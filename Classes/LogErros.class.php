<?php

class LogErros {

    public static function log($msgErro) {
        file_put_contents("log/log_erros.txt", "[" . date("d/m/Y H:i:s") . "] - $msgErro " . PHP_EOL, FILE_APPEND);
    }
}