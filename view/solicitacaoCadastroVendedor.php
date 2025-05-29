<?php
    session_start();
    require_once('../bd/config.inc.php');
    $user_id = $_SESSION["usuario_id"];

    // buscar se há solicitacao pendente 
    $sql_pendente = "SELECT COUNT(*) from solicitacoes_vendedor where user_id = :user_id and status = '1'";
    $stmt_pendente = $connection->prepare($sql_pendente);
    $stmt_pendente->bindParam(':user_id', $user_id);
    $stmt_pendente->execute();
    $pendente =  $stmt_pendente->fetchColumn();
    
    // buscar se há solicitação rejeitada 
    $sql_recusado = "SELECT motivo_rejeicao from solicitacoes_vendedor where user_id = :user_id and status = '2'";
    $stmt_recusado = $connection->prepare($sql_recusado);
    $stmt_recusado->bindParam(':user_id', $user_id);
    $stmt_recusado->execute();
    $recusado =  $stmt_recusado->fetchColumn();
    
    //redireciona caso tenha solicitação pendente
    if($pendente){
        header("Location:../view/solicitacaoVendedorPendente.html");
        exit();
    }
    
    // redireciona caso tenha solicitação recusada
    if($recusado){
        $_SESSION['msgRecusado'] = $recusado;
        header("Location:../view/solicitacaoVendedorRecusado.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title> Solicitação para ser vendedor | Iconst </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- formulário de solicitação -->
    <section class="formulario-solicitacao-vendedor">
        <h2>Solicitação de cadastro para vender na plataforma </h2>
        <form action="../bd/solicitacao_vendedor.php" method="post" id="form">
            <label for="user_id" style="display:none">User ID</label>
            <input type="text" id="user_id" name="user_id" style="display:none" value="<?php echo $user_id ?>">

        <form action="../bd/solicitacao_vendedor.php" method="post">
            <label for="nome_loja">Nome da Loja:</label>
            <input type="text" id="nome_loja" name="nome_loja" required>

            <label for="cnpj">CNPJ:</label>
            <input type="text" id="cnpj" name="cnpj" required>

            <label for="email">E-mail de Contato:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" required>

            <label for="cep">CEP:</label>
            <input type="text" id="cep" name="cep" required>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" required>

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" required>

            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" required>

            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" required>

            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero" required>

            <label for="categoria">Categoria Principal</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione</option>
                <option value="materiais">Construção Civil</option>
                <option value="materiais">Materiais de Construção</option>
                <option value="ferramentas">Ferramentas</option>
                <option value="maquinas">Máquinas e Equipamentos</option>
            </select>

            <label for="descricao">Descrição da Loja:</label>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>
            <button type="submit" name="acao" value="cadastrar">Enviar Solicitação</button>
        </form>

        <div class="links">
            <a href="perfilUsuario.php">←Voltar</a>
        </div>

    </section>

    <?php
        if(isset($_SESSION['msgSucesso'])){
            echo '<script>alert("'.$_SESSION['msgSucesso'].'")</script>';
            unset($_SESSION["msgSucesso"]);
        }
    ?>

</body>

<script src="../js/global.js"></script>

</html>