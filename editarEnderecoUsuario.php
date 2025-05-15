<?php
session_start();
require_once('config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_POST['acao'])) {
    header("Location: perfilUsuario.php");
    exit();
}

$acao = $_POST['acao'];

if ($acao == 'editar') {
    $sql = "UPDATE enderecos SET tipo = :tipo, cep = :cep, estado = :estado, cidade = :cidade, bairro = :bairro, 
            rua = :rua, numero = :numero, complemento = :complemento WHERE id = :id AND user_id = :userId";
    
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':tipo' => $_POST['tipo'],
        ':cep' => $_POST['cep'],
        ':estado' => $_POST['estado'],
        ':cidade' => $_POST['cidade'],
        ':bairro' => $_POST['bairro'],
        ':rua' => $_POST['rua'],
        ':numero' => $_POST['numero'],
        ':complemento' => $_POST['complemento'],
        ':id' => $_POST['id'],
        ':userId' => $_POST['userId']
    ]);

    $_SESSION['msgSucesso'] = 'Endereço atualizado com sucesso!';
    header("Location: perfilUsuario.php");
    exit();

} elseif ($acao == 'excluir') {
    $sql = "DELETE FROM enderecos WHERE id = :id AND user_id = :userId";
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':id' => $_POST['id'],
        ':userId' => $_POST['userId']
    ]);

    $_SESSION['msgSucesso'] = 'Endereço removido com sucesso!';
    header("Location: perfilUsuario.php");
    exit();

} elseif ($acao == 'salvar') {
    $sql = "INSERT INTO enderecos (user_id, tipo, cep, estado, cidade, bairro, rua, numero, complemento)
            VALUES (:user_id, :tipo, :cep, :estado, :cidade, :bairro, :rua, :numero, :complemento)";
    
    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':user_id' => $_SESSION['id'],
        ':tipo' => $_POST['tipo'],
        ':cep' => $_POST['cep'],
        ':estado' => $_POST['estado'],
        ':cidade' => $_POST['cidade'],
        ':bairro' => $_POST['bairro'],
        ':rua' => $_POST['rua'],
        ':numero' => $_POST['numero'],
        ':complemento' => $_POST['complemento']
    ]);

    $_SESSION['msgSucesso'] = 'Endereço cadastrado com sucesso!';
    header("Location: perfilUsuario.php");
    exit();
}
?>
