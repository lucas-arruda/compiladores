<?php

require_once 'Classes/LogErros.class.php';
require_once 'Classes/PalavrasReservadas.class.php';

class Estrutura {

    /**
     * Metodo para verificar se os identificadores show e safe estão escritos corretamente.
     * 
     * @param array $dados
     * @param array $variaveis
     * @return array
     * @throws Exception array
     */
    public function estrutura($dados = [], $variaveis = []) {
        try {
            $texto = "";
            $safe = 0;
            $safes = $shows = $linhas = [];
            foreach ($dados as $registro) {
                $paralavraReservada = preg_replace("/[^a-zA-Z]/", "", substr($registro, 0, 5));
                switch ($paralavraReservada) {
                    case "show":
                        if (!PalavrasReservadas::reservadas($paralavraReservada)) {
                            throw new Exception("Palavra " . $paralavraReservada . " não reconhecida.");
                        }
                        $texto = str_replace("show", "", str_replace("(", "", str_replace(")", "", str_replace(";", "", $registro))));
                        if (substr($registro, 4, 1) != "(") {
                            throw new Exception("Não encontrado ( após show.");
                        }
                        if (substr($registro, -1) != ";") {
                            throw new Exception("na linha do show não foi encontrado o ;");
                        }
                        if (substr($registro, -2) != ");") {
                            throw new Exception("na linha do show não foi encontrado o ) antes do ;");
                        }
                        $shows[] = $texto;
                        break;
                    case "safe":
                        if (!PalavrasReservadas::reservadas($paralavraReservada)) {
                            throw new Exception("Palavra " . $paralavraReservada . " não reconhecida.");
                        }
                        if (substr($registro, 4, 1) != "(") {
                            throw new Exception("Não encontrado ( após safe.");
                        }
                        if (substr($registro, -1) != ";") {
                            throw new Exception("na linha do safe não foi encontrado o ;");
                        }
                        if (substr($registro, -2) != ");") {
                            throw new Exception("na linha do safe não foi encontrado o ) antes do ;");
                        }
                        $variavel = str_replace("safe", "", str_replace("(", "", str_replace(")", "", str_replace(";", "", $registro))));
                        foreach ($variaveis as $valores) {
                            $variaveisDeclarados[] = $valores['nome'];
                        }
                        if (!in_array($variavel, $variaveisDeclarados)) {
                            throw new Exception("A variavel " . $variavel . " não foi declarada.");
                        }
                        unset($variaveisDeclarados);
                        $safes[] = $variavel;
                        $safe++;
                        break;
                    default:
                        if (substr($registro, -1) != ";") {
                            throw new Exception("na linha ". $registro . " não foi encontrado o ;");
                        }
                        $linhas[] = $registro;
                        break;
                }
                
            }
            if ($safe === 0) {
                throw new Exception("Nenhuma variavel foi lida.");
            }
            return [
                'erro' => false,
                'shows' => $shows,
                'safes' => $safes,
                'linhas' => $linhas
            ];
        } catch (Exception $e) {
            LogErros::log($e->getMessage());
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
    }
    
}