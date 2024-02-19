<?php

namespace system\lib;

class Segments
{
    private static array $_Segments = array();


    /**
     * Deze functie kijkt haalt uit de url de bruikbare segmenten achter het domeinadres.
     * De opgehaalde segmenten kunnen middels de array $this->Segments gebruikt worden.
     */
    private static function getSegments()
    {
        $uriPath = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if ($uriPath == "/") {
            self::$_Segments[] = "/";
        } else {
            $segments = explode('/', $uriPath);

            // Tijdens het testen is gebleken dat er tot maximaal 2 segmenten overbodig kunnen zijn. 
            // Vandaar 2 keer onderstaande functie. 
            $segments = self::RemoveUnnecessarySegment($segments, 0);
            self::$_Segments = self::RemoveUnnecessarySegment($segments, 0);
        }
    }

    /**
     * Controleer of eerste segment in array leeg is of index.php bevat. 
     * Beide zijn in de reeks van Segmenten overbodig en kunnen dus verwijderd worden. 
     * Indien de betreffende segment een andere waarde dan leeg of index.php bevat en dus een functionele segment kan zijn 
     * zal de segment niet verwijderd worden. Herschikken (array_values) heeft verder geen invloed.
     * @param array $Segments 
     * @return array
     */
    private static function RemoveUnnecessarySegment($Segments)
    {
        if (empty($Segments[0])) {
            unset($Segments[0]);
        }
        return array_values($Segments);
    }

    /**
     * getSegment
     * Segmenten opvragen. 
     * @param  mixed $num - Dit is het segment nummer in het webadres. 
     *                      Er kan ook een string waarde ALL meegegeven worden. In dat geval wordt de volledige string als return terug gestuurd.
     * @return void - Indien de param $num een cijfer bevat dan zal de return de opgevraagde segment als string retour sturen.
     *              - Indien de param $num de tekst ALL bevat dan zal er een array retour gestuurd worden. 
     */
    public static function getSegment($num)
    {
        self::getSegments();
        if (is_int($num)) {
            return self::$_Segments[$num];
        }
        if (is_string($num)) {
            if ($num = "ALL") {
                return self::$_Segments;
            }
        }
        return null;
    }
}
