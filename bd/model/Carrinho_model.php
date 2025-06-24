<?php
class Carrinho
{
    private $id;
    private $carrinhoItem_id;
    private $usuario_id;
    private $produto_id;
    private $quantidade;
    private $preco_unitario;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCarrinhoItemId($carrinhoItem_id)
    {
        $this->carrinhoItem_id = $carrinhoItem_id;
    }

    public function getCarrinhoItemId()
    {
        return $this->carrinhoItem_id;
    }

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setProdutoId($produto_id)
    {
        $this->produto_id = $produto_id;
    }

    public function getProdutoId()
    {
        return $this->produto_id;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setPrecoUnitario($preco_unitario)
    {
        $this->preco_unitario = $preco_unitario;
    }

    public function getPrecoUnitario()
    {
        return $this->preco_unitario;
    }
}