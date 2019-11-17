<?php

require_once 'Classes/Identificadores.class.php';
require_once 'Classes/PalavrasReservadas.class.php';
require_once 'Classes/TipoVariaveis.class.php';
require_once 'Classes/Operandos.class.php';

class AnalisadorLexico {
    private $estados = [];
    private $simbolo;
    private $estadoAtual;
    private $terminou;
    private $erro;
    private $cont = 0;

    public function estado0 ($character) {
        if (Identificadores::string($caracter)) {
            switch ($character) {
                case "v":
                $this->$simbolo = $this->estados[10];
                $this->estadoAtual = 10;
                break;
            }
            
        } else if (Identificadores::numeros($character)) {
            $this->$simbolo = $this->estados[$this->cont++];
            $this->estadoAtual = 1;
        } else if (Operandos::operadores($character)) {
            switch ($character) {
                case "=":
                    $this->$simbolo = $this->estados[$this->cont++];
                    $this->estadoAtual = 1;
                    break;
                case "-":
                    $this->$simbolo = $this->estados[$this->cont++];
                    $this->estadoAtual = 1;
                    break;
                case "+":
                    $this->$simbolo = $this->estados[$this->cont++];
                    $this->estadoAtual = 1;
                    break;
                case ">":
                    $this->$simbolo = $this->estados[$this->cont++];
                    $this->estadoAtual = 1;
                    break;
                case "<":
                    $this->$simbolo = $this->estados[$this->cont++];
                    $this->estadoAtual = 1;
                    break;
            }
            
        } else if (Identificadores::separadores($character)) {
            $this->$simbolo = $this->estados[$this->cont++];
            $this->estadoAtual = 1;
        }
        else {
            $this->terminou = true;
            $this->erro = true;
        }
    }

    public function analisador($dados) {
        try {
            $codigoFonte = array_filter(explode("\n", $dados));
            foreach ($codigoFonte as $linha) {
                $tamanhoString = strlen($linha);
                for ($i = 0; $i <= $tamanhoString; $i++) {
                    $valores[$i] = substr($linha, $i, 1);
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
        } catch (Exception $th) {
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
    }
}