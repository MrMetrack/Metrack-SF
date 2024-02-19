<?php

namespace app\controllers;

use system\lib\AuthorisedAccess;
use system\Controller;
use system\lib\Database;
use system\Env;
use system\lib\FormValidation;
use system\lib\Request;

class Registreren extends Controller
{
    public static function index()
    {
        $viewData["fullname"] = Request::input("fullname");
        $viewData["email"] = Request::input("email");
        if (Request::input("submit") == "Registreren") {
            $fv = new FormValidation();

            $fv->setValidationRule("fullname", "Naam", "required|min_length[2]");
            $fv->setValidationRule("email", "E-mailardres", "required|min_length[5]|valid_email|is_unique[dfr_userAccounts.email]");
            $fv->setValidationRule("password", "Wachtwoord", "required|min_length[8]|valid_password|valid_password[passwordAgain]");
            $fv->run();

            if ($fv->hasErrors() == true) {
                $viewData["formerrors"] = $fv->Errors();
                parent::View("registreren", $viewData);
            } else {

                $d = new Database();

                $roleId = $d->from("dfr_userAccounts")->count() == 0 ? 1 : 2;
                $d->table = "dfr_userAccounts";
                $dbdata = [
                    "username" => $viewData["email"],
                    "email" => $viewData["email"],
                    "name" => $viewData["fullname"],
                    "RolesId" => $roleId,
                    "password" => AuthorisedAccess::passwordHash(Request::input("password")),
                ];
                if ($d->insert($dbdata) == 1) {
                    parent::View("succesvol_geregistreerd", $viewData);
                } else {
                    $viewData["formerrors"] = [["Er is iets mis gegaan"]];
                    parent::View("registreren", $viewData);
                }
            }
        } else {
            parent::View("registreren", $viewData);
        }
    }
}
