<?php
session_start();
if (isset($_POST['itensSelecionados'])) {
    $itens = [];

    $quantidades = $_POST['quantidades'] ?? [];

    foreach ($_POST['itensSelecionados'] as $item) {
        list($id, $preco) = explode(',', $item);
        $id = (int)$id;
        $preco = (float)$preco;
        $quantidade = isset($quantidades[$id]) ? (int)$quantidades[$id] : 1;

        $itens[] = [
            'id' => $id,
            'preco_unitario' => $preco,
            'quantidade' => $quantidade
        ];
    }

    $_SESSION['carrinhotemp'] = $itens;

    // Redireciona para a página de checkout
    header("Location: ../../view/checkoutCarrinho.php");
    exit();
} else {
    // Nenhum item selecionado
    header("Location: ../../view/carrinho.php");
    exit();
}
