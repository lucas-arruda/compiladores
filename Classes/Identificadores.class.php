<?php

class Identificadores {

    public static function string($string) {
        $testaString = preg_replace("/[a-zA-Z0-9]/", "", $string);
        if ($testaString != "") {
            return false;
        }
        return true;
    }
    
    public static function numeros($string) {
        if(preg_replace("/[0-9.]/", "", $string) != "") {
            return false;
        }
        return true;
    }

    public static function separadores($string) {
        $separadores = [',', '.', ';', ':', '/', '"\"', '#', '>', '<'];
        if (!in_array($string, $separadores)) {
            return false;
        }
        return true;
    }

}