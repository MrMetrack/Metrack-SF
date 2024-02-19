<?php

namespace app\controllers;

use system\Controller;

class Home extends Controller
{

    public static function index()
    {
        parent::View("home");
    }
}
