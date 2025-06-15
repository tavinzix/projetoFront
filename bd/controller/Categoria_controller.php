<?php
session_start();
require_once('../model/Categoria_model.php');
require_once('../dao/categoria_DAO.php');
require_once('../dao/conexao.php');

$conexao = (new Conexao())->conectar();

#CADASTRO CATEGORIA
if ($_POST['acao'] == 'cadastrar') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $url = $_POST['url'];
    $nome_arquivo = $_FILES['novaImagem']['name'];
    $tamanho_arquivo = $_FILES['novaImagem']['size'];
    $arquivo_temporario = $_FILES['novaImagem']['tmp_name'];

    $categoria = new Categoria();
    $categoria->setNome($nome);
    $categoria->setDescricao($descricao);
    $categoria->setUrl($url);
    if ($nome_arquivo != '') {
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $url . '.' . $extensao;

        //deleta imagem antiga
        $caminho_imagem_antiga = "../../img/categoria/" . $novo_nome;
        if (file_exists($caminho_imagem_antiga)) {
            unlink($caminho_imagem_antiga);
        }

        //move para a pasta
        if (move_uploaded_file($arquivo_temporario, "../../img/categoria/$novo_nome")) {
            $categoria->setImagem($novo_nome);
        } else {
            $_SESSION['msgErro'] = 'Erro ao mover o arquivo para o servidor.';
            header("Location: ../../view/categorias.php");
            exit();
        }
    }
    $categoriaDAO = new categoria_DAO($conexao);
    $retorno = $categoriaDAO->inserirCategoria($categoria);
    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Categoria cadastrada com sucesso';
        header("Location: ../../view/categorias.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar categoria';
        header("Location: ../../view/categorias.php");
        exit();
    }
}

#EDITAR CATEGORIA
else if ($_POST['acao'] == 'editar') {
    $id = $_POST['id_categoria'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $url = $_POST['url'];
    $nome_arquivo = $_FILES['novaImagem']['name'];
    $tamanho_arquivo = $_FILES['novaImagem']['size'];
    $arquivo_temporario = $_FILES['novaImagem']['tmp_name'];

    $categoria = new Categoria();
    $categoria->setId($id);
    $categoria->setNome($nome);
    $categoria->setDescricao($descricao);
    $categoria->setUrl($url);

    if ($nome_arquivo != '') {
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $url . '.' . $extensao;

        //deleta imagem antiga
        $caminho_imagem_antiga = "../../img/categoria/" . $novo_nome;
        if (file_exists($caminho_imagem_antiga)) {
            unlink($caminho_imagem_antiga);
        }

        //move para a pasta
        if (move_uploaded_file($arquivo_temporario, "../../img/categoria/$novo_nome")) {
            $categoria->setImagem($novo_nome);
        
            $categoriaDAO = new categoria_DAO($conexao);
            $retorno = $categoriaDAO->editarCategoriaComImagem($categoria);
        } else {
            $_SESSION['msgErro'] = 'Erro ao mover o arquivo para o servidor.';
            header("Location: ../../view/categorias.php");
            exit();
        }
    } else {
        $categoriaDAO = new categoria_DAO($conexao);
        $retorno = $categoriaDAO->editarCategoriaSemImagem($categoria);
    }

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Categoria editada com sucesso';
        header("Location: ../../view/categorias.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao editar categoria';
        header("Location: ../../view/categorias.php");
        exit();
    }
}

#INATIVAR CATEGORIA
else if ($_POST['acao'] == 'inativar') {
    $id = $_POST['id_categoria'];

    $categoria = new Categoria();
    $categoria->setId($id);

    $categoriaDAO = new categoria_DAO($conexao);
    $retorno = $categoriaDAO->inativarCategoria($categoria);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Categoria cadastrada com sucesso';
        header("Location: ../../view/categorias.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar categoria';
        header("Location: ../../view/categorias.php");
        exit();
    }
}

#ATIVAR CATEGORIA
else if ($_POST['acao'] == 'ativar') {
    $id = $_POST['id_categoria'];

    $categoria = new Categoria();
    $categoria->setId($id);

    $categoriaDAO = new categoria_DAO($conexao);
    $retorno = $categoriaDAO->ativarCategoria($categoria);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Categoria cadastrada com sucesso';
        header("Location: ../../view/categorias.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar categoria';
        header("Location: ../../view/categorias.php");
        exit();
    }
}
