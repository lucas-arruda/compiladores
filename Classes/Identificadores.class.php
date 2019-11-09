<?php

class Identificadores {

    public static function string($string) {
        if(preg_replace("/[^a-zA-z]/", "", $string) == "") {
            return false;
        }
        return true;
    }
    
    public static function numeros($string) {
        if(preg_replace("/[^0-9]/", "", $string) == "") {
            return false;
        }
        return true;
    }


}