<?php

require_once 'Classes/PalavrasReservadas.class.php';
require_once 'Classes/Operandos.class.php';
require_once 'Classes/Identificadores.class.php';

class Estrutura {

    /**
     * Metodo que monta a estrutura do compilador e verifica se possui erros, se não, o codigo pode ser compilado
     * 
     * @param array $dados
     * @param array $variaveis
     * @return array
     * @throws Exception array
     */
    public function estrutura($dados = [], $variaveis = []) {
        try {
            $linhas = $valorStrings = [];
            $safe = false;
            foreach ($dados as $registro) {
                $operador = "";
                $espacosBrancos = preg_replace("/[^[:space:]]/", "", $registro);
                if ($espacosBrancos == "") {
                    if(!PalavrasReservadas::reservadas(substr($registro, 0, 4))) {
                        throw new Exception("Erro encontrado na linha " . $registro);
                    }
                }
                if (substr($registro, -1) != ";") {
                    throw new Exception("Erro encontrado o ; na linha " . $registro);
                }

                $linha = explode(" ", str_replace(";", "", $registro));
                if (count($linha) == 1) {
                    $palavraReservada = substr($linha[0], 0, 4);
                    if (PalavrasReservadas::reservadas($palavraReservada)) {
                        if (substr($registro, 4, 1) != "(") {
                            throw new Exception("Não encontrado ( após " . $palavraReservada . ".");
                        }
                        if (substr($registro, -1) != ";") {
                            throw new Exception("na linha do ". $palavraReservada . " não foi encontrado o ;");
                        }
                        if (substr($registro, -2) != ");") {
                            throw new Exception("na linha do " . $palavraReservada . " não foi encontrado o ) antes do ;");
                        }
                        
                        if ($palavraReservada == "show") {
                            $cont = 0;
                            $string = str_replace("show", "", str_replace("(", "", str_replace(")", "", str_replace(";", "", $registro))));
                            for ($i = 0;$i < count($variaveis);$i++) {
                                if ($string == $variaveis[$i]['nome']) {
                                    if (!isset($variaveis[$i]['safe'])) {
                                        throw new Exception("variavel " . $string . "  não pode ser mostrada, não foi armazenada na memória.");
                                    }
                                    $variaveis[$i]['show'] = true;
                                    $cont++;
                                }
                            }
                            if ($cont === 0) {
                                throw new Exception("variavel " . $string . "  não pode ser mostrada, não foi armazenada como variavel.");
                            }
                        } else {
                            $string = str_replace("safe", "", str_replace("(", "", str_replace(")", "", str_replace(";", "", $registro))));
                            for ($i = 0;$i < count($variaveis);$i++) {
                                if ($string == $variaveis[$i]['nome']) {
                                    $variaveis[$i]['safe'] = true;
                                } 
                            }
                        }
                    } else {
                        throw new Exception("Palavra reservada " . $palavraReservada . " não reconhecida.");
                    }
                }
                if (count($linha) == 3) {
                    foreach ($linha as $string) {
                        if (Identificadores::string($string) && !Identificadores::numeros($string)) {
                            continue;
                        } else if (Identificadores::numeros($string)) {
                            for ($i = 0;$i < count($variaveis);$i++) {
                                if ($operador == "=") {
                                    if ($linha[0] == $variaveis[$i]['nome']) {
                                        if(!TipoVariaveis::verificaTipoNumerico($variaveis[$i]['tipo'], $string)) {
                                            throw new Exception("Valor digitado para variavel ". $variaveis[$i]['nome'] . " está incorreto");
                                        }
                                        $variaveis[$i]['valor'] = $variaveis[$i]['tipo'] == "float" ? (float)$string : (int)$string;
                                        break;
                                    }
                                }
                            }
                        } else if (Operandos::operadores($string)) {
                            $operador = $string;
                        } else {
                            throw new Exception("Palavra " . $string . " não reconhecida.");
                        }
                    }
                }
                if (count($linha) > 3) {
                    foreach ($linha as $string) {
                        if ($string == "") {
                            throw new Exception("A linha " . implode(" ", $linha) . " contém erro.");
                        }
                        if (Identificadores::string($string) && !Identificadores::numeros($string)) {
                            
                            if ($operador != "" && ($operador == "=" ||$operador == "+")) {
                                $tipo = $existeVariavel = false;
                                for ($i = 0;$i < count($variaveis);$i++) {
                                    if ($string == $variaveis[$i]['nome']) {
                                        if (!isset($variaveis[$i]['safe'])) {
                                            throw new Exception("A variável " . $string . " não foi armazenada na memória.");
                                        }
                                        $valor = $variaveis[$i]['valor'];
                                        $tipo = $variaveis[$i]['tipo'];
                                        $existeVariavel = true;
                                        break;
                                    }
                                }
                                if (!$existeVariavel) {
                                    throw new Exception("Variavel " . $string . " não reconhecida.");
                                }
                                for ($i = 0;$i < count($variaveis);$i++) {
                                    if ($linha[0] == $variaveis[$i]['nome'] &&  $tipo == $variaveis[$i]['tipo']) {
                                        $variaveis[$i]['valor'] += $valor;
                                        $tipo = true;
                                        break;
                                    } 
                                }
                                if (!isset($tipo)) {
                                    throw new Exception("Não foi possível calcular os valores. Os tipos das variaveis ". $linha[0] . " e da " . $string . " são diferentes.");
                                }
                            }
                            if ($operador != "" && $operador == "-") {
                                $cont = 0;
                                for ($i = 0;$i < count($variaveis);$i++) {
                                    if ($string == $variaveis[$i]['nome']) {
                                        if (!isset($variaveis[$i]['safe'])) {
                                            throw ("A variável " . $string . " não foi armazenada na memória.");
                                        }
                                        $valor = $variaveis[$i]['valor'];
                                        $tipo = $variaveis[$i]['tipo'];
                                        break;
                                    }
                                }
                                for ($i = 0;$i < count($variaveis);$i++) {
                                    if ($linha[0] == $variaveis[$i]['nome'] && $tipo == $variaveis[$i]['tipo']) {
                                        $variaveis[$i]['valor'] = $variaveis[$i]['valor'] - $valor;
                                        $cont++;
                                        break;
                                    }
                                }
                                if ($cont === 0) {
                                    throw new Exception("Não foi possível calcular os valores. Os tipos das variaveis ". $linha[0] . " e da " . $string . " são diferentes.");
                                }
                            }

                        } else if (Identificadores::numeros($string)) {
                            if ($operador != "" && ($operador == "=" ||$operador == "+")) {
                                $cont = 0;
                                for ($i = 0;$i < count($variaveis);$i++) {
                                    if(!TipoVariaveis::verificaTipoNumerico($variaveis[$i]['tipo'], $string)) {
                                        throw new Exception("Valor digitado para variavel ". $variaveis[$i]['nome']  . " está incorreto");
                                    }
                                    if ($linha[0] == $variaveis[$i]['nome']) {
                                        $variaveis[$i]['valor'] += $valor;
                                        $cont++;
                                        break;
                                    } 
                                }
                                if ($cont === 0) {
                                    throw new Exception("Não foi possível calcular os valores. Os tipos das variaveis ". $primeiraString . " e da " . $string . " são diferentes.");
                                }
                            }
                            if ($operador != "" && $operador == "-") {
                                $cont = 0;
                                for ($i = 0;$i < count($variaveis);$i++) {
                                    if ($linha[0] == $variaveis[$i]['nome'] && $tipo == $variaveis[$i]['tipo']) {
                                        if(!TipoVariaveis::verificaTipoNumerico($variaveis[$i]['tipo'], $string)) {
                                            throw new Exception("Valor digitado para variavel " . $variaveis[$i]['nome'] . " está incorreto");
                                        }
                                        $variaveis[$i]['valor'] = $variaveis[$i]['valor'] - $valor;
                                        $cont++;
                                        break;
                                    }
                                }
                                if ($cont === 0) {
                                    throw new Exception("Não foi possível calcular os valores. Os tipos das variaveis ". $linha[0] . " e da " . $string . " são diferentes.");
                                }
                            }
                        } else if (Operandos::operadores($string)) {
                            $operador = $string;
                        }
                    }
                }
            }
            return [
                'erro' => false,
                'variaveis' => $variaveis
            ];
        } catch (Exception $e) {
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
    }
    
}