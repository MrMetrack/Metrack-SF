<?php

namespace system\lib;

class Permission
{
    /**
     * checkPermission
     * Controleren over de gebruiker genoeg rechten heeft. Zo ja dan een true return. Zo nee dan een false return;
     * @param  mixed $ruleAlias
     * @param  mixed $level
     * @return void
     */
    public static function checkPermission($ruleAlias, $level)
    {


        //Controleren in database welke rechten er bij de betreffende alias hoort.
        $db = new Database();
        $db->select("level, id")->from("dfr_permissions")->join("dfr_rules", "dfr_permissions.rulesId=dfr_rules.RulesId")->where("dfr_rules.alias=" . $ruleAlias)->where("dfr_permissions.rolesId=" . $_SESSION["RolesId"]);
        $dbdata = $db->get();

        $dblevel = 0; // Variable vooraf van data voorzien om problemen te voorkomen.

        //Deze voorwaarde controleerd of er een regel in de database is gevonden. Zo ja dan zal if uitgevoerd worden. Zo nee dan blijft $dblevel = 0;
        if (isset($dbdata[0]["level"])) {
            $dblevel = $dbdata[0]["level"];
        }

        // deze voorwaarde controleerd of de gebruiker gelijke of hogere rechten heeft dan wat er bij deze regel/rule is ingesteld. 
        if ($dblevel >= $level) {
            return true;
        } else {
            return false;
        }
    }
}
