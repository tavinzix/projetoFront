<?php
session_start();

$cpf = $_POST["cpf"];
$senha = $_POST["senha"];

if (!(empty($cpf) or empty($senha))) // testa se os campos do formulário não estão vazios
{
	require_once('config.inc.php');
	$sql = "SELECT * from usuarios WHERE cpf = :cpf and senha = :senha";
	$statement = $connection->prepare($sql);
	$statement->bindParam(':cpf', $cpf);
	$statement->bindParam(':senha', $senha);
	$statement->execute();

	$usuario = $statement->fetch(PDO::FETCH_ASSOC);
	if ($usuario) { // testa se encontrou o usuário
		$_SESSION["logado"] = true; // armazena TRUE na variável de sessão logado
		$_SESSION["cpf"] = $cpf; // armazena na variável de sessão cpf o conteúdo do campo cpf do formulário
		header("Location:index.php"); // direciona para o menu
		exit; // Certifica que a execução do script seja interrompida após o redirecionamento
	} else {
		$_SESSION["msg"] = "Usuário ou senha inválidos"; // mensagem de erro
		header("Location:login.php"); // volta para o login
		exit;
	}
} else { // caso contrário, se algum campo estiver vazio
	$_SESSION["msg"] = "Preencha campos cpf e senha"; // mensagem de erro
	header("Location:login.php"); // volta para o login
	exit;
}
?>
