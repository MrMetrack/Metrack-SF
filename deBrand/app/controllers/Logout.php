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
        session_destroy();
        header("Location: /");
    }
}
