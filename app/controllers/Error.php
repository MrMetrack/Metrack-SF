<?php

namespace app\controllers;

use system\Controller;

class Error extends Controller
{

    public static function index()
    {
        parent::View("dashboard");
    }
    public static function pagenotfound()
    {
        parent::View("errors/404error");
    }

    public static function accessdennied()
    {
        parent::View("errors/accessdennied");
    }
}
