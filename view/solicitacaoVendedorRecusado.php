<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/dao/usuario_DAO.php');
require_once('../bd/dao/solicitacao_DAO.php');
$conexao = (new Conexao())->conectar();

$cpf = $_SESSION['cpf'];
$userId = $_SESSION['usuario_id'];

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:login.html");
    exit;
}

$listaSolicitacaoRejeitada = new solicitacao_DAO($conexao);
$solicitacoes = $listaSolicitacaoRejeitada->buscaSolicitacaoRecusadaPorUsuario($userId);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Pedido recusado | Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="../img/site/icone.png" type="image/x-icon">

</head>

<body>
    <section class="formulario-solicitacao-vendedor">
        <h2>Sua solicitação foi negada. <br><br>Você não poderá vender na plataforma</h2>
        <br><br>
        <p style="text-align: center;">Motivo da rejeição:<br>
          <?php echo $solicitacoes['motivo_rejeicao'] ?>
        </p>
        <div class="links">
            <a href="perfilUsuario.php">←Voltar</a>
        </div>
    </section>
</body>


</html>