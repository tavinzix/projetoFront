<?php
session_start();
require_once('../model/Usuario_model.php');
require_once('../dao/usuario_DAO.php');
require_once('../dao/conexao.php');

$conexao = (new Conexao())->conectar();

#CADASTRO USUARIO
if ($_POST['acao'] == 'cadastrar') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $nascimento = $_POST['nascimento'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];

    $usuario = new Usuario();
    $usuario->setNomeCompleto($nome);
    $usuario->setEmail($email);
    $usuario->setCpf($cpf);
    $usuario->setDtNasc($nascimento);
    $usuario->setTelefone($telefone);
    $usuario->setSenha($senha);
    $usuarioDAO = new usuario_DAO($conexao);
    $retorno = $usuarioDAO->cadastrarUsuario($usuario);
    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Usuário cadastrado com sucesso';
        header("Location: ../../view/login.html");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar usuário';
        header("Location: ../../view/form_criarContaUsuario.php");
        exit();
    }
}

#EDITAR USUARIO
else if ($_POST['acao'] == 'editar') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $nome_arquivo = $_FILES['novaImagem']['name'];
    $tamanho_arquivo = $_FILES['novaImagem']['size'];
    $arquivo_temporario = $_FILES['novaImagem']['tmp_name'];

    $usuario = new Usuario();
    $usuario->setId($id);
    $usuario->setNomeCompleto($nome);
    $usuario->setEmail($email);
    $usuario->setTelefone($telefone);
    $usuario->setSenha($nova_senha);
    $usuario->setImgUser($nome_arquivo);
    $usuarioDAO = new usuario_DAO($conexao);

    if ($nome_arquivo != '') {
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $id . '.' . $extensao;

        //deleta imagem antiga
        $extensoes_possiveis = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        foreach ($extensoes_possiveis as $ext) {
            $arquivo_antigo = "../../img/users/" . $id . "." . $ext;
            if (file_exists($arquivo_antigo)) {
                unlink($arquivo_antigo);
            }
        }

        //move para a pasta
        if (move_uploaded_file($arquivo_temporario, "../../img/users/$novo_nome")) {
            $usuario->setImgUser($novo_nome);
            if (isset($senha_atual) && $senha_atual != '' && isset($nova_senha) && $nova_senha != '' && isset($confirmar_senha) && $confirmar_senha != '') {
                $retorno = $usuarioDAO->editarTodosOsCampos($usuario);
            } else {
                $retorno = $usuarioDAO->editarSemSenha($usuario);
            }
        } else {
            echo "Arquivo não pode ser copiado para o servidor.";
        }
    } else {
        if (isset($senha_atual) && $senha_atual != '' && isset($nova_senha) && $nova_senha != '' && isset($confirmar_senha) && $confirmar_senha != '') {
            $retorno = $usuarioDAO->editarSemImagemComSenha($usuario);
        } else {
            $retorno = $usuarioDAO->editarSemImagemSemSenha($usuario);
        }
    }

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Usuário editado com sucesso';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao editar usuário';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    }
}

#REMOVER FOTO
else if ($_POST['acao'] == 'removerFoto') {
    $id = $_POST['id'];

    $usuario = new Usuario();
    $usuario->setId($id);

    $usuarioDAO = new usuario_DAO($conexao);
    $retorno = $usuarioDAO->removerFoto($usuario);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Alteração efetuada com sucesso';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar categoria';
        header("Location: ../../view/categorias.php");
        exit();
    }
}
