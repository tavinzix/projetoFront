<?php
session_start();
require_once('../model/Pedido_model.php');
require_once('../model/Carrinho_model.php');
require_once('../dao/pedido_DAO.php');
require_once('../dao/carrinho_DAO.php');
require_once('../dao/conexao.php');

$conexao = (new Conexao())->conectar();

#FINALIZAR PEDIDO
if ($_POST['acao'] == 'finalizar') {
    $userId = $_SESSION['usuario_id'];
    $itens = $_SESSION['carrinhotemp'];
    $endereco = $_POST['endereco_id'];
    $pagamento = $_POST['pagamento_id'];
    $total = $_POST['total'];

    $conexao->beginTransaction();
    $pedido = new Pedido();
    $pedido->setUsuarioId($userId);
    $pedido->setStatus(1); // status 1 para pedido em andamento
    $pedido->setValorTotal($total);
    $pedido->setEnderecoEntregaId($endereco);
    $pedido->setFormaPagamentoId($pagamento);
    $pedidoDAO = new pedido_DAO($conexao);
    
    $pedidoId = $pedidoDAO->criaPedido($pedido);

    // insere os itens do carrinho no pedido
    foreach ($itens as $item) {
        $produtoId = $item['id'];
        $quantidade = $item['quantidade'];
        $precoUnitario = $item['preco_unitario'];

        $pedido->setId($pedidoId);
        $pedido->setProdutoId($produtoId);
        $pedido->setQuantidade($quantidade);
        $pedido->setPrecoUnitario($precoUnitario);

        $pedidoDAO->criarPedidoItens($pedido);
    }
    
    $carrinhoDAO = new carrinho_DAO($conexao);
    $carrinho = new Carrinho();
    $carrinho->setUsuarioId($userId);

    $codCarrinho = $carrinhoDAO->verificaCarrinho($carrinho);
    
    foreach ($itens as $item) {
        $produtoId = $item['id'];
        // remove os itens do carrinho que foram comprados
        $carrinho->setProdutoId($produtoId);
        $carrinho->setId($codCarrinho);
        $carrinhoDAO->removerItemCarrinho($carrinho);
    }

    $conexao->commit();
    unset($_SESSION['carrinhotemp']);
    header("Location: ../../view/perfilUsuario.php");

}
