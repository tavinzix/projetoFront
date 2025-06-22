<?php
session_start();
require_once('../model/Produto_model.php');
require_once('../dao/produto_DAO.php');
require_once('../dao/vendedor_DAO.php');
require_once('../dao/conexao.php');

$conexao = (new Conexao())->conectar();

$vendedorDAO = new vendedor_DAO($conexao);

#CADASTRO PRODUTO
if ($_POST['acao'] == 'cadastrar') {
    $userId = $_SESSION['usuario_id'];
    $vendedor = $vendedorDAO->buscaVendedorPorIdUser($userId);
    $vendedor_id = $vendedor['id'];

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    $marca = $_POST['marca'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];

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

    // cria um vetor de imagens
    $imagens = [
        1 => ['arquivo' => $_FILES['imagem1'], 'obrigatoria' => true],
        2 => ['arquivo' => $_FILES['imagem2'], 'obrigatoria' => false],
        3 => ['arquivo' => $_FILES['imagem3'], 'obrigatoria' => false],
        4 => ['arquivo' => $_FILES['imagem4'], 'obrigatoria' => false],
        5 => ['arquivo' => $_FILES['imagem5'], 'obrigatoria' => false],
    ];

    try {
        $produto = new Produto();

        $produto->setVendedorId($vendedor_id);
        $produto->setNome($nome);
        $produto->setDescricao($descricao);
        $produto->setCategoriaId($categoria);
        $produto->setMarca($marca);
        $produto->setPreco($preco);
        $produto->setEstoque($estoque);

        $conexao->beginTransaction();

        $produtoDAO = new produto_DAO($conexao);

        $retornoProdutos = $produtoDAO->inserirProduto($produto);
        $produto_id = $conexao->lastInsertId();
        $retornoVendedorProduto = $produtoDAO->cadastrarVendedorProduto($produto, $produto_id);

        $conexao->commit();

        //percorre as imagens
        foreach ($imagens as $ordem => $dados) {
            $arquivo = $dados['arquivo'];
            $obrigatoria = $dados['obrigatoria'];

            if ($arquivo['name'] != '') {
                $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
                $novo_nome = $produto_id . '_' . $ordem . '.' . $extensao;
                $caminho = "../../img/produtos/$novo_nome";

                //exclui caso já tenha
                if (file_exists($caminho)) {
                    unlink($caminho);
                }

                //insere na tabela produto imagens
                if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
                    $produto->setImagemUrl($novo_nome);
                    $produto->setOrdem($ordem);
                    $produto->setId($produto_id);
                    $retornoImagens = $produtoDAO->inserirImagens($produto);
                } else {
                    echo "Falha ao enviar a imagem $ordem.";
                }
            } elseif ($obrigatoria) {
                echo "A imagem 1 é obrigatória.";
                exit;
            }
        }
    } catch (Exception $e) {
        $conexao->rollBack();
        echo 'Erro: ' . $e->getMessage(); 
        exit();
    }

    if ($retornoProdutos && $retornoVendedorProduto && isset($retornoImagens)) {
        $_SESSION['msgSucesso'] = 'Alteração Efetuada com sucesso';
        header("Location: ../../view/gerenciarProduto.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar produto';
        header("Location: ../../view/gerenciarProduto.php");
        exit();
    }
}
