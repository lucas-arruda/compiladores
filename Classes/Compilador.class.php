<?php

require_once 'Classes/TratarVariaveis.class.php';
require_once 'Classes/Estrutura.class.php';
require_once 'Classes/VerificaInicioFim.class.php';
require_once 'Classes/Calcular.class.php';

class Compilador {
    protected $trataVariaveis;
    protected $estrutura;
    protected $codigoFonte;
    protected $calcular;

    public function __construct() {
        $this->trataVariaveis =  new TratarVariaveis();
        $this->estrutura = new Estrutura();
        $this->codigoFonte = new VerificaInicioFIm();
        $this->calcular =  new Calcular();
    }

    public function getDados ($dados) {
        try {
            $codigoFonte = array_filter(explode("\n", $dados));
            $inicioCompilador = array_shift($codigoFonte);
            $finalCompilador = array_pop($codigoFonte);
            $retornoInicioFim = $this->codigoFonte->inicioFim($inicioCompilador, $finalCompilador);
            if (isset($retornoInicioFim['erro']) && $retornoInicioFim['erro']) {
                throw new Exception($retornoInicioFim['mensagem']);
            }
            $linhaVariavel = explode(" ", $codigoFonte[0]);
            $valoresVariaveis = $this->trataVariaveis->varDeclarada($linhaVariavel);
            if ($valoresVariaveis['erro']) {
                throw new Exception($valoresVariaveis['mensagem']);
            }
            unset($linhaVariavel);
            array_shift($codigoFonte);
            $retornoEstrutura = $this->estrutura->estrutura($codigoFonte, $valoresVariaveis['variaveis']);
            if ($retornoEstrutura['erro']) {
                throw new Exception($retornoEstrutura['mensagem']);
            }
            // $variaveisLidas = $this->trataVariaveis->verificarVariavelLida($valoresVariaveis['variaveis'], $retornoEstrutura['safe']);
            // if(!$variaveisLidas) {
            //     throw new Exception($variaveisLidas['mensagem']);
            // }
            // unset($retornoEstrutura['erro']);
            $retornoCalculos = $this->calcular->calcularValores($retornoEstrutura['linhas'], $valoresVariaveis['variaveis']);
            if (isset($retornoCalculos['erro']) && $retornoCalculos['erro']) {
                throw new Exception($retornoCalculos['mensagem']);
            }
            return $retornoEstrutura;
        } catch (Exception $e) {
            LogErros::log($e->getMessage());
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
    }
}