<?php

require_once 'Classes/LogErros.class.php';
require_once 'Classes/TipoVariaveis.class.php';
require_once 'Classes/PalavrasReservadas.class.php';
require_once 'Classes/Identificadores.class.php';

class TratarVariaveis {

    public function verificarVariavelLida($variaveis = [], $safes = []) {
        try {
            $variaveisLidas = $variaveisNaoLidas = [];
            foreach ($safes as $valor) {
                if (in_array($valor['nome'], $variaveis)) {
                    $variaveisLidas[] = $valor['nome'];
                }
            }
            foreach ($variaveis as $valor) {
                if (!in_array($valor['nome'], $variaveisLidas)) {
                    $variaveisNaoLidas[] = $valor['nome'];
                }
            }

            if (count($variaveisNaoLidas)) {
                $variaveis = implode(", ", $variaveisNaoLidas);
                throw new Exception("As variaveis " . $variaveis . " não foram lidas.");
            }
            return true;
        } catch (Exception $e) {
            LogErros::log($e->getMessage());
            return [
                'error' => true,
                'mensagem' => $e->getMenssage()
            ]; 
        }
    }

    /**
     * Metodo para verificar se as variaveis foram declaradas corretamente.
     * 
     * @param array $linhaVariavel
     * @return array
     * @throws Exception array
     */
    public function varDeclarada($linhaVariavel = []) {
        try {
            if(!PalavrasReservadas::reservadas(strtolower($linhaVariavel[0]))) {
                throw new Exception("Palavra chave var ausente ou não encontrada.");
            }
            array_shift($linhaVariavel);
            $linhaVariavel = str_replace(" ", "", implode(" ", $linhaVariavel));
            if ($linhaVariavel == "") {
                throw new Exception("Nenhuma variável declarada.");
            }
            $tratarLinha = explode(",", $linhaVariavel);
            $finalLinha = array_pop($tratarLinha);
            if (substr($finalLinha, -1) != ";") {
                throw new Exception("Erro no final da linha. Esperado ;");
            }
            array_push($tratarLinha, str_replace(";", "", $finalLinha));
            foreach ($tratarLinha as $variavel) {
                if(preg_replace("/[^:]/", "", $variavel) == "") {
                    throw new Exception("identificador : não encontrado o tipo da variável na linha que contém: " . $variavel);
                }
                $dados = explode(":", $variavel);
                if (!TipoVariaveis::tipos($dados[0])) {
                    throw new Exception("Tipo da variavel " . $dados[0]. "não encontrado.");
                }
                if (count($dados) == 1) {
                    throw new Exception("erro encontrado na linha " . $dados[0]);
                }
                if (preg_replace("^=", "", $dados[1]) != "") {
                    $verificaVariavel = explode("=", $dados[1]);
                    if(!Identificadores::string($verificaVariavel[0])) {
                        throw new Exception("O nome da variavel " . $verificaVariavel[0] . " não possuí letras.");
                    }
                    if(!Identificadores::numeros($verificaVariavel[1])) {
                        throw new Exception("O valor da variavel " . $verificaVariavel[1] . " não possuí números.");
                    }
                    $nome = $verificaVariavel[0];
                    $valor = $verificaVariavel[1];
                }
                $variaveis[] = [
                    'nome' => isset($nome) ? $nome : $dados[1],
                    'tipo' => $dados[0],
                    'valor' => isset($valor) ? $valor : "",
                ];
            }
            return [
                'erro' => false,
                'variaveis' => $variaveis
            ];
        } catch (Exception $e) {
            LogErros::log($e->getMessage());
            return [
                'erro' => true,
                'mensagem'=> $e->getMessage()
            ];
        }
    }
}