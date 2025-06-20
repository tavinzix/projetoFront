<?php
session_start();
require_once('../dao/conexao.php');
require_once('../model/Solicitacao_model.php');
require_once('../model/Vendedor_model.php');
require_once('../dao/solicitacao_DAO.php');
require_once('../dao/vendedor_DAO.php');

$conexao = (new Conexao())->conectar();

// para usuario cadastrar solicitação de vendedor
// insere na tabela solicitacao vendedor
if ($_POST['acao'] == 'cadastrar') {
    $userId = $_POST['user_id'];
    $nome = $_POST['nome_loja'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $categoria = $_POST['categoria'];
    $descricao = $_POST['descricao'];

    $solicitacao = new Solicitacao();
    $solicitacao->setUserId($userId);
    $solicitacao->setNomeLoja($nome);
    $solicitacao->setCnpj($cnpj);
    $solicitacao->setEmail($email);
    $solicitacao->setTelefone($telefone);
    $solicitacao->setCep($cep);
    $solicitacao->setEstado($estado);
    $solicitacao->setCidade($cidade);
    $solicitacao->setBairro($bairro);
    $solicitacao->setRua($rua);
    $solicitacao->setNumero($numero);
    $solicitacao->setCategoria($categoria);
    $solicitacao->setDescricaoLoja($descricao);
    $solicitacao->setStatus(1);

    $solicitacaoDAO = new solicitacao_DAO($conexao);
    $retorno = $solicitacaoDAO->enviarSolicitacao($solicitacao);

    if ($retorno) {
        header("Location: ../../view/solicitacaoCadastroVendedor.php");
        exit();
    } else {
        header("Location: ../../view/solicitacaoCadastroVendedor.php");
        exit();
    }
}

// para adm aprovar a solicitação como vendedor
// insere na tabela vendedor e  da um update na tabela da solicitação para definir como aprovada

else if ($_POST['acao'] == 'aprovar') {
    $userId = $_POST['id_user'];
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $descricao = $_POST['descricao'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $categoria = $_POST['categoria'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];

    try {
        $conexao->beginTransaction();

        $vendedor = new Vendedor();
        $vendedor->setUserId($userId);
        $vendedor->setNomeLoja($nome);
        $vendedor->setCnpj($cnpj);
        $vendedor->setEmail($email);
        $vendedor->setTelefone($telefone);
        $vendedor->setCep($cep);
        $vendedor->setEstado($estado);
        $vendedor->setCidade($cidade);
        $vendedor->setBairro($bairro);
        $vendedor->setRua($rua);
        $vendedor->setNumero($numero);
        $vendedor->setCategoria($categoria);
        $vendedor->setDescricaoLoja($descricao);
        $vendedor->setStatus(1);

        // Insere o vendedor
        $vendedorDAO = new vendedor_DAO($conexao);
        $vendedorDAO->cadastrarVendedor($vendedor);

        // Atualiza a solicitação como aprovada
        $solicitacao = new Solicitacao();
        $solicitacao->setUserId($userId);

        $solicitacaoDAO = new solicitacao_DAO($conexao);
        $solicitacaoDAO->atualizaSolicitacaoAprovada($solicitacao);

        $conexao->commit();

        $_SESSION['msgSucesso'] = 'Solicitação aprovada com sucesso';
        header("Location:../view/solicitacaoPendente.php");
        exit();
    } catch (Exception $e) {
        $conexao->rollBack();
        echo 'Erro: ' . $e->getMessage();
        exit();
    }
}
// para o adm recusar a solicitação de vendedor
// da um update no status da solicitação e guarda o motivo
else if ($_POST['acao'] == 'rejeitar') {
    $solicitacaoId = $_POST['id_pedido'];
    $motivo = $_POST['motivo'];

    try {
        $solicitacao = new Solicitacao();
        $solicitacao->setId($solicitacaoId);
        $solicitacao->setStatus(2);
        $solicitacao->setMotivoRejeicao($motivo);
        
        $solicitacaoDAO = new solicitacao_DAO($conexao);
        $solicitacaoDAO->atualizaSolicitacaoRejeiada($solicitacao);
        
        $_SESSION['msgSucesso'] = 'Solicitação rejeitada com sucesso';
        // header("Location: ../view/solicitacaoPendente.php");
        exit();
    } catch (Exception $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}
