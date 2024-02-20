<?php

namespace app\controllers;

use system\Controller;
use system\lib\AuthorisedAccess;
use system\lib\Database;
use system\lib\FormValidation;
use system\lib\Request;
use system\lib\Segments;

class Gebruikersrollen extends Controller
{

    public static function index()
    {
        $viewData["roles"] = [];
        $db = new Database();
        $db->select()->from("dfr_roles");
        $dbdata = $db->get();

        foreach ($dbdata as $rol) {
            $rolarray = [];
            foreach ($rol as $key => $val) {
                $rolarray[$key] = $val;
                if ($key == "RolesId") {
                    $rolarray["rules"] = self::getPermissions($val);
                }
            }
            $viewData["roles"][] = $rolarray;
        }
        parent::View("gebruikersrollen/rollenoverzicht", $viewData);
    }

    private static function getPermissions($role_id)
    {
        $returnarray = [];
        $db = new Database();
        $db->select()->from("dfr_permissions")->join("dfr_rules", "dfr_permissions.rulesId=dfr_rules.RulesId")->where("rolesId=" . $role_id);
        $dbdata = $db->get();

        foreach ($dbdata as $permission) {

            $returnrules = [
                "rulename" => $permission["name"],
                "rulelevel" => $permission["level"]
            ];
            $returnarray[] = $returnrules;
        }
        return $returnarray;
    }

    public static function edit()
    {
        $id = Segments::getSegment(2);
        if (is_numeric($id)) {
            $viewData = [];
            if (Request::input("submit") == "Opslaan") {
                $fv = new FormValidation();
                $fv->setValidationRule("RolesName", "Rolnaam", "required|min_length[2]|is_unique[dfr_roles.RolesName]");
                $fv->run();
                if ($fv->hasErrors() == true) {
                    $viewData["formerrors"] = $fv->Errors();
                } else {
                    self::saveRole($id);
                    //self::saveRules($id);
                }
            }


            $dbq = new Database();
            $dbq->select()->from("dfr_roles")->where("RolesId=" . $id);
            $dbdata = $dbq->get();
            $viewData["rol"] = $dbdata[0];
            $rules = self::getAllRules();
            $viewrules = [];
            foreach ($rules as $rule) {

                $rule["level"] = self::getPermissionsSates($id, $rule["RulesId"]);
                $viewrules[] = $rule;
            }

            $viewData["rules"] = $viewrules;

            parent::View("gebruikersrollen/rolbewerken", $viewData);
        } else {
            header("Location: " . __BASEURL__ . "/home");
        }
    }

    private static function getAllRules()
    {
        $dbd = new Database();
        $dbd->select()->from("dfr_rules");
        return $dbd->get();
    }

    private static function getPermissionsSates($role_id, $rule_id): int
    {
        $db1 = new Database();
        $db1->select("level")->from("dfr_permissions")->where("rulesId=" . $rule_id)->where("rolesId=" . $role_id);
        $array = $db1->get();
        if (isset($array[0]["level"])) {
            return isset($array[0]["level"]);
        } else {
            return 0;
        }
    }

    private static function saveRules($roleid)
    {
        $rules = self::getAllRules();
        foreach ($rules as $rule) {
            $rulepermission = Request::input("ruleid-" . $rule["RulesId"]);
            echo $rulepermission . "<br>";
        }
    }

    private static function saveRole($roleid)
    {
        echo "dit";
        $db = new Database();
        $dataToUpdate = [
            "RolesName" => Request::input("RolesName"),
        ];
        $db->from("dfr_roles")->where("RolesId=" . $roleid)->update($dataToUpdate);
    }
}
