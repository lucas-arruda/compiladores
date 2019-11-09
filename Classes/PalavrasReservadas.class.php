<?php

class PalavrasReservadas {

    public static function reservadas($palavra) {
        $reservadas = ['safe', 'show', '<#start', '#>', 'var'];
        if (!in_array($palavra, $reservadas)) {
            return false;
        }
        return true;
    }
}