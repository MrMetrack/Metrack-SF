<?php

namespace system\lib;

class AuthorisedAccess
{
    protected static $algo = PASSWORD_DEFAULT;

    /**
     * passwordHash
     * genereert een passwordHash op basis van het wachtwoord en het vastgestelde algoritme $algo.
     * @param  string $password
     * @return string Hash
     */
    public static function passwordHash($password)
    {

        return password_hash($password, self::$algo);
    }

    /**
     * generatePassword
     * Wachtwoord generator
     * @param  int $numberOfCharacters - aantal karakters dat het wachtwoord moet bevatten.
     * @return string 
     */
    public static function generatePassword($numberOfCharacters = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$#!@';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $numberOfCharacters; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * LoggedIn
     * Controleerd of de gebruiker is ingelogd. De controle wordt uitgevoerd op basis van 
     * tijd tussen 2 webrequesten.
     * @return boolean - true als login sessie nog actief is en false als deze verlopen is
     */
    public static function LoggedIn()
    {
        //Controleerd of er een session met de naam lac aanwezig is
        if (isset($_SESSION["lac"])) {

            //Haalt de data op van lac en converteert deze naar DateTime
            $lac = new \DateTime($_SESSION["lac"]);

            //Berekend het verschil tussen de huidige datetime en de
            //datetime die in de regel hierboven is opgehaald.
            $diff = $lac->diff(new \DateTime(date("Y-m-d h:i:s")));

            //Berekenen van het verschil in tijd in minuten.
            $minutes = $diff->days * 24 * 60;
            $minutes += $diff->h * 60;
            $minutes += $diff->i;

            //Als het verschil in tijd kleiner of gelijk is aan 10 dan true retour sturen. 
            //De gebruiker is dus nog ingelogd. Tevens lac voorzien van nieuwe tijd en datum.
            //Indien het verschil in tijd groter is dan 10 minuten dan session vernietigen middels
            //Session_destroy en false retourneren. 
            if ($minutes <= 10) {
                $_SESSION["lac"] = date("Y-m-d h:i:s");
                return true;
            } else {
                session_destroy();
            }
        }
        return false;
    }

    /**
     * checkLogin
     * Controleren of de combinatie gebruikersnaam en wachtwoord goed is.
     * @param  string $username 
     * @param  string $password
     * @return boolean - true als waar en false als niet waar
     */
    public static function checkLogin($username, $password): bool
    {

        //Gegevens ophalen uit database 
        $db = new Database();
        $db->select("id, password, RolesId")->from("dfr_userAccounts")->where("username=" . $username);
        $data = $db->get();

        //Controleren of er maar 1 gebruikersnaam gevonden is. Bij meerdere resultaten klopt er iets niet.
        if (count($data) == 1) {

            //Wachtwoord verrificatie uitvoeren.
            if (password_verify($password, $data[0]["password"]) == true) {

                //Indien alles goed nieuwe session registreren. 
                $_SESSION["lac"] = date("Y-m-d h:i:s"); // LAC = Last Access Check
                $_SESSION["UserId"] = $data[0]["id"]; // Gebruikers id
                $_SESSION["RolesId"] = $data[0]["RolesId"]; // Role id voor toegangsrechten

                return true;
            }
        }
        return false;
    }

    public static function logout(): bool
    {
        session_destroy();

        $lac_deleted = (!isset($_SESSION["lac"])) ? true : false;
        $UserId_deleted = (!isset($_SESSION["UserId"])) ? true : false;
        $RolesId_deleted = (!isset($_SESSION["RolesId"])) ? true : false;

        return (($lac_deleted) && ($UserId_deleted) && ($RolesId_deleted)) ? true : false;
    }
}
