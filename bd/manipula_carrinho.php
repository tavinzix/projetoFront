<?php
session_start();
require_once('config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    //TODO ao voltar do login recuperar a página do item que estava
    header("Location:../view/login.html");
}

if ($_POST['acao'] == 'adicionar') {
    $userId = $_SESSION['usuario_id'];

    $sql_BuscaCarrinho = 'SELECT id FROM carrinho WHERE usuario_id = :userId LIMIT 1';
    $statement_buscaCarrinho = $connection->prepare($sql_BuscaCarrinho);
    $statement_buscaCarrinho->bindParam(':userId', $userId, PDO::PARAM_INT);
    $statement_buscaCarrinho->execute();
    $codcarrinho = $statement_buscaCarrinho->fetchColumn();

    if (!$codcarrinho) {
        $sql_Insertcarrinho = 'INSERT INTO carrinho (usuario_id) VALUES (:userId)';
        $statement_insertCarrinho = $connection->prepare($sql_Insertcarrinho);
        $statement_insertCarrinho->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement_insertCarrinho->execute();

        $codcarrinho = $connection->lastInsertId();
    }

    $qtd = $_POST['quantidade'];
    $cod = $_POST['produto_id'];
    $preco_unitario = $_POST['preco'];

    $sql_VerificaExistente = "SELECT id, quantidade FROM carrinho_itens 
                                WHERE carrinho_id = :carrinho_id AND produto_id = :produto_id";
    $stmt_verifica = $connection->prepare($sql_VerificaExistente);
    $stmt_verifica->bindParam(':carrinho_id', $codcarrinho, PDO::PARAM_INT);
    $stmt_verifica->bindParam(':produto_id', $cod, PDO::PARAM_INT);
    $stmt_verifica->execute();
    $itemExistente = $stmt_verifica->fetch(PDO::FETCH_ASSOC);

    if ($itemExistente) {
        $novaQuantidade = $itemExistente['quantidade'] + $qtd;
        $sql_Atualiza = "UPDATE carrinho_itens SET quantidade = :qtd WHERE id = :id";
        $stmt_atualiza = $connection->prepare($sql_Atualiza);
        $stmt_atualiza->bindParam(':qtd', $novaQuantidade, PDO::PARAM_INT);
        $stmt_atualiza->bindParam(':id', $itemExistente['id'], PDO::PARAM_INT);

        $stmt_atualiza->execute();

    } else {
        $sql_InsertCarrinhoItem = "INSERT INTO carrinho_itens (carrinho_id, produto_id, quantidade, preco_unitario) 
                               VALUES (:codcarrinho, :cod, :qtd, :preco_unitario)";
        $statement_insert = $connection->prepare($sql_InsertCarrinhoItem);
        $statement_insert->bindParam(':codcarrinho', $codcarrinho, PDO::PARAM_INT);
        $statement_insert->bindParam(':cod', $cod, PDO::PARAM_INT);
        $statement_insert->bindParam(':qtd', $qtd, PDO::PARAM_INT);
        $statement_insert->bindParam(':preco_unitario', $preco_unitario, PDO::PARAM_STR);
        $statement_insert->execute();
    }

    header("Location: ../view/produto.php?id=$cod");
    exit;
} else if ($_POST['acao'] == 'remover') {
    $itemId = $_POST['item_id'];

    $sql = "DELETE FROM carrinho_itens WHERE produto_id = :itemId";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "removido";
    } else {
        echo "erro";
    }
} else if ($_POST['acao'] == 'atualizar') {
    $itemId = $_POST['item_id'];
    $novaQuantidade = $_POST['quantidade'];

    $sql = "UPDATE carrinho_itens SET quantidade = :quantidade WHERE produto_id = :itemId";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':quantidade', $novaQuantidade, PDO::PARAM_INT);
    $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "atualizado";
    } else {
        echo "erro";
    }
}
