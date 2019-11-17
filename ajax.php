<?php

require_once 'Classes/Compilador.class.php';
require_once 'Classes/AnalisadorLexico.class.php';

$compilador = new Compilador();
$analisador = new AnalisadorLexico();
sleep(1);
extract($_POST);

switch ($acao) {
    case "compilar":
        $retornoDados = $compilador->getDados($codigo_fonte);
        echo json_encode($retornoDados);
        break;
    case "analisador_lexico":
        $retornoAnalisador = $analisador->analisador($codigo_fonte);
        echo json_encode($retornoAnalisador);
        break;
}




