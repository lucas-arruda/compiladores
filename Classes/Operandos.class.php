<?php

class Operandos {

    public static function operadores($operador) {
        $operandos = ['+', '-', '='];
        if (!in_array($operador, $operandos)) {
            return false;
        }
        return $operador;
    }
}