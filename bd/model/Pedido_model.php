<?php
class Pedido
{
    private $id;
    private $usuario_id;
    private $status;
    private $valor_total;
    private $endereco_entrega_id;
    private $forma_pagamento_id;
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

    public function setUsuarioId($usuario_id)
    {
        $this->usuario_id = $usuario_id;
    }

    public function getUsuarioId()
    {
        return $this->usuario_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setValorTotal($valor_total)
    {
        $this->valor_total = $valor_total;
    }
    public function getValorTotal()
    {
        return $this->valor_total;
    }
    public function setEnderecoEntregaId($endereco_entrega_id)
    {
        $this->endereco_entrega_id = $endereco_entrega_id;
    }
    public function getEnderecoEntregaId()
    {
        return $this->endereco_entrega_id;
    }
    public function setFormaPagamentoId($forma_pagamento_id)
    {
        $this->forma_pagamento_id = $forma_pagamento_id;
    }
    public function getFormaPagamentoId()
    {
        return $this->forma_pagamento_id;
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