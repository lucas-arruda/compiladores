<?php

require_once 'Classes/TipoVariaveis.class.php';
require_once 'Classes/PalavrasReservadas.class.php';
require_once 'Classes/Identificadores.class.php';

class TratarVariaveis {

    /**
     * Metodo para verificar se as variaveis foram declaradas corretamente.
     * 
     * @param array $linhaVariavel
     * @return array
     * @throws Exception array
     */
    public function varDeclarada($linhaVariavel = []) {
        $nome = $valor = "";
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

                if (preg_replace("/[^=]/", "", $dados[1]) != "") {
                    $verificaVariavel = explode("=", $dados[1]);
                    if(!Identificadores::string($verificaVariavel[0])) {
                        throw new Exception("O nome da variavel " . $verificaVariavel[0] . " possuí caracteres especiais.");
                    }
                    if(!Identificadores::numeros($verificaVariavel[1])) {
                        throw new Exception("O valor da variavel " . $verificaVariavel[1] . " não possuí números.");
                    }
                    $nome = $verificaVariavel[0];
                    $valor = (int)$verificaVariavel[1];
                } else {
                    if(!Identificadores::string($dados[1])) {
                        throw new Exception("O nome da variavel " . $dados[1] . " não possuí letras.");
                    }
                    $nome = $dados[1];
                    $valor = 0;
                }
                $variaveis[] = [
                    'nome' => $nome,
                    'tipo' => $dados[0],
                    'valor' => $valor
                ];
            }
            return [
                'erro' => false,
                'variaveis' => $variaveis
            ];
        } catch (Exception $e) {
            return [
                'erro' => true,
                'mensagem'=> $e->getMessage()
            ];
        }
    }
}