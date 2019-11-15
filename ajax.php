<?php

require_once 'Classes/Compilador.class.php';
require_once 'Classes/LogErros.class.php';

$compilador = new Compilador();
sleep(1);

if(!isset($_POST['codigo_fonte']) || $_POST['codigo_fonte'] == "") {
    LogErros::log("Nenhum dado encontrado");
    echo json_encode([
        'erro' => true,
        'mensagem' => "Nenhum dado encontrado"
    ]);
    exit;
}

sleep(1);

$retornoDados = $compilador->getDados($_POST['codigo_fonte']);

echo json_encode($retornoDados);