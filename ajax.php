<?php

require_once 'Classes/Compilador.class.php';
require_once 'Classes/LogErros.class.php';

$compilador = new Compilador();

if(!isset($_POST['codigo_fonte']) || $_POST['codigo_fonte'] == "") {
    LogErros::log("Nenhum dado encontrado");
    echo json_encode([
        'erro' => true,
        'mensagem' => "Nenhum dado encontrado"
    ]);
    exit;
}

$retornoDados = $compilador->getDados($_POST['codigo_fonte']);

echo json_encode($retornoDados);