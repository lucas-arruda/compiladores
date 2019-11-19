<?php

require_once 'Classes/AutomatoInicio.class.php';
require_once 'Classes/AutomatoFinal.class.php';
require_once 'Classes/AutomatoVar.class.php';
require_once 'Classes/AutomatoVariaveisFloat.class.php';
require_once 'Classes/AutomatoVariaveisInt.class.php';
require_once 'Classes/AutomatoSafe.class.php';
require_once 'Classes/AutomatoShow.class.php';
require_once 'Classes/AutomatoCondicoes.class.php';

class AnalisadorLexico {
    private $tabelaSimbolos = [];
    private $cont = 0;

    public function analisador($dados) {
        $automatoInicio = new AutomatoInicio();
        $automatoFim = new AutomatoFinal();
        $automatoVar = new AutomatoVar();
        $automatoVariaveisFloat = new AutomatoVariaveisFloat();
        $automatoVariaveisInt = new AutomatoVariaveisInt();
        $automatoSafe = new AutomatoSafe();
        $automatoShow = new AutomatoShow();
        $automatoCondicoes = new AutomatoCondicoes();
        $codigoFonte = array_filter(explode("\n", $dados));
        foreach ($codigoFonte as $linha) {
            $simboloInicial = $simboloFinal = $simboloVar = $simboloVariaveis = $simboloSafe = $simboloShow = $simboloCondicoes = "";
            if (substr($linha, 0, 3) == "var") {
                $simboloVar = $automatoVar->validarAutomato(substr($linha, 0, 3));
                $tabelaSimbolos = $this->tabelaSimbolos($simboloVar);
                $linha = str_replace("var", "", ltrim($linha));
                unset($simboloVar);
            }
            if ($simboloInicial = $automatoInicio->validarAutomato($linha)) {
                $tabelaSimbolos = $this->tabelaSimbolos($simboloInicial);
            }
            unset($simboloInicial);
            if ($simboloFinal = $automatoFim->validarAutomato($linha)) {
                $tabelaSimbolos = $this->tabelaSimbolos($simboloFinal);
            }
            unset($simboloFinal);
            if ($simboloVariaveis = $automatoVariaveisFloat->validarAutomato($linha)) {
                $tabelaSimbolos = $this->tabelaSimbolos($simboloVariaveis);
            }
            unset($simboloVariaveis);
            if ($simboloVariaveis = $automatoVariaveisInt->validarAutomato($linha)) {
                $tabelaSimbolos = $this->tabelaSimbolos($simboloVariaveis);
            }
            unset($simboloVariaveis);
            if ($simboloSafe = $automatoSafe->validarAutomato($linha)) {
                $tabelaSimbolos = $this->tabelaSimbolos($simboloSafe);
            }
            unset($simboloSafe);
            if ($simboloShow = $automatoShow->validarAutomato($linha)) {
                $tabelaSimbolos = $this->tabelaSimbolos($simboloShow);
            }
            unset($simboloShow);
            if ($simboloCondicoes = $automatoCondicoes->validarAutomato($linha)) {
                $tabelaSimbolos = $this->tabelaSimbolos($simboloCondicoes);
            }
            unset($simboloShow);
        }
        $tabelaSimbolos = array_map("unserialize", array_unique(array_map("serialize", $tabelaSimbolos)));
        return [
            'erro' => false,
            'tabela_simbolos' => $tabelaSimbolos
        ];
    }

    public function tabelaSimbolos($dadosSimbolos = []) {
        for ($i = 0; $i < count($dadosSimbolos); $i++) {
            $this->tabelaSimbolos[$this->cont] = [
                'cadeia' => $dadosSimbolos[$i]['cadeia'],
                'token' => $dadosSimbolos[$i]['token'],
                'categoria' => $dadosSimbolos[$i]['categoria'],
                'tipo' => $dadosSimbolos[$i]['tipo']
            ];
            $this->cont++;
        }
        return $this->tabelaSimbolos;
    }
}