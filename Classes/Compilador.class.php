<?php

require_once 'Classes/TratarVariaveis.class.php';
require_once 'Classes/Estrutura.class.php';
require_once 'Classes/VerificaInicioFim.class.php';
require_once 'Classes/TratarResultadoFinal.class.php';
require_once 'Classes/LogErros.class.php';

class Compilador {
    protected $trataVariaveis;
    protected $estrutura;
    protected $verificaInicioFim;
    protected $retornoFinal;

    public function __construct() {
        $this->trataVariaveis =  new TratarVariaveis();
        $this->estrutura = new Estrutura();
        $this->verificaInicioFim = new VerificaInicioFIm();
        $this->retornoFinal = new TratarResultadoFinal();
    }

    /**
     * Metodo que verifica as estruturas, variaveis do compilador
     * 
     * @param array $dados
     * @return array
     * @throws Exception array
     */
    public function getDados($dados) {
        try {
            $codigoFonte = array_filter(explode("\n", $dados));
            $inicioCompilador = array_shift($codigoFonte);
            $finalCompilador = array_pop($codigoFonte);
            $retornoInicioFim = $this->verificaInicioFim->inicioFim($inicioCompilador, $finalCompilador);
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
            unset($retornoEstrutura['erro']);
            $retornoResultado = TratarResultadoFinal::mostrarResultadoFinal($retornoEstrutura);
            return [
                'erro' => false,
                'mensagem' => "",
                'resultado' => $retornoResultado,
                'dados_variaveis' => $valoresVariaveis['variaveis']
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