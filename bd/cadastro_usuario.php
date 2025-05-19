<?php

require_once('../bd/config.inc.php');

   if (!isset($_POST['botaoEnviar'])) {
      exit;
   }

   $nome = $_POST['nome'];
   $email = $_POST['email'];
   $cpf = $_POST['cpf'];
   $nascimento = $_POST['nascimento'];
   $telefone = $_POST['telefone'];
   $senha = $_POST['senha'];
   $cSenha = $_POST['cSenha'];

   $sql = "INSERT INTO usuarios (nome_completo, email, senha, telefone, dt_nasc, cpf)
         VALUES (:nome, :email, :senha, :telefone, :nascimento, :cpf)";

   $statement = $connection->prepare($sql);

   $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
   $statement->bindParam(':email', $email, PDO::PARAM_STR);
   $statement->bindParam(':senha', $senha, PDO::PARAM_STR);
   $statement->bindParam(':telefone', $telefone, PDO::PARAM_STR);
   $statement->bindParam(':nascimento', $nascimento, PDO::PARAM_STR);
   $statement->bindParam(':cpf', $cpf, PDO::PARAM_STR);

   if ($statement->execute()) {
      echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'login.php';</script>";
      exit;
  } else {
      echo "<script>alert('Erro ao cadastrar!');</script>";
  }
?>