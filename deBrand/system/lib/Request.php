<?php

namespace system\lib;

/**
 * Request
 */
class Request
{
    /**
     * input
     * Ophalen van $_REQUEST["KEYNAME"] data
     * @param  mixed $name - Dit is de key die in $_REQUEST gebruikt wordt
     * @return void - Dit bevat de data die na opschonen terug gestuurd wordt.
     */
    public static function input($name)
    {
        $value = null;

        if (isset($_REQUEST[$name])) {
            $value = $_REQUEST[$name];
            $value = self::cleanUpValue($value);
        }

        return $value;
    }

    public static function RawInput($name)
    {
        if (isset($_REQUEST[$name])) {
            return $_REQUEST[$name];
        }

        return null;
    }

    /**
     * post
     *
     * Ophalen van $_POST["KEYNAME"] data
     * @param  mixed $name - Dit is de key die in $_POST gebruikt wordt
     * @return void - Dit bevat de data die na opschonen terug gestuurd wordt.
     */
    public function post($name)
    {
        $value = null;

        if (isset($_POST[$name])) {
            $value = $_POST[$name];
            $value = self::cleanUpValue($value);
        }

        return $value;
    }

    /**
     * get
     * Ophalen van $_GET["KEYNAME"] data
     * @param  mixed $name - Dit is de key die in $_GET gebruikt wordt
     * @return void - Dit bevat de data die na opschonen terug gestuurd wordt.
     */
    public static function get($name)
    {
        $value = null;

        if (isset($_GET[$name])) {
            $value = $_GET[$name];
            $value = self::cleanUpValue($value);
        }

        return $value;
    }

    /**
     * cleanUpValue
     * Deze functie zorgt er voor dat de data schoon gemaakt wordt van onnodige witruimtes, html tekens en loze karakters
     * @param  string $value - de data die opgeschoont moet worden
     * @return string - de schone data die terug gestuurd wordt. 
     */
    private static function cleanUpValue($value)
    {
        if (!empty($value)) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            return $value;
        } else {
            return null;
        }
    }
}
