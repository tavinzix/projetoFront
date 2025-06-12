<?php
class FormaPagamento {
    private $id;
    private $user_id;
    private $nome_titular;
    private $nome_cartao;
    private $numero_cartao;
    private $validade;
    private $cvv;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setUserId($user_id){
        $this->user_id = $user_id;
    }

    public function getUserId(){
        return $this->user_id;
    }

    public function setNomeTitular($nome_titular){
        $this->nome_titular = $nome_titular;
    }
    public function getNomeTitular(){
        return $this->nome_titular;
    }
    public function setNomeCartao($nome_cartao){
        $this->nome_cartao = $nome_cartao;
    }
    public function getNomeCartao(){
        return $this->nome_cartao;
    }
    public function setNumeroCartao($numero_cartao){
        $this->numero_cartao = $numero_cartao;
    }
    public function getNumeroCartao(){
        return $this->numero_cartao;
    }
    public function setValidade($validade){
        $this->validade = $validade;
    }
    public function getValidade(){
        return $this->validade;
    }
    public function setCvv($cvv){
        $this->cvv = $cvv;
    }
    public function getCvv(){
        return $this->cvv;
    }
}