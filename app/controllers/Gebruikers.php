<?php

namespace app\controllers;

use system\Controller;
use system\lib\Database;
use system\lib\Permission;
use system\lib\Segments;

class Gebruikers extends Controller
{

    public static function index()
    {
        $viewData["users"] = [];
        $db = new Database();
        $db->select("id,name, email, username, dfr_roles.RolesName")->from("dfr_userAccounts")->join("dfr_roles", "dfr_userAccounts.RolesId=dfr_roles.RolesId");
        $dbdata = $db->get();

        $viewData["users"] = $dbdata;


        parent::View("gebruikers/gebruikersoverzicht", $viewData);
    }

    public static function edit()
    {
        $id = Segments::getSegment(2);
        if ($id) {
            parent::View("gebruikerbewerken");
        } else {
            header("Location: " . __BASEURL__ . "/gebruikers");
        }
    }
}
