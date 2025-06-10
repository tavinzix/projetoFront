<?php
session_start();
require_once('../model/Endereco_model.php');
require_once('../dao/enderecoUsuario_DAO.php');
require_once('../dao/conexao.php');

$conexao = (new Conexao())->conectar();

#CADASTRO ENDERECO
if ($_POST['acao'] == 'salvar') {
    $userId = $_POST['userId'];
    $tipo = $_POST['tipo'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];

    $endereco = new Endereco();
    $endereco->setUserId($userId);
    $endereco->setTipo($tipo);
    $endereco->setCep($cep);
    $endereco->setEstado($estado);
    $endereco->setCidade($cidade);
    $endereco->setBairro($bairro);
    $endereco->setRua($rua);
    $endereco->setNumero($numero);
    $endereco->setComplemento($complemento);

    $enderecoDAO = new enderecoUsuario_DAO($conexao);
    $retorno = $enderecoDAO->inserirEndereco($endereco);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Endereço cadastrado com sucesso!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar endereço!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    }
}

#EDITAR ENDERECO
else if ($_POST['acao'] == 'editar') {
    $id = $_POST['id'];
    $userId = $_POST['userId'];
    $tipo = $_POST['tipo'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];

    $endereco = new Endereco();
    $endereco->setId($id);
    $endereco->setUserId($userId);
    $endereco->setTipo($tipo);
    $endereco->setCep($cep);
    $endereco->setEstado($estado);
    $endereco->setCidade($cidade);
    $endereco->setBairro($bairro);
    $endereco->setRua($rua);
    $endereco->setNumero($numero);
    $endereco->setComplemento($complemento);

    $enderecoDAO = new enderecoUsuario_DAO($conexao);
    $retorno = $enderecoDAO->alterarEndereco($endereco);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Endereço atualizado com sucesso!';
        header("Location: ../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao atualizar endereço!';
        header("Location: ../view/perfilUsuario.php");
        exit();
    }
}

#DELETAR ENDERECO
else if ($_POST['acao'] == 'excluir') {
    $id = $_POST['id'];
    $userId = $_POST['userId'];

    $endereco = new Endereco();
    $endereco->setId($id);
    $endereco->setUserId($userId);

    $enderecoDAO = new enderecoUsuario_DAO($conexao);
    $retorno = $enderecoDAO->deletarEndereco($endereco);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Endereço removido com sucesso!';
        header("Location: ../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao remover endereço!';
        header("Location: ../view/perfilUsuario.php");
        exit();
    }
}
