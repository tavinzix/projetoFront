<?php
class pedido_DAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }
    
    function criaPedido(Pedido $pedido)
    {
        try {
            $query = $this->conexao->prepare("INSERT into pedidos (usuario_id, status, valor_total, endereco_entrega_id, forma_pagamento_id)
                                            values (:userId, 1, :valorTotal, :enderecoId, :formaId)");

            $query->execute([
                'userId' => $pedido->getUsuarioId(),
                'valorTotal' => $pedido->getValorTotal(),
                'enderecoId' => $pedido->getEnderecoEntregaId(),
                'formaId' => $pedido->getFormaPagamentoId()
            ]);

            return $this->conexao->lastInsertId();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function criarPedidoItens(Pedido $pedido)
    {
        try {
            $query = $this->conexao->prepare("INSERT into pedidos_itens (pedido_id, produto_id, quantidade, preco_unitario)
                            values (:pedidoId, :produtoId, :quantidade, :precoUnitario)");

            $query->execute([
                'pedidoId' => $pedido->getId(),
                'produtoId' => $pedido->getProdutoId(),
                'quantidade' => $pedido->getQuantidade(),
                'precoUnitario' => $pedido->getPrecoUnitario()
            ]);

            return $this->conexao->lastInsertId();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    
    
}