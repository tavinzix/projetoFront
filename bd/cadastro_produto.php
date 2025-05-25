<?php
session_start();
require_once('../bd/config.inc.php');

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$categoria = $_POST['categoria'];
$marca = $_POST['marca'];
$preco = $_POST['preco'];
$estoque = $_POST['estoque'];
$userId = $_SESSION['usuario_id'];

$nome_arquivo1 = $_FILES['imagem1']['name'];
$tamanho_arquivo1 = $_FILES['imagem1']['size'];
$arquivo_temporario1 = $_FILES['imagem1']['tmp_name'];

$nome_arquivo2 = $_FILES['imagem2']['name'];
$tamanho_arquivo2 = $_FILES['imagem2']['size'];
$arquivo_temporario2 = $_FILES['imagem2']['tmp_name'];

$nome_arquivo3 = $_FILES['imagem3']['name'];
$tamanho_arquivo3 = $_FILES['imagem3']['size'];
$arquivo_temporario3 = $_FILES['imagem3']['tmp_name'];

$nome_arquivo4 = $_FILES['imagem4']['name'];
$tamanho_arquivo4 = $_FILES['imagem4']['size'];
$arquivo_temporario4 = $_FILES['imagem4']['tmp_name'];

$nome_arquivo5 = $_FILES['imagem5']['name'];
$tamanho_arquivo5 = $_FILES['imagem5']['size'];
$arquivo_temporario5 = $_FILES['imagem5']['tmp_name'];


$sql = "INSERT INTO produtos (nome, descricao, categoria_id, marca)
            VALUES (:nome, :descricao, :categoria_id, :marca)";

$stmt_produto = $connection->prepare($sql);

$stmt_produto->bindParam(':nome', $nome, PDO::PARAM_STR);
$stmt_produto->bindParam(':descricao', $descricao, PDO::PARAM_STR);
$stmt_produto->bindParam(':categoria_id', $categoria, PDO::PARAM_STR);
$stmt_produto->bindParam(':marca', $marca, PDO::PARAM_INT);
$stmt_produto->execute();


$sqlVendedor = "SELECT * from vendedores where user_id = :user_id";
$stmt_vendedor = $connection->prepare($sqlVendedor);

$stmt_vendedor->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt_vendedor->execute();

$vendedor = $stmt_vendedor->fetch(PDO::FETCH_ASSOC);
$produto_id = $connection->lastInsertId();

$sqlVendedorProduto = "INSERT INTO vendedores_produtos (vendedor_id, produto_id, preco, estoque, ativo)
                            VALUES (:vendedor_id, :produto_id, :preco, :estoque, '1')";

$stmt_vendedorProduto = $connection->prepare($sqlVendedorProduto);

$vendedor_id = $vendedor['id'];
$stmt_vendedorProduto->bindParam(':vendedor_id', $vendedor_id, PDO::PARAM_INT);
$stmt_vendedorProduto->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
$stmt_vendedorProduto->bindParam(':preco', $preco, PDO::PARAM_STR);
$stmt_vendedorProduto->bindParam(':estoque', $estoque, PDO::PARAM_INT);
$stmt_vendedorProduto->execute();



$imagens = [
    1 => ['arquivo' => $_FILES['imagem1'], 'obrigatoria' => true],
    2 => ['arquivo' => $_FILES['imagem2'], 'obrigatoria' => false],
    3 => ['arquivo' => $_FILES['imagem3'], 'obrigatoria' => false],
    4 => ['arquivo' => $_FILES['imagem4'], 'obrigatoria' => false],
    5 => ['arquivo' => $_FILES['imagem5'], 'obrigatoria' => false],
];

foreach ($imagens as $ordem => $dados) {
    $arquivo = $dados['arquivo'];
    $obrigatoria = $dados['obrigatoria'];

    if ($arquivo['name'] != '') {
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $novo_nome = $produto_id . '_' . $ordem . '.' . $extensao;
        $caminho = "../img/produtos/$novo_nome";

        if (file_exists($caminho)) {
            unlink($caminho);
        }

        if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
            $sqlImagem = "INSERT INTO produto_imagens (produto_id, imagem_url, ordem) 
                          VALUES (:produto_id, :imagem_url, :ordem)";
            $stmt_imagem = $connection->prepare($sqlImagem);
            $stmt_imagem->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $stmt_imagem->bindParam(':imagem_url', $novo_nome, PDO::PARAM_STR);
            $stmt_imagem->bindParam(':ordem', $ordem, PDO::PARAM_INT);
            $stmt_imagem->execute();
        } else {
            echo "Falha ao enviar a imagem $ordem.";
        }
    } elseif ($obrigatoria) {
        echo "A imagem 1 é obrigatória.";
        exit;
    }
}


if ($stmt_produto && $stmt_vendedorProduto) {
    $_SESSION['msgSucesso'] = 'Alteração Efetuada com sucesso';
    header("Location:../view/gerenciarProduto.php");
} else {
    echo "Erro";
}