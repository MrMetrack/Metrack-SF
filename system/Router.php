<?php

namespace system;

use Exception;
use system\lib\AuthorisedAccess;
use system\lib\Permission;
use system\lib\Segments;

/**
 * Router
 */
class Router
{
    private array $_Segments = array();
    private array $_RoutingSegments = array();
    private $_MatchController = null;

    /**
     * __construct
     * Hier wordt gestart met het ophalen van alle segmenten door de class Segments op te roepen.
     * @return void
     */
    public function __construct()
    {
        $this->_Segments = Segments::getSegment("ALL");
    }

    /*====================================================================================================================
     * setRouting 
     *--------------------------------------------------------------------------------------------------------------------
     * setRouting(string $from, string $to, $AuthReq = false, $Permission = "public=3")
     * Middels deze functie kan een route toegevoegd worden. 
     * @param string $from - Deze parameter moet gelijk zijn aan de url segmenten die naar de controller moeten verwijzen.
     * @param string $to - Deze paramter gevat de controller class en object in stringvorm zoals "Home::index".
     * @param boolean $AuthReq - Als deze parameter op true staat dan dient gebruiker ingelogd te zijn op deze pagina te bekijken.
     * @param string $Permission - Geef hier aan of er rechten nodig zijn voor het openen van de betreffende controller/pagina
     /*=====================================================================================================================*/
    public function setRouting(string $from, string $to, $AuthReq = false, $Permission = "public=3")
    {
        $this->_RoutingSegments[$from]["to"] = $to;
        $this->_RoutingSegments[$from]["AuthReq"] = $AuthReq;
        $this->_RoutingSegments[$from]["permission"] = $Permission;
    }


    /*====================================================================================================================
     * getRouting
     * --------------------------------------------------------------------------------------------------------------------
     * Deze functie gaat uitzoeken welke routing een match vormt met de segmenten die afkomstig zijn uit de url.
     * Voor het maken van de segmenten zie functie Router::getSegments.
     * @return void
     /*=====================================================================================================================*/
    public function getRouting()
    {

        $segmentsString = null; //vooraf defineren van $segmentsString zodat deze altijd een waarde bevat.

        // Midels onderstaande loop worden alle segmenten van het webadres gecontroleerd.
        for ($t = 0; $t < count($this->_Segments); $t++) {

            // Toevoegen van segment aan de segment string omdat de key van $this->_RoutingSegments[KEY] via setRouting als
            // referentie is gedefinieerd. 
            $segmentsString .= $this->_Segments[$t];

            if (array_key_exists($segmentsString, $this->_RoutingSegments)) {

                // Dit deel wordt uitgevoerd als een array key overeenkomt met het segment ($segmentString)
                // Indien _MatchController is gevuld dan zal deze overschreven worden door de nieuwe match.

                $this->_MatchController = $this->_RoutingSegments[$segmentsString];
                $segmentsString .= "/"; // nieuwe forward slash toevoegen voor nieuwe vergelijking. 

            } elseif (!array_key_exists($segmentsString, $this->_RoutingSegments) && !empty($this->_MatchController)) {
                // voorkom onnodige loops. De vergelijking hierboven wordt uitgevoerd als er geen match is in de array key's 
                // en _MatchController wel gevuld is. Dat houdt dus in dat de vorige match de laatst gevonden match is. Die
                // zal dan gebruikt worden als resultaat.
                break;
            } else {

                // Als er geen van bovenstaande voorwaardes een match geven dan zal er altijd een / toegevoegd worden. 
                $segmentsString .= "/"; // nieuwe forward slash toevoegen voor nieuwe vergelijking. 
            }
        }

        // De $this->_MatchController kan alleen leeg zijn als er in app/Routing.php geen setRouting() is geconfigureerd met
        // get segment dat tijdens deze controlle als webadres is opgegeven. Ofwel de pagina / controller is niet gevonden.
        // Dit zal de onderstaande if functie dan ook laten uitvoeren.
        if ($this->_MatchController == null) {

            // als er geen enkele match plaats vind in de loop dan zal er verwezen worden naar /.
            //$this->_MatchController = $this->_RoutingSegments["/paginanietgevonden"];
            header("Location: /paginanietgevonden");
            exit();
        }
    }

    /**
     * Deze functie zorgt er voor dat de controller als object gebruikt gaat worden. 
     */
    public function callController()
    {
        self::getRouting(); // Uitzoeken welke Routing overeenkomt met de segmenten uit het webadres.


        // Indien blijkt dat de matchcontroller toch leeg is dan de default controller gebruiken.
        if (!is_array($this->_MatchController) or $this->_MatchController["to"] == null) {
            $this->_MatchController["to"] = Env::get("app.controllers.default");
            $this->_MatchController["permission"] = Env::get("app.permission.default");
            $this->_MatchController["AuthReq"] = false;
        }

        // Indien op app/Routing.php bij de betreffende regel achteraan een permission is ingesteld die anders is dan public=3
        // dan zal de acties onder deze if uitgevoerd worden.
        if ($this->_MatchController["permission"] != "public=3") {

            // uitsplitsen van permission alias en permission level. Alias is nodig om in de DB de juiste rule te vinden.
            // Level is nodig om te controlen of de gebruiker op het juiste niveau zit of hoger om toegang te krijgen tot de 
            // opgeroepen controller.
            [$PermissionAlias, $permissionLevel] = explode("=", $this->_MatchController["permission"]);

            //Hier wordt gecontroleerd of de gebruiker wel het juiste niveau heeft. Als dit false is dan zal Error:accessdennied 
            //als conroller uitgevoerd worden.
            if (!Permission::checkPermission($PermissionAlias, $permissionLevel)) {
                $this->_MatchController["to"] = "Error::accessdennied";
            }
        }

        // Indien op app/Routing.php bij de betreffende regel is op gegeven dat de gebruiker geautoriseerd moet zijn om de betreffende
        // conroller/pagina te openen en LoggedIn false als return geeft omdat de gebruiker niet is ingelogd dan zal
        // de header er voor zorgen dat /login pagina als nieuwe request vanuit de browser naar de server verstuurd wordt.
        // Exit zorgt er voor dat de rest van de applicatie niet meer uitgevoerd wordt. 
        if ($this->_MatchController["AuthReq"] == true and AuthorisedAccess::LoggedIn() == false) {
            header("Location: " . __BASEURL__ . "/login");
            exit();
        }

        //Als alle bovenstaande geen belemering vormen voor de uitvoering dan kan er begonnen worden aan het uitvoeren 
        //van de controller. Hieronder wordt de classenaam gescheiden van de class functie. 
        $getFilename = explode('::', $this->_MatchController["to"]);

        try {
            $controllerPath = '../' . _APPDIR_ . 'controllers/' . $getFilename[0] . ".php"; // instellen van pad naar het contoller bestand.

            if (!file_exists($controllerPath)) {
                // Als bestand niet gevonden kan worden dan de taken in deze if uit voeren.
                throw new Exception('Kan controller niet vinden');
            } else {
                // Als bestand gevonden is dan kan doormiddel van require de controller geopend worden..
                require_once('../' . _APPDIR_ . 'controllers/' . $getFilename[0] . ".php");

                // Deze functie zorgt er voor dat de string waarde van TO omgezet wordt naar een oproepbaar
                // object. Tevens zal deze ook door de functie zelf opgeroepen worden. 
                call_user_func("app\controllers\\" . $this->_MatchController["to"] . "");
            }
        } catch (Exception $e) {
            // Indien bovenstaande niet mag baten dan deze error afhandeling starten en applicatie stoppen.
            echo $e->getCode() . ":" . $e->getMessage();
            exit();
        }
    }
}
