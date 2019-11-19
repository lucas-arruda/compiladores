<?php

require_once 'Classes/Identificadores.class.php';
require_once 'Classes/PalavrasReservadas.class.php';
require_once 'Classes/TipoVariaveis.class.php';
require_once 'Classes/Operandos.class.php';

class AutomatoShow {
    private $entrada = [];
    private $tabelaSimbolos = [];
    private $simbolo;
    private $estadoAtual;
    private $terminou;
    private $erro;
    private $posicao = 0;

    public function estado0() {
        $this->cadeia = "";
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "s") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 1;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }
    public function estado1() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "h") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 2;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }
    public function estado2() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "o") {
            $this->cadeia .= $this->simbolo;
            $this->estadoAtual = 3;
        } else {
            $this->terminou = true;
            $this->erro = true;
        } 
    }
    public function estado3() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "w") {
            $this->cadeia .= $this->simbolo;
            $this->tabelaSimbolos($this->cadeia, "Identificador", "Palavra reservada");
            $this->cadeia = "";
            $this->estadoAtual = 4;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
        
    }
    public function estado4() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == "(") {
            $this->tabelaSimbolos($this->simbolo, "Separador", "Simbolo para separação");
            $this->estadoAtual = 5;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado5() {
        $this->simbolo = $this->entrada[$this->cont++];
        if (Identificadores::string($this->simbolo) && !Identificadores::numeros($this->simbolo)) {
            $this->estadoAtual = 6;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado6() {
        $this->simbolo = $this->entrada[$this->cont++];
        while (Identificadores::string($this->simbolo)) {
            $this->simbolo = $this->entrada[$this->cont++];
        }
        if ($this->simbolo == ")") {
            $this->tabelaSimbolos($this->simbolo, "Separador", "Simbolo para separação");
            $this->estadoAtual = 7;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }
    public function estado7() {
        $this->simbolo = $this->entrada[$this->cont++];
        if ($this->simbolo == ";") {
            $this->tabelaSimbolos($this->simbolo, "Separador", "Simbolo para separação");
            $this->estadoAtual = 8;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function estado8() {
        $this->terminou = true;
    }

    

    public function validarAutomato($linha) {
        $this->entrada = [];
        $tamanhoString = strlen($linha);
        $linha = str_replace(" ", "", $linha);
        for ($i = 0; $i < $tamanhoString; $i++) {
            $this->entrada[$i] = substr($linha, $i, 1);
        }
        $this->simbolo = $this->cadeia = "";
        $this->terminou = $this->erro = false;
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
                case 8:
                    $this->estado8();
                    break;
            }
        }
        if ($this->erro) {
            return false;
        }
        return $this->tabelaSimbolos;
    }

    public function tabelaSimbolos($cadeia, $token, $categoria = "", $tipo = "") {
        $this->tabelaSimbolos[$this->posicao] = [
            'cadeia' => $cadeia,
            'token' => $token,
            'categoria' => $categoria,
            'tipo' => $tipo
        ];
        $this->posicao++;
    }

}