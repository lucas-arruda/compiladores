<?php

require_once 'Classes/LogErros.class.php';
require_once 'Classes/Operandos.class.php';

class Calcular {


    public function calcularValores($dados = [], $variaveis = []) {
        try {
            foreach ($dados as $linha) {
                $dadosLinha = explode(" ", $linha);
                if (count($dadosLinha) == 1) {
                    throw new Exception("Erro encontrado na linha " . $linha . ". Não foi reconhecida.");
                }
                if (count($dadosLinha) == 3) {
                    $variavel = array_shift($dadosLinha);
                    $valor = array_pop($dadosLinha);
                    $operando = $dadosLinha[0];
                } else {

                }
                
                if (!Operandos::operadores($operando)) {
                    throw new Exception("Operando " . $operando .  " não encontrado.");
                }
                if ($operando == "=") {
                    $operandoCondicao = preg_replace("/[0-9a-zA-Z;]/", "", $valor);
                    if ($operandoCondicao != null) {
                        if (!Operandos::operadores($operandoCondicao)) {
                            throw new Exception("Operando " . $operandoCondicao .  " não encontrado.");
                        }
                        switch ($operandoCondicao) {
                            case "+":
                                $valores = explode(" ", str_replace("+", " ", $valor));
                                $cont = 0;
                                if ($valores[$cont] == $variaveis[$i]['nome']) {
                                    $variavel += isset($variaveis[$i]['valor']) ? $variaveis[$i]['valor'] : 0;
                                    $cont++;
                                }
                                break;
                            case "-":
                                $valores = explode(" ", str_replace("-", " ", $valor));
                                $cont = 0;
                                if ($valores[$cont] == $variaveis[$i]['nome']) {
                                    if ($variaveis[$i]['valor'] == "") {
                                        throw new Exception("Valor da variavel " . $variaveis[$i]['nome'] . " não foi atribuído.");
                                    }
                                    $variavel -= $variaveis[$i]['valor'];
                                    $cont++;
                                }
                                break;
                        }
                        for ($i = 0; $i < count($variaveis); $i++) {
                            if ($variavel == $variaveis[$i]['nome']) {
                                $variaveis[$i]['valor'] = $resultado;
                            }
                        }
                    } else {
                        for ($i = 0; $i < count($variaveis); $i++) {
                            if ($variavel == $variaveis[$i]['nome']) {
                                $variaveis[$i]['valor'] = preg_replace("/[^0-9]/", "", $valor);
                            }
                        }
                    } 
                }
            }
            return $variaveis;
        } catch (Exception $e) {
            LogErros::log($e->getMessage());
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
    }
}