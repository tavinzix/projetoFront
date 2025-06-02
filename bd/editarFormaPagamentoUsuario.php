<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');


//editar endereço do usuario
if ($_POST['acao'] == 'editar') {
    $id = $_POST['id'];
    $userId =$_POST['userId'];
    $tipo = $_POST['tipo'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];

    $sql = "UPDATE enderecos SET tipo = :tipo, cep = :cep, estado = :estado, cidade = :cidade, bairro = :bairro, 
            rua = :rua, numero = :numero, complemento = :complemento WHERE id = :id AND user_id = :userId";
    
    $stmt = $connection->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':cidade', $cidade, PDO::PARAM_STR);
    $stmt->bindParam(':bairro', $bairro, PDO::PARAM_STR);
    $stmt->bindParam(':rua', $rua, PDO::PARAM_STR);
    $stmt->bindParam(':numero', $numero, PDO::PARAM_STR);
    $stmt->bindParam(':complemento', $complemento, PDO::PARAM_STR);

    $stmt->execute();

    if($stmt){
        $_SESSION['msgSucesso'] = 'Endereço atualizado com sucesso!';
        header("Location: perfilUsuario.php");
        exit();
    }else{
        echo "Erro";
    }  

} 
//excluir endereço
elseif ($_POST['acao']== 'excluir') {
    $id = $_POST['id'];
    $userId =$_POST['userId'];

    $sql = "DELETE FROM formas_pagamento WHERE id = :id AND user_id = :userId";
    $stmt = $connection->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    $stmt->execute();

    if($stmt){
        $_SESSION['msgSucesso'] = 'Forma de pagamento removida com sucesso!';
        header("Location: ../view/perfilUsuario.php");
        exit();
    }
} 
//salvar nova forma de pagamento
elseif ($_POST['acao'] == 'salvar') {
    $userId =$_POST['userId'];
    $nome_titular = $_POST['nome'];
    $nome_cartao = $_POST['cartao'];
    $numero_cartao = $_POST['numero'];
    $validade = $_POST['validade'];
    $cvv = $_POST['cvv'];

    $sql = "INSERT INTO formas_pagamento (user_id, nome_titular, nome_cartao, numero_cartao, validade, cvv) 
            VALUES (:user_id, :nome_titular, :nome_cartao, :numero_cartao, :validade, :cvv)";
    
    $stmt = $connection->prepare($sql);

    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':nome_titular', $nome_titular, PDO::PARAM_STR);
    $stmt->bindParam(':nome_cartao', $nome_cartao, PDO::PARAM_STR);
    $stmt->bindParam(':numero_cartao', $numero_cartao, PDO::PARAM_STR);
    $stmt->bindParam(':validade', $validade, PDO::PARAM_STR);
    $stmt->bindParam(':cvv', $cvv, PDO::PARAM_STR);

    $stmt->execute();

    if($stmt){
        $_SESSION['msgSucesso'] = 'Forma de pagamento cadastrada com sucesso!';
        header("Location: ../view/perfilUsuario.php");
        exit();
    }
}
?>
