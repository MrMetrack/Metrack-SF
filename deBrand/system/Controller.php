<?php

namespace system;

use app\Config\App as ConfigApp;

/**
 * Controller
 */
class Controller
{
    /**
     * View
     *
     * @param  mixed $viewfile - dit is het bestand inclusief subdir in de viewmap. bestand extensie hoeft niet opgegeven te worden.
     * @param  mixed $viewdata - Eventuele data die in het view bestand gebruikt gaat worden.
     * @param  mixed $themeData - Indien er afgeweken wordt van de standaard thema.
     * @return void
     */
    public static function View($viewfile, $viewdata = array(), $themeData = array())
    {
        // Ophalen van de content die weergegeven kan wordne in layout bestand van de thema.
        $themeData["content"] = self::getContent($viewfile, $viewdata);

        //Het thema traject starten
        self::theme($themeData);
    }

    /**
     * getContent
     * Openen van het opgegeven view bestand en deze als string content terug sturen.
     * @param  string $viewfile - bestandsnaam (zonder extensie) met eventuele pad.
     * @param  array $viewdata - Bevat data die in het viewbestand gebruikt kan worden
     * @return string - Stuurt de inhoud van het view bestand retour in string formaat.
     */
    protected static function getContent($viewfile = "", $viewdata = array())
    {
        try {
            // Vooraf defineren van deze variable zodat deze ten allertijden een waarde bevat.
            $returnvalue = "";

            $viewpath = "../" . _APPDIR_ . "views/" . $viewfile . ".php";

            // controleren of bestand bestaat en geopend kan worden. Indien ja dan 
            // functei onder if uitvoeren.
            if (file_exists($viewpath)) {

                // de keys in de array $viewdata omzetten naar variabele namen.
                extract($viewdata, EXTR_OVERWRITE);

                // View bestand openen, uitvoeren en als string content meegeven aan $returnvalue;
                ob_start();
                require_once($viewpath);
                $returnvalue = ob_get_contents();
                ob_end_clean();
            } else {
                // Als bestand niet geopend kan worden dan dit uitvoeren.
                throw new \Exception("Kan view " . $viewpath . " niet openen");
                $themeData["content"] = "404 error";
            }

            return $returnvalue;
        } catch (\Exception $e) {
            echo "Er is iets mis gegaan waardoor de view niet geopend kan worden.";
        }
    }

    /**
     * theme
     * Het invullen van het thema bestand
     * @param  array $data - bevat data die gebruikt kan worden in het layout bestand 
     * @return void
     */
    protected static function theme($data)
    {
        try {
            // Hier wordt gecontroleerd of vanuit de controller aangegeven wordt of er afgeweken wordt van de
            // standaard thema. Zo niet dan de standaard thema van het Env bestand gebruiken.
            if (!isset($data["theme"]) or $data["theme"] == null) $data["theme"] = Env::get("app.theme.name");

            // Hier wordt gecontroleerd of vanuit de controller aangegeven wordt of er afgeweken wordt van de
            // standaard layout. Zo niet dan de standaard layout van het Env bestand gebruiken.
            if (!isset($data["layout"]) or $data["layout"] == null) $data["layout"] = Env::get("app.theme.defaultlayout");

            $themepath = "../" . _APPDIR_ . "themes/" . $data["theme"] . "/";
            $layoutpath = $themepath . "" . $data["layout"] . ".php";

            // Deze variable bevat alle css data die hoort bij het betreffende thema en layout.
            $data["cssContent"] = self::readCssStyleFile($themepath, $data["layout"]);

            // controleren van het bestand gevonden en geopend kan werden. Indien ja 
            // dan middels require_once het bestand openen en uitvoeren.
            if (file_exists($layoutpath)) {
                require_once($layoutpath);
            } else {
                throw new \Exception("Kan layout " . $layoutpath . " niet openen");
            }
        } catch (\Exception $e) {
            echo "Er is iets mis gegaan waardoor het layout niet geopend kan worden.";
        }
    }

    /**
     * readCssStyleFile
     * importeren van css opmaak regels naar het layout. Om het aantal requesten tot het minimum te beperken
     * zal deze functie alle thema css opmaak bestanden als content versturen naar het layout bestand van 
     * de betreffende thema.
     * @param  string $themepath - bevat het pad naar de thema directory
     * @param  string $layout
     * @return string csscontent - alle css content wordt gesamelijk als string geretourneerd. 
     */
    protected static function readCssStyleFile($themepath, $layout)
    {
        // Bij iedere layout bestand is ook een style.css bestand verplicht. Hierin staat
        // een verzameling van css bestanden die ingelezen moeten worden. 
        // De css bestanden die ingelezen worden zijn allemaal opgeslagen in de subfolder css 
        // van dit thema. 
        $stylePath = $themepath . $layout . "-Style.css";

        $csscontent = ""; //Deze variable wordt vooraf gedefineerd om errors te voorkomen.

        if (file_exists($stylePath)) {

            //Indien bestand bestaat dan onderstaande loop uitvoeren.
            foreach (file($stylePath) as $line) {

                // Check of $line een geldig css bestand bevat.
                if (preg_match('/.*\.(?:css)/i', $line)) {

                    //Openen van css bestand en in een geheel importeren als string data in $csscontent.
                    ob_start();
                    require_once($themepath . "css/" . trim($line));
                    $csscontent .= ob_get_contents();

                    // de boel schoonmaken zodat bij een volgende actie geen oude data nogmaals aan $csscontent wordt toegevoegd.
                    ob_end_clean();
                }
            }
        }


        //Alle onnodige enters en lege regels verwijderen zodat het css bestand als compacte data geretourneerd kan worden.
        $csscontent = trim(preg_replace('/\s\s+/', ' ', $csscontent));

        return $csscontent;
    }

    protected static function readViewAddedCssFiles()
    {
    }

    protected static function readCssFile()
    {
    }
}
