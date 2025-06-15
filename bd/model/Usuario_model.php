<?php
class Usuario
{
    private $id;
    private $nome_completo;
    private $email;
    private $senha;
    private $telefone;
    private $dt_nasc;
    private $cpf;
    private $data_criacao;
    private $status;
    private $img_user;

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setNomeCompleto($nome_completo)
    {
        $this->nome_completo = $nome_completo;
    }
    public function getNomeCompleto()
    {
        return $this->nome_completo;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
    public function getSenha()
    {
        return $this->senha;
    }
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }
    public function getTelefone()
    {
        return $this->telefone;
    }
    public function setDtNasc($dt_nasc)
    {
        $this->dt_nasc = $dt_nasc;
    }
    public function getDtNasc()
    {
        return $this->dt_nasc;
    }
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }
    public function getCpf()
    {
        return $this->cpf;
    }
    public function setDataCriacao($data_criacao)
    {
        $this->data_criacao = $data_criacao;
    }
    public function getDataCriacao()
    {
        return $this->data_criacao;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setImgUser($img_user)
    {
        $this->img_user = $img_user;
    }
    public function getImgUser()
    {
        return $this->img_user;
    }
}
