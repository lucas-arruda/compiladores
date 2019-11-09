<?php

require_once 'Classes/Compilador.class.php';

$compilador = new Compilador();

if(!isset($_POST['codigo_fonte']) || $_POST['codigo_fonte'] == "") {
    echo json_encode([
        'erro' => true,
        'mensagem' => "Nenhum dado encontrado"
    ]);
    exit;
}

$retornoDados = $compilador->getDados($_POST['codigo_fonte']);




echo json_encode($retornoDados);



?>