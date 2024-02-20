<?php

namespace system;

require_once("Loader.php"); //Inladen van autoloader.

use system\Router;

/**
 * Bootstrap
 * Bootstrap zorgt er voor dat de belangrijkste functies aangeroepen worden.
 */
class Bootstrap
{
    /**
     * run
     * Het object Run draagt zorg over en starten van de applicaties.  
     * @return void
     */
    public static function run()
    {
        self::DefineConstants();

        /*=====================================================================================================================
         * Start Router
         * --------------------------------------------------------------------------------------------------------------------
         * 
         * Oproepen van de Router Class voor het bepalen wat de gebruiker
         * te zien krijgt.         
         /*=====================================================================================================================*/

        $Router = new Router();

        //In het bestand Routing.php welke te vinden is in de app directory wordt opgegeven welke combinatie van segmenten 
        //welke controller kunnen openen.

        if (file_exists("../" . _APPDIR_ . "Routing.php")) {
            require_once("../" . _APPDIR_ . "Routing.php");
        }

        //het object CallControl zorgt er voor dat de juiste controller geopend wordt.
        $Router->callController();
    }

    /**
     * DefineConstants
     * In dit object worden de contstanten ingesteld die elders in de applicatie opgeroepen kunnen worden.
     * @return void
     */

    protected static function DefineConstants()
    {
        define('_PPATH_', __DIR__ . DIRECTORY_SEPARATOR); // defineren van pad naar public folder.
        $fulluri = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        define('_FULLURI__', $fulluri);
        define('_APPDIR_', "app/");
        define("_UPLOADDIR_", _PPATH_ . "../upload/");


        Env::parse(); // Dit object zorgt er voor dat alle paramters welke zijn opgeslagen in .Env bestand getransporteerd worden naar $_ENV.
        Env::defineBaseUrl(); //Gezien de urgentie van de BaseURL wordt deze als constante gedefineerd.
    }
}

spl_autoload_register(__NAMESPACE__ . "\Loader::AutoLoader"); // Zorgt er voor dat class welke opgeroepen worden automatisch ingeladen worden.
