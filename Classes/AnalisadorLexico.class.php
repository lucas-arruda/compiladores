<?php

require_once 'Classes/Identificadores.class.php';
require_once 'Classes/PalavrasReservadas.class.php';
require_once 'Classes/TipoVariaveis.class.php';
require_once 'Classes/Operandos.class.php';

class AnalisadorLexico {
    private $entrada = [];
    private $tabelaSimbolos = [];
    private $simbolo;
    private $estadoAtual;
    private $tipo = "";
    private $token;
    private $cadeia;
    private $categoria = "";
    private $terminou;
    private $erro;
    private $posicaoTabela = 0;
    private $valor = "";

    public function estado0() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "<" || $this->simbolo == "#") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 1;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado1() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "#" || $this->simbolo == ">") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 2;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado2() {
        if (isset($this->entrada[$this->cont + 1])) {
            $this->simbolo = $this->entrada[$this->cont++];
            if ($this->simbolo == "s") {
                $this->cadeia .= $this->simbolo;
                $this->estadoAtual = 3;
            } else {
                $this->terminou = true;
                $this->erro = true;
            }
        } else {
            if (PalavrasReservadas::reservadas($this->cadeia)) {
                $this->token = "Identificador";
                $this->categoria = "Palavra reservada";
                $this->terminou = true;
            } else {
                $this->terminou = true;
                $this->erro = true;
            }
        }
        
        
    }

    public function estado3() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "t") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 4;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado4() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "a") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 5;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado5() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "r") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 6;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado6() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "t") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 7;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado7() {
        if (PalavrasReservadas::reservadas($this->cadeia)) {
            $this->token = "Identificador";
            $this->categoria = "Palavra reservada";
            $this->terminou = true;
        }
    }




    public function analisador($dados) {
        try {
            $codigoFonte = array_filter(explode("\n", $dados));
            foreach ($codigoFonte as $linha) {
                $tamanhoString = strlen($linha);
                for ($i = 0; $i < $tamanhoString; $i++) {
                    $this->entrada[$i] = substr($linha, $i, 1);
                }
                $this->simbolo = "";
                $this->terminou = false;
                $this->estadoAtual = $this->cont = 0;
                while(!$this->terminou) {
                    switch ($this->estadoAtual) {
                        case 0:
                            $this->estado0();
                            break;
                        case 1:
                            $this->estado1();
                            break;
                        case 2:
                            $this->estado2();
                            break;
                        case 3:
                            $this->estado3();
                            break;
                        case 4:
                            $this->estado4();
                            break;
                        case 5:
                            $this->estado5();
                            break;
                        case 6:
                            $this->estado6();
                            break;
                        case 7:
                            $this->estado7();
                            break;
                    }
                }
                if ($this->erro) {
                    $this->tabelaSimbolos($this->cadeia, "Token não reconhecido");
                } else {
                    $this->tabelaSimbolos($this->cadeia, $this->token, $this->categoria, $this->tipo);
                }
            }
            return [
                'erro' => false,
                'tabela_simbolos' => $this->tabelaSimbolos
            ];
        } catch (Exception $e) {
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
    }
    public function tabelaSimbolos($cadeia, $token, $categoria = "", $tipo = "") {
        if (!in_array($cadeia, $this->tabelaSimbolos) && !in_array($token, $this->tabelaSimbolos) && !in_array($categoria, $this->tabelaSimbolos) && !in_array($tipo, $this->tabelaSimbolos)) {
            $this->tabelaSimbolos[$this->posicaoTabela] = [
                'cadeia' => $cadeia,
                'token' => $token,
                'categoria' => $categoria,
                'tipo' => $tipo
            ];
            $this->posicaoTabela++;
        }
        
    }
}