<?php

namespace system;

/**
 * Loader
 * De class Loader zorgt voor het laden van bestanden.
 */
class Loader
{
    /**
     * AutoLoader
     * De autoloader zorgt er voor dat middels één simpel object andere class die gebruikt worden 
     * ingeladen kunnen worden zonder voor iedere class en require of include te schrijven.
     * Dit object hoeft niet opgeroepen te worden. Onderaan in het bestaand Bootstrap wordt dit
     * object opgeroepen.
     * @param  mixed $className - in combinatie met use path. 
     * @return void
     */
    public static function AutoLoader($className)
    {
        $className = str_replace('\\', '/', $className); // backward slash vervangen door forward slash zodat bestand middels filepad gevonden kan worden.

        $filepath =  "../" . $className . '.php';

        if (file_exists($filepath)) {
            require $filepath;
        }
    }

    /**
     * LoadHelper
     * De LoadHelper zorgt er voor dat helpers ingeladen kunnen worden. Helpers zijn bestanden met geprogrammeerde functies die geen gezamelijke 
     * class vormen. 
     * @param  string $helper 
     * @return void
     */
    public static function LoadHelper($helper)
    {
        $appHelperPath = "../app/helpers/" . $helper . ".php";
        $systemHelperPath = "./helpers/" . $helper . ".php";
        if (file_exists($appHelperPath)) {
            require_once($appHelperPath);
        } elseif (file_exists($systemHelperPath)) {
            require_once($systemHelperPath);
        } else {
            throw new \Exception("Er is geen helper bestand gevonden met de naam " . $helper);
        }
    }
}
