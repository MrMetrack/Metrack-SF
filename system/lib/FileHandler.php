<?php

namespace system\lib;

class FileHandler
{
    private $errorReport = null;


    public function __construct()
    {
    }

    public static function read($pathToFile, $mode = "r")
    {
        $fileOpen = @fopen($pathToFile, $mode) or die("Kan bestand " . $pathToFile . " niet openen");
    }


    /**
     * 
     * @return boolean
     */
    public static function write($pathToFile = "", $file = "", $textToWrite = "", $mode = "a")
    {
        $fileOpen = @fopen($pathToFile . $file, $mode) or die("Kan bestand " . $pathToFile . $file . " niet openen");
        fwrite($fileOpen, $textToWrite . "\n");
        fclose($fileOpen);
    }
}
