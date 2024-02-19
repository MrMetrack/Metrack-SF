<?php

namespace app\controllers;

use system\Controller;
use system\lib\AuthorisedAccess;
use system\lib\Database;
use system\lib\FormValidation;
use system\lib\Request;

class MijnGegevens extends Controller
{

    public static function index()
    {
        $viewData["fullname"] = Request::input("fullname");
        $viewData["email"] = Request::input("email");
        $viewData["successful"] = false;
        if (Request::input("submit") == "Opslaan") {
            $fv = new FormValidation();

            $fv->setValidationRule("fullname", "Naam", "required|min_length[2]");
            $fv->setValidationRule("email", "E-mailardres", "required|min_length[5]|valid_email");
            $fv->run();

            if ($fv->hasErrors() == true) {
                $viewData["formerrors"] = $fv->Errors();
            } else {
                $db = new Database();
                $dataToUpdate = [
                    "name" => Request::input("fullname"),
                    "email" => Request::input("email")
                ];
                $db->from("dfr_userAccounts")->where("id=" . $_SESSION["UserId"])->update($dataToUpdate);
                $viewData["successful"] = true;
            }
        } else {
            $db = new Database();
            $db->select("name, email")->from("dfr_userAccounts")->where("id=" . $_SESSION["UserId"]);

            $dbdata = $db->get();

            $viewData["fullname"] = $dbdata[0]["name"];
            $viewData["email"] = $dbdata[0]["email"];
        }

        parent::View("mijngegevens", $viewData);
    }
}
