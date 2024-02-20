<?php

namespace system;

/**
 * Env
 */
class Env
{
    /**
     * parse
     * In dit object wordt het .Env bestand uitgelezen en opgeslagen in $_ENV parameters    
     * @return void
     */
    public static function parse()
    {
        // Openen en ophalen van alle data uit het Env bestand. 
        //Eventuele overbodige nieuwe lijnen (FILE_IGNORE_NEW_LINES) en legen lijnen (FILE_SKIP_EMPTY_LINES) overslaan.
        $EnvLines = file(_PPATH_ . "../.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        //Lijn voor lijn het bestand uitlezen
        foreach ($EnvLines as $line) {

            // Indien bestand begint met een # dan deze lijn overslaan en doorgaan met loop.
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2); // Splitsen van parameter naam en waarde.

            $name = trim($name);
            $value = trim($value);
            $value = str_replace(['\'', '"'], '', $value);

            $_ENV[$name] = $value; // Vastleggen van parameter in $_ENV
        }
    }

    /**
     * get
     * Ophalen van waarde uit $_ENV[$name]. Moet een bestaande key zijn. Deze komt overeen met de paramter naam in het .Env bestand.
     * @param  mixed $name - Deze komt overeen met de parameter naam uit het .Env bestand.
     * @return void
     */
    public static function get($name)
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        } else {

            return null;
        }
    }

    /**
     * defineBaseUrl
     * Voor het gemaak wordt de app.base.url gedifineerd als een snel benaderbare constante __BASEURL__
     * @return void
     */
    public static function defineBaseUrl()
    {
        define("__BASEURL__", self::get("app.base.url"));
    }
}
