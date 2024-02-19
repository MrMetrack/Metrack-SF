<?php
session_start();

/*=====================================================================================================================
 * ERROR LOGGING
 *---------------------------------------------------------------------------------------------------------------------
 * Onderstaande settings zorgen er voor dat alle error meldingen in eht logboek opgeslagen worden. Iedere dag wordt er 
 * een nieuwe logboek file aangemaakt. 
 * Display_errors en display_startup_errors staan standaard op 0. Hiermee 
 * voorkomen we dat de error zichtbaar zijn op de webpagina. 
 * ===================================================================================================================*/

ini_set("log_errors", 1);
ini_set("error_log", "../upload/log/" . date("Y-m-d") . ".log");
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

/*=====================================================================================================================
 * Begin met controleren PHP versie
 *---------------------------------------------------------------------------------------------------------------------
 * controleren welke php versie gebruikt wordt. Indien de php versie op de server lager is dan de versie 
 * waarvoor deze ontworpen is dan wordt middels exit de run van de verdere applicatie afgebroken.
 * ===================================================================================================================*/
$minPhpVersion = 8.2;

if (version_compare(phpversion(), $minPhpVersion, '<')) {
    $msg = sprintf(
        "PHP Warning:: De huidige php versie voldoet niet aan de minimaal vereiste php. De server/hosting staat ingesteld op php versie %s. De minimaal vereiste php versie is %s",
        phpversion(),
        $minPhpVersion
    );
    error_log($msg);

    exit("Controleer logboek voor fouten");
}



/*=====================================================================================================================
 * Bootstrap - Start Framework
 *---------------------------------------------------------------------------------------------------------------------
 * Hieronder wordt de bootstrap ingeladen in middels Run uitgevoerd. Dit bestand zorgt er voor dat het framework
 * tot leven komt.
 * ===================================================================================================================*/

require_once "../system/Bootstrap.php";

use system\Bootstrap;

Bootstrap::run();
