<?php

if(!isset($_POST['string']) || $_POST['string'] == "") {
    echo json_encode([
        'erro' => true,
        'mensagem' => "Nenhum valor digitado"
    ]);
    exit;
}