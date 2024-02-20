<?php

namespace app\controllers;

use system\Controller;
use system\lib\AuthorisedAccess;
use system\lib\Database;
use system\lib\FormValidation;
use system\lib\Request;

class Login extends Controller
{
    public static function index()
    {
        if (AuthorisedAccess::LoggedIn()) {
            header("Location: " . __BASEURL__ . "/dashboard");
        } else {
            $viewData["email"] = Request::input("email");
            if (Request::input("submit") == "Login") {
                $fv = new FormValidation();
                $fv->setValidationRule("email", "E-mailardres", "required|valid_email");
                $fv->setValidationRule("password", "Wachtwoord", "required|login[email]");
                $fv->run();
                if ($fv->hasErrors() == true) {
                    $viewData["formerrors"] = $fv->Errors();
                    parent::View("login", $viewData);
                } else {
                    header("Location: " . __BASEURL__ . "/blogs");
                }
            } else {
                parent::View("login", $viewData);
            }
        }
    }
}
