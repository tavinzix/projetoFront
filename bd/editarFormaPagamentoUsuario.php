<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');


//editar endereço do usuario
if ($_POST['acao'] == 'editar') {
    $id = $_POST['id'];
    $userId =$_POST['userId'];
    $nome_titular = $_POST['nome_titular'];
    $nome_cartao = $_POST['nome_cartao'];
    $numero_cartao = $_POST['numero_cartao'];
    $validade = $_POST['$validade'];
    $cvv = $_POST['cvv'];

    $sql = "UPDATE formas_pagamento SET nome_titular = :nome_titular, nome_cartao = :nome_cartao, numero_cartao = :numero_cartao, validade = :validade, cvv = :cvv
            WHERE id = :id AND user_id = :userId";
    
    $stmt = $connection->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':nome_titular', $nome_titular, PDO::PARAM_STR);
    $stmt->bindParam(':nome_cartao', $nome_cartao, PDO::PARAM_STR);
    $stmt->bindParam(':numero_cartao', $numero_cartao, PDO::PARAM_STR);
    $stmt->bindParam(':validade', $validade, PDO::PARAM_STR);
    $stmt->bindParam(':cvv', $cvv, PDO::PARAM_STR);

    $stmt->execute();

    if($stmt){
        $_SESSION['msgSucesso'] = 'Forma de pagamaneto atualizada com sucesso!';
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
