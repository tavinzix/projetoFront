<?php
    session_start();
    if (isset($_SESSION["msg"])) {
        $mensagem = $_SESSION["msg"];
        echo "<script>alert('$mensagem');</script>";
        unset($_SESSION["msg"]);
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
    <title>Login- Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="../img/site/icone.png" type="image/x-icon">
</head>

<body>
    <div class="login-exterior">
        <!-- imagem da esquerda -->
        <div class="painel-direita">
            <div class="logo-login">
                <img src="../img/site/logo.png" alt="Logo Iconst" />
                <h1>Materiais de construção civil<br>de um jeito que você nunca viu.</h1>
            </div>
        </div>

        <!-- imagem da direita -->
        <div class="painel-esquerda">
            <form class="login-formulario" action="../bd/valida_login.php" method="POST">
                <h2>Bem-vindo de volta!</h2>

                <label for="cpfLabel">CPF</label>
                <input type="text" id="cpf" name="cpf" maxlength="11" minlength="11" placeholder="Digite seu CPF"
                    required />

                <div class="senha-input">
                    <label for="senhaLabel">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required />
                    <span class="mostrar-senha" id="mostrar-senha">
                        <img src="../img/site/olhoAberto.png"></img>
                    </span>
                </div>

                <button type="submit">Entrar</button>
                
                <!-- opcoes -->
                <div class="links">
                    <a href="recuperarSenha.html">Esqueceu a senha?</a>
                    <a href="form_criarContaUsuario.html">Criar nova conta</a>
                </div>
            </form>
        </div>
    </div>

    
    <script src="../js/global.js"></script>
    <script src="../js/formulario.js"></script>
    <!-- <script src="../js/validacao.js"></script> -->
</body>

</html>