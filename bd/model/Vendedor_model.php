<?php
class Vendedor
{
    private $id;
    private $user_id;
    private $nome_loja;
    private $cnpj;
    private $descricao_loja;  
    private $email;
    private $telefone;
    private $categoria;
    private $cep;
    private $estado;
    private $cidade;
    private $bairro;
    private $rua;
    private $numero;
    private $status;
    private $img_vendedor;
    private $motivo_rejeicao;

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function setNomeLoja($nome_loja)
    {
        $this->nome_loja = $nome_loja;
    }
    public function getNomeLoja()
    {
        return $this->nome_loja;
    }
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
    }
    public function getCnpj()
    {
        return $this->cnpj;
    }
    public function setDescricaoLoja($descricao_loja)
    {
        $this->descricao_loja = $descricao_loja;
    }
    public function getDescricaoLoja()
    {
        return $this->descricao_loja;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }
    public function getTelefone()
    {
        return $this->telefone;
    }
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }
    public function getCategoria()
    {
        return $this->categoria;
    }
    public function setCep($cep)
    {
        $this->cep = $cep;
    }
    public function getCep()
    {
        return $this->cep;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }
    public function getCidade()
    {
        return $this->cidade;
    }
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }
    public function getBairro()
    {
        return $this->bairro;
    }
    public function setRua($rua)
    {
        $this->rua = $rua;
    }
    public function getRua()
    {
        return $this->rua;
    }
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }
    public function getNumero()
    {
        return $this->numero;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setImgVendedor($img_vendedor)
    {
        $this->img_vendedor = $img_vendedor;
    }
    public function getImgVendedor()
    {
        return $this->img_vendedor;
    }

    public function setMotivoRejeicao($motivo_rejeicao)
    {
        $this->motivo_rejeicao = $motivo_rejeicao;
    }

    public function getMotivoRejeicao()
    {
        return $this->motivo_rejeicao;
    }
    
}
