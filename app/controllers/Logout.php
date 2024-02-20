<?php

namespace app\controllers;

use SessionHandler;
use system\Controller;
use system\lib\AuthorisedAccess;
use system\lib\Database;
use system\lib\FormValidation;
use system\lib\Request;

class Logout extends Controller
{

    public static function index()
    {

        if (AuthorisedAccess::logout()) {
            $viewData["message"] = "U bent met succes uitgelogd. Over enkele seconden sturen wij je door naar de homepage.";
            $viewData["redirectUrl"]  = __BASEURL__ . "/";
        } else {
            $viewData["message"] = "Een ogenblik geduld a.u.b. u wordt uitgelogd.";
            $viewData["redirectUrl"] = __BASEURL__ . "/logout";
        }

        parent::View("logout", $viewData);
    }

    public static function sessionExpired()
    {
        $viewData["message"] = "Sorry, uw login sessie is verlopen. U dient opnieuw in te loggen om verder te gaan.";
        $viewData["redirectUrl"] = null;
        parent::View("logout", $viewData);
    }
}
