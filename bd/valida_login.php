<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/model/Usuario_model.php');
require_once('../bd/dao/usuario_DAO.php');
$conexao = (new Conexao())->conectar();

$cpf = $_POST["cpf"];
$senha = $_POST["senha"];

if (!(empty($cpf) or empty($senha))){ // testa se os campos do formulário não estão vazios

    $parametroLogin = new Usuario();
    $parametroLogin->setCpf($cpf);
    $parametroLogin->setSenha($senha);

    $listaUsuario = new usuario_DAO($conexao);
    $usuario = $listaUsuario->verificaLogin($parametroLogin);
    
	if ($usuario) { // testa se encontrou o usuário
		$_SESSION["logado"] = true; // armazena TRUE na variável de sessão logado
		$_SESSION["cpf"] = $cpf; // armazena na variável de sessão cpf o conteúdo do campo cpf do formulário
        $_SESSION["usuario_id"] = $usuario['id'];
        $user_id = $usuario['id'];
        
        // Verifica se é administrador
        $verificaTipo = new usuario_DAO($conexao);
        $admin = $verificaTipo->verificaUsuarioAdm($user_id);
        
        // Verifica se é vendedor
        $vendedor = $verificaTipo->verificaUsuarioVendedor($user_id);
        
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
		header("Location:../view/login.php"); // volta para o login
		exit;
	}
} else { // caso contrário, se algum campo estiver vazio
	$_SESSION["msg"] = "Preencha campos cpf e senha"; // mensagem de erro
	header("Location:../view/login.php"); // volta para o login
	exit;
}
?>
