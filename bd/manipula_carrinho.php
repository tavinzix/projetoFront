<?php
session_start();
require_once('config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    //TODO ao voltar do login recuperar a página do item que estava
    header("Location:../view/login.html");
}

if ($_POST['acao'] == 'carrinho') {
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

    $sql_InsertCarrinhoItem = "INSERT INTO carrinho_itens (carrinho_id, produto_id, quantidade, preco_unitario) 
                               VALUES (:codcarrinho, :cod, :qtd, :preco_unitario)";
    $statement_insert = $connection->prepare($sql_InsertCarrinhoItem);
    $statement_insert->bindParam(':codcarrinho', $codcarrinho, PDO::PARAM_INT);
    $statement_insert->bindParam(':cod', $cod, PDO::PARAM_INT);
    $statement_insert->bindParam(':qtd', $qtd, PDO::PARAM_INT);
    $statement_insert->bindParam(':preco_unitario', $preco_unitario, PDO::PARAM_STR);
    $statement_insert->execute();

    $carrinhoItem = $statement_insert->fetch(PDO::FETCH_ASSOC);

    header("Location: ../view/produto.php?id=$cod");
    exit;
}
