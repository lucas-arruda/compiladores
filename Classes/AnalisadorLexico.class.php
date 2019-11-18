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
    private $tipo;
    private $token;
    private $terminou;
    private $erro;
    private $cont = 0;
    private $posicaoTabela = 0;
    private $valor = "";

    public function estado0 () {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token = "";
        if (Identificadores::string($this->simbolo) && !Identificadores::numeros($this->simbolo)) {
            switch ($this->simbolo) {
                case "v":
                    $this->token .= $this->simbolo;
                    $this->estadoAtual = 10;
                    break;
                case "f":
                    $this->token .= $this->simbolo;
                    $this->estadoAtual = 14;
                    break;
                case "i":
                    $this->token .= $this->simbolo;
                    $this->estadoAtual = 19;
                    break;
                case "s":
                    $this->token .= $this->simbolo;
                    $this->estadoAtual = 22;
                    break;
                case ($this->simbolo != "s" || $this->simbolo != "v" || $this->simbolo != "i" || $this->simbolo != "f"):
                    $this->token .= $this->simbolo;
                    $this->estadoAtual = 33;
                    break;
                default:
                    $this->token .= $this->simbolo;
                    $this->estadoAtual = 34;
            }
            
        } else if (Identificadores::numeros($this->simbolo)) {
            $this->estadoAtual = 30;
        } else if (Operandos::operadores($this->simbolo)) {
            if($this->simbolo == "<") {
                $this->estadoAtual = 3;
            }
        } else if (Identificadores::separadores($this->simbolo)) {
            switch ($this->$simbolo) {
                case "#":
                    $this->estadoAtual = 1;
                    break;
                case ";":
                case ":";
                case ",";
                    $this->tabelaSimbolos($this->tabelaSimbolos, "Separador");
                    $this->estadoAtual = 32;
                    break;
            }
        }
        else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado1() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::separadores($this->simbolo) && $this->simbolo == ">") {
            $this->tipo = "palavra reservada";
            $this->valor =  "";
            $this->estadoAtual = 2;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado2Final() {
        $this->tipo = "Palavra reservada";
        $this->terminou = true;
    }

    public function estado3() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::separadores($this->simbolo) && $this->simbolo == "#") {
            $this->estadoAtual = 4;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado4() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "s") {
            $this->estadoAtual = 5;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado5() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "t") {
            $this->estadoAtual = 6;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado6() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "a") {
            $this->estadoAtual = 7;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado7() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "r") {
            $this->estadoAtual = 8;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado8() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "t") {
            $this->estadoAtual = 9;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado9Final() {
        $this->tipo = "Palavra reservada";
        $this->terminou = true;
    }

    public function estado10() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "a") {
            $this->estadoAtual = 11;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado11() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "r") {
            $this->estadoAtual = 12;
        } else if ($this->simbolo != "r") {
            $this->estadoAtual = 13;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado12Final() {
        if (isset($this->entrada[$this->cont++])) {
            $this->estadoAtual = 13;
        } else {
            $this->tipo = "Palavra reservada";
            $this->terminou = true;
        }
    }

    public function estado13Final() {
        $this->simbolo = $this->entrada[$this->cont++];
        if (Identificadores::string($this->simbolo)) {
            while (Identificadores::string($this->simbolo)) {
                $this->token .= $this->simbolo;
            }
            $this->tipo = "Identificador";
            $this->terminou = true;
        } else {
            $this->erro = true;
            $this->terminou = true;
        }
    }

    public function estado14() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "l") {
            $this->estadoAtual = 15;
        } else if ($this->simbolo != "l") {
            $this->estadoAtual = 13;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado15() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "o") {
            $this->estadoAtual = 16;
        } else if ($this->simbolo != "o") {
            $this->estadoAtual = 13;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado16() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "a") {
            $this->estadoAtual = 17;
        } else if ($this->simbolo != "a") {
            $this->estadoAtual = 13;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado17() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "t") {
            $this->estadoAtual = 18;
        } else if ($this->simbolo != "t") {
            $this->estadoAtual = 13;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado18Final() {
        if (isset($this->entrada[$this->cont++])) {
            $this->tabelaSimbolos($this->token, "Palavra reservada");
            $this->estadoAtual = 0;
        } else {
            $this->tabelaSimbolos($this->token, "Palavra reservada");
            $this->terminou = true;
        }
    }

    public function estado19() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "n") {
            $this->estadoAtual = 20;
        } else if ($this->simbolo != "n") {
            $this->estadoAtual = 13;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado20() {
        $this->simbolo = $this->entrada[$this->cont++];
        $this->token .= $this->simbolo;
        if (Identificadores::string($this->simbolo) && $this->simbolo == "t") {
            $this->estadoAtual = 21;
        } else if ($this->simbolo != "t") {
            $this->estadoAtual = 13;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado21Final() {
        if (isset($this->entrada[$this->cont++])) {
            $this->tabelaSimbolos($this->token, "Palavra reservada");
            $this->estadoAtual = 0;
        } else {
            $this->tabelaSimbolos($this->token, "Palavra reservada");
            $this->terminou = true;
        }
    }

    public function estado32Final() {
        if (isset($this->entrada[$this->cont++])) {
            $this->estadoAtual = 0;
        } else {
            $this->terminou = true;
        }
    }

    public function estado34Final() {
        $this->simbolo = $this->entrada[$this->cont++];
        if (Identificadores::string($this->simbolo)) {
            while (Identificadores::string($this->simbolo)) {
                $this->token .= $this->simbolo;
            }
            $this->tipo = "Identificador";
            $this->terminou = true;
        } else {
            $this->erro = true;
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
                $this->estadoAtual = 0;
                while(!$this->terminou) {
                    switch ($this->estadoAtual) {
                        case 0:
                            $this->estado0();
                            break;
                        case 1:
                            $this->estado1();
                            break;
                        case 2:
                            $this->estado2Final();
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
                        case 8:
                            $this->estado8();
                            break;
                        case 9:
                            $this->estado9Final();
                            break;
                        case 10:
                            $this->estado10();
                            break;
                        case 11:
                            $this->estado11();
                            break;
                        case 12:
                            $this->estado12Final();
                            break;
                        case 13:
                            $this->estado13Final();
                            break;
                        case 14:
                            $this->estado14();
                            break;
                        case 15:
                            $this->estado15();
                            break;
                        case 16:
                            $this->estado16();
                            break;
                        case 17:
                            $this->estado17();
                            break;
                        case 18:
                            $this->estado18Final();
                            break;
                        case 19:
                            $this->estado19();
                            break;
                        case 20:
                            $this->estado20();
                            break;
                        case 21:
                            $this->estado21Final();
                            break;
                        case 32:
                            $this->estado32Final();
                            break;
                        case 34:
                            $this->estado34Final();
                            break;
                    }
                }
                if ($this->erro) {
                    $this->tabelaSimbolos($this->token, " Token não reconhecido", $this->valor);
                } else {
                    $this->tabelaSimbolos($this->token, $this->tipo, $this->valor);
                }
            }
            return [
                'erro' => false,
                'tabela_simbolos' => $this->tabelaSimbolos
            ];
        } catch (Exception $th) {
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
    }

    public function tabelaSimbolos($token, $tipo, $valor = "") {
        $this->tabelaSimbolos[$this->posicaoTabela] = [
            'token' => $token,
            'tipo' => $tipo,
            'valor' => $valor,
        ];
        $this->posicaoTabela++;
    }
}