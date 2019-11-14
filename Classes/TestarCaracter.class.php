<?php

require_once 'Classes/Identificadores.class.php';
require_once 'Classes/PalavrasReservadas.class.php';
require_once 'Classes/TiposVariaveis.class.php';
require_once 'Classes/Operandos.class.php';

class TestarCaracter {
    private $estados = [];
    private $simbolo;
    private $estadoAtual;
    private $terminou;
    private $erro;
    private $cont = 0;

    public function estado0 ($caracter) {
        if (Identificadores::string($valores[$this->cont])) {
            $this->$simbolo = $this->estados[$this->cont++];
            $this->estadoAtual = 1;
        } else if (Identificadores::numeros($caracter)) {
            $this->$simbolo = $this->estados[$this->cont++];
            $this->estadoAtual = 1;
        } else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function testar($string) {
        $tamanhoString = strlen($string);
        for ($i = 0; $i <= $tamanhoString; $i++) {
            $valores[$i] = substr($string, $i, 1); 
        }

        $this->estadoAtual = 0;
        $this->estados[] = 0;
        while(!$this->terminou) {
            switch ($this->estadoAtual) {
                case 0:
                    $this->estado0($valores[$this->cont]);
            }
        }
                

    }
}