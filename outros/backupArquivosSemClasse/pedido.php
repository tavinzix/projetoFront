<?php
session_start();
require_once('config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    //TODO ao voltar do login recuperar a página do item que estava
    header("Location:../view/login.html");
}

// para adicionar item ao carrinho
if ($_POST['acao'] == 'finalizar') {
    $userId = $_SESSION['usuario_id'];
    $itens = $_SESSION['carrinhotemp'];
    $endereco = $_POST['endereco_id'];
    $pagamento = $_POST['pagamento_id'];
    $total = $_POST['total'];
    
    $sql_InsertPedido = 'INSERT into pedidos (usuario_id, status, valor_total, endereco_entrega_id, forma_pagamento_id)
                        values (:userId, 1, :valorTotal, :enderecoId, :formaId)';

    $stmt_insertPedido = $connection->prepare($sql_InsertPedido);
    $stmt_insertPedido->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt_insertPedido->bindParam(':valorTotal', $total, PDO::PARAM_STR);
    $stmt_insertPedido->bindParam(':enderecoId', $endereco, PDO::PARAM_INT);
    $stmt_insertPedido->bindParam(':formaId', $pagamento, PDO::PARAM_INT);
    $stmt_insertPedido->execute();
    $pedidoId = $connection->lastInsertId();

    // insere os itens do carrinho no pedido
    $sql_InsertPedidoItem = 'INSERT into pedidos_itens (pedido_id, produto_id, quantidade, preco_unitario)
                            values (:pedidoId, :produtoId, :quantidade, :precoUnitario)';
    $stmt_insertPedidoItem = $connection->prepare($sql_InsertPedidoItem);
    $stmt_insertPedidoItem->bindParam(':pedidoId', $pedidoId, PDO::PARAM_INT);
    $stmt_insertPedidoItem->bindParam(':produtoId', $produtoId, PDO::PARAM_INT);
    $stmt_insertPedidoItem->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);
    $stmt_insertPedidoItem->bindParam(':precoUnitario', $precoUnitario, PDO::PARAM_STR);

    foreach ($itens as $item) {
        $produtoId = $item['id'];
        $quantidade = $item['quantidade'];
        $precoUnitario = $item['preco_unitario'];

        // executa a inserção para cada item
        $stmt_insertPedidoItem->execute();
    }

    $sql_BuscaCarrinho = 'SELECT id FROM carrinho WHERE usuario_id = :userId LIMIT 1';
    $statement_buscaCarrinho = $connection->prepare($sql_BuscaCarrinho);
    $statement_buscaCarrinho->bindParam(':userId', $userId, PDO::PARAM_INT);
    $statement_buscaCarrinho->execute();
    $codcarrinho = $statement_buscaCarrinho->fetchColumn();

    //limpa do carrinho os itens que foram comprados
    $sql_deleteCarrinho = 'DELETE FROM carrinho_itens WHERE carrinho_id = :carrinhoId and produto_id in (:produtoId)';
    $stmt_deleteCarrinho = $connection->prepare($sql_deleteCarrinho);
    $stmt_deleteCarrinho->bindParam(':carrinhoId', $codcarrinho, PDO::PARAM_INT);
    $stmt_deleteCarrinho->bindParam(':produtoId', $produtoId, PDO::PARAM_INT);

    foreach ($itens as $item) {
        $produtoId = $item['id'];
        $stmt_deleteCarrinho->execute();
    }

    // limpa o carrinho temporário
    unset($_SESSION['carrinhotemp']);
    // redireciona para a página de confirmação ou sucesso
    header("Location: ../view/perfilUsuario.php");

}
