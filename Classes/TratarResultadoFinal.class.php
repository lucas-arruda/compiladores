<?php

class TratarResultadoFinal {

    public static function mostrarResultadoFinal($retornoEstrutura) {
        foreach ($retornoEstrutura['variaveis'] as $dados) {
            if (isset($dados['show']) && $dados['show']) {
                $mensagem[] = "O valor da variavel " . $dados['nome'] . " é: " . $dados['valor'] . "\n"; 
            }
        }
        return $mensagem;
    }
}