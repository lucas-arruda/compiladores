<?php

class TipoVariaveis {

    public static function tipos($tipo) {
        $tipos = ['int', 'float'];
        if (!in_array($tipo, $tipos)) {
            return false;
        }
        return true;
    }

    public static function verificaTipoNumerico($tipo, $valor) {
        if ($tipo == "float") {
            $verificador = preg_replace("/[0-9]/", "", $valor);
            if ($verificador == ".") {
                return true;
            } else {
                return false;
            }
        } else {
            $verificador = preg_replace("/[0-9]/", "", $valor);
            if ($verificador == "") {
                return true;
            } else {
                return false;
            }
        }
    }
}