<?php

class TipoVariaveis {

    public static function tipos($tipo) {
        $tipos = ['int', 'float'];
        if (!in_array($tipo, $tipos)) {
            return false;
        }
        return true;
    }
}