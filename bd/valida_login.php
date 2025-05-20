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
        $_SESSION["usuario_id"] = $usuario['id'];
        $user_id = $usuario['id'];

        // Verifica se é administrador
        $sql_admin = "SELECT COUNT(*) FROM administradores WHERE user_id = :id AND status = '1'";
        $stmt_admin = $connection->prepare($sql_admin);
        $stmt_admin->bindParam(':id', $user_id);
        $stmt_admin->execute();
        $admin = $stmt_admin->fetchColumn();

        // Verifica se é vendedor
        $sql_vendedor = "SELECT COUNT(*) FROM vendedores WHERE user_id = :id AND status = '1'";
        $stmt_vendedor = $connection->prepare($sql_vendedor);
        $stmt_vendedor->bindParam(':id', $user_id);
        $stmt_vendedor->execute();
        $vendedor = $stmt_vendedor->fetchColumn();

        // Define o tipo do usuário na sessão
        if ($admin) {
            $_SESSION["tipo_usuario"] = "admin";
        } elseif ($vendedor) {
            $_SESSION["tipo_usuario"] = "vendedor";
        } else {
            $_SESSION["tipo_usuario"] = "usuario";
        }

        header("Location:../index.php"); // direciona para o menu
		exit; // Certifica que a execução do script seja interrompida após o redirecionamento
	} else {
		$_SESSION["msg"] = "Usuário ou senha inválidos"; // mensagem de erro
		echo "<script>alert('Usuário ou senha inválidos!');</script>";
		header("Location:../view/login.html"); // volta para o login
		exit;
	}
} else { // caso contrário, se algum campo estiver vazio
	$_SESSION["msg"] = "Preencha campos cpf e senha"; // mensagem de erro
	echo "<script>alert('Preencha todos os campos');</script>";
	header("Location:../view/login.html"); // volta para o login
	exit;
}
?>
