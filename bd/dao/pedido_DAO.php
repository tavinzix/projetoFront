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

    function buscaPedidosComStatus($userId)
    {
        try {
            $query = $this->conexao->prepare("SELECT *, CASE WHEN status = '1' THEN 'Aguardando Pagamento' 
                                                WHEN status = '2' THEN 'A caminho' 
                                                WHEN status = '3' THEN 'Aguardando Envio' 
                                                WHEN status = '4' THEN 'Enviado' 
                                                WHEN status = '5' THEN 'Entregue' 
                                                WHEN status = '6' THEN 'Cancelado' 
                                                WHEN status = '7' THEN 'Reembolsado'
                                                WHEN status = '8' THEN 'Estornado' 
                                                WHEN status = '9' THEN 'Devolvido' END AS status_texto  from pedidos pe 
                                            join pedidos_itens pi on pi.pedido_id = pe.id
                                            join produtos pr on pr.id = pi.produto_id
                                            join produto_imagens pri on pri.produto_id = pr.id and pri.ordem = '1' 
                                            WHERE pe.usuario_id = :userId");

            $query->bindParam(':userId', $userId, PDO::PARAM_INT);

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
