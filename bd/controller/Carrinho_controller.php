<?php
session_start();
require_once('../model/Carrinho_model.php');
require_once('../dao/carrinho_DAO.php');
require_once('../dao/vendedor_DAO.php');
require_once('../dao/conexao.php');

$conexao = (new Conexao())->conectar();

$vendedorDAO = new vendedor_DAO($conexao);

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:../../view/login.php");
    exit();
}

#ADICIONAR ITEM AO CARRINHO
if ($_POST['acao'] == 'adicionar') {
    $userId = $_SESSION['usuario_id'];
    $carrinho = new Carrinho();
    $carrinho->setUsuarioId($userId);
    $carrinhoDAO = new carrinho_DAO($conexao);
    $codCarrinho = $carrinhoDAO->verificaCarrinho($carrinho);
    // se não há um carrinho, cria
    if (!$codCarrinho) {
        $codCarrinho = $carrinhoDAO->criarCarrinho($carrinho);
    }

    // pega o item, quantidade, e preco
    $qtd = $_POST['quantidade'];
    $cod = $_POST['produto_id'];
    $preco_unitario = $_POST['preco'];
    $carrinho->setId($codCarrinho);
    $carrinho->setProdutoId($cod);
    $carrinho->setQuantidade($qtd);
    $carrinho->setPrecoUnitario($preco_unitario);

    // busca se já há um item no carrinho com o mesmo id
    $itemExistente = $carrinhoDAO->verificarItemCarrinho($carrinho);

    // se já tem o item no carrinho, atualiza a quantidade
    if ($itemExistente) {
        $novaQuantidade = $itemExistente['quantidade'] + $qtd;
        $carrinho->setCarrinhoItemId($itemExistente['id']);
        $carrinhoDAO->atualizarQuantidadeItemCarrinho($carrinho, $novaQuantidade);
    }

    // se não tem, insere o item com as suas informações
    else {
        $carrinho->setQuantidade($qtd);
        $carrinho->setCarrinhoItemId(null);
        $carrinho->setId($codCarrinho);
        $carrinho->setProdutoId($cod);
        $carrinho->setPrecoUnitario($preco_unitario);

        $carrinhoDAO->adicionarProduto($carrinho);
    }

    $_SESSION["msg"] = "Produto adicionado com sucesso";
   
    header("Location: ../../view/produto.php?id=$cod");
    exit();

} else if ($_POST['acao'] == 'remover') {
    $carrinhoDAO = new carrinho_DAO($conexao);
    $userId = $_SESSION['usuario_id'];
    $produtoId = $_POST['item_id'];

    $carrinho = new Carrinho();
    $carrinho->setProdutoId($produtoId);
    $carrinho->setUsuarioId($userId);

    $carrinhoId = $carrinhoDAO->verificaCarrinho($carrinho);


    $carrinho->setId($carrinhoId);

    $resultado = $carrinhoDAO->removerItemCarrinho($carrinho);

    if ($resultado) {
        header("Location: ../../view/carrinho.php");
        exit();
    }
} else if ($_POST['acao'] == 'atualizar') {
    $carrinhoDAO = new carrinho_DAO($conexao);
    $userId = $_SESSION['usuario_id'];
    $produtoId = $_POST['item_id'];
    $novaQuantidade = $_POST['quantidade'];

    $carrinho = new Carrinho();
    $carrinho->setProdutoId($produtoId);
    $carrinho->setUsuarioId($userId);

    $carrinhoId = $carrinhoDAO->verificaCarrinho($carrinho);

    $carrinho->setId($carrinhoId);

    $carrinhoDAO = new carrinho_DAO($conexao);
    $resultado = $carrinhoDAO->atualizarQuantidadeItemCarrinho($carrinho, $novaQuantidade);

    if ($resultado) {
        echo ("atualizado");
    } else {
        echo ("erro ao atualizar");
    }
}
