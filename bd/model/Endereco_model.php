<?php
class Endereco {
    private $id;
    private $user_id;
    private $tipo;
    private $cep;
    private $estado;
    private $cidade;
    private $bairro;
    private $rua;
    private $numero;
    private $complemento;

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

    public function setTipo($tipo){
        $this->tipo = $tipo;
    }

    public function getTipo(){
        return $this->tipo;
    }

    public function setCep($cep){
        $this->cep = $cep;
    }

    public function getCep(){
        return $this->cep;
    }

    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function getEstado(){
        return $this->estado;
    }

    public function setCidade($cidade){
        $this->cidade = $cidade;
    }

    public function getCidade(){
        return $this->cidade;
    }

    public function setBairro($bairro){
        $this->bairro = $bairro;
    }

    public function getBairro(){
        return $this->bairro;
    }

    public function setRua($rua){
        $this->rua = $rua;
    }

    public function getRua(){
        return $this->rua;
    }

    public function setNumero($numero){
        $this->numero = $numero;
    }

    public function getNumero(){
        return $this->numero;
    }

    public function setComplemento($complemento){
        $this->complemento = $complemento;
    }

    public function getComplemento(){
        return $this->complemento;
    }
}
