<?php

require_once 'Classes/PalavrasReservadas.class.php';

class VerificaInicioFim {

    /**
     * Metodo para verificar se o codigo fonte possui inicio e fim.
     * 
     * @param array $dados
     * @return array
     * @throws Exception array
     */
    public function inicioFim($inicioCompilador, $finalCompilador) {
        try {
            
            if(!PalavrasReservadas::reservadas($inicioCompilador)) {
                throw new Exception("<#start não encontrado.");
            } 
            if (!PalavrasReservadas::reservadas($finalCompilador)) {
                throw new Exception("#> não encontrado.");
            }
            return true;
        } catch (Exception $e) {
            return [
                'erro' => true,
                'mensagem' => $e->getMessage()
            ];
        }
        
    }
}