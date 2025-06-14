<?php
session_start();
require_once('../dao/conexao.php');
require_once('../model/FormaPagamento_model.php');
require_once('../dao/formaPagamentoUsuario_DAO.php');

$conexao = (new Conexao())->conectar();

#CADASTRO FORMA PAGAMENTO
if ($_POST['acao'] == 'salvar') {
    $userId = $_POST['userId'];
    $nome_titular = $_POST['nome_titular'];
    $nome_cartao = $_POST['nome_cartao'];
    $numero_cartao = $_POST['numero_cartao'];
    $validade = $_POST['validade'];
    $cvv = $_POST['cvv'];

    $formaPagamento = new FormaPagamento();
    $formaPagamento->setUserId($userId);
    $formaPagamento->setNomeTitular($nome_titular);
    $formaPagamento->setNomeCartao($nome_cartao);
    $formaPagamento->setNumeroCartao($numero_cartao);
    $formaPagamento->setValidade($validade);
    $formaPagamento->setCvv($cvv);

    $formaPagamentoDAO = new formaPagamentoUsuario_DAO($conexao);
    $retorno = $formaPagamentoDAO->inserirForma($formaPagamento);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Forma de pagamento cadastrada com sucesso!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao cadastrar forma de pagamento!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    }
}

#EDITAR FORMA
else if ($_POST['acao'] == 'editar') {
    $id = $_POST['id_forma'];
    $userId = $_POST['id_usuario'];
    $nome_titular = $_POST['nome_titular'];
    $nome_cartao = $_POST['nome_cartao'];
    $numero_cartao = $_POST['numero_cartao'];
    $validade = $_POST['validade'];
    $cvv = $_POST['cvv'];

    $formaPagamento = new FormaPagamento();
    $formaPagamento->setId($id);
    $formaPagamento->setUserId($userId);
    $formaPagamento->setNomeTitular($nome_titular);
    $formaPagamento->setNomeCartao($nome_cartao);
    $formaPagamento->setNumeroCartao($numero_cartao);
    $formaPagamento->setValidade($validade);
    $formaPagamento->setCvv($cvv);

    $formaPagamentoDAO = new formaPagamentoUsuario_DAO($conexao);
    $retorno = $formaPagamentoDAO->alterarForma($formaPagamento);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Forma de pagamento atualizada com sucesso!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao atualizar forma de pagamento!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    }
}

#DELETAR FORMA
else if ($_POST['acao'] == 'excluir') {
    $id = $_POST['id'];
    $userId = $_POST['userId'];

    $formaPagamento = new FormaPagamento();
    $formaPagamento->setId($id);
    $formaPagamento->setUserId($userId);

    $formaPagamentoDAO = new formaPagamentoUsuario_DAO($conexao);
    $retorno = $formaPagamentoDAO->deletarForma($formaPagamento);

    if ($retorno) {
        $_SESSION['msgSucesso'] = 'Forma de pagamento removida com sucesso!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    } else {
        $_SESSION['msgSucesso'] = 'Erro ao remover forma de pagamento!';
        header("Location: ../../view/perfilUsuario.php");
        exit();
    }
}
