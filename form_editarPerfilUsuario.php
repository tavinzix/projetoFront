<?php
  session_start();
  require_once('config.inc.php');
  ini_set('default_charset', 'utf-8');

  if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:login.php");
    exit;
  }

  

  $cpf = $_SESSION['cpf'];

  $sql = "SELECT * FROM usuarios WHERE cpf = :cpf";
  $stmt = $connection->prepare($sql);
  $stmt->bindParam(':cpf', $cpf);
  $stmt->execute();

  $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($usuario && !empty($usuario['img_user'])) {
    $imagemUsuario = 'img/users/' . ($usuario['img_user']);
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/perfilUsuario.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <title>Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <!--CABEÇALHO-->
    <header class="menu">
        <div class="logo">
            <a href="index.html"> <img src="img/site/logo.png"></a>
        </div>

        <form action="buscar produto do banco" method="GET" class="busca-container">
            <input type="text" class="busca-input" placeholder="Procurar produto ou loja">
            <button type="submit" class="lupa-icone">
                <img src="img/site/lupa.png" id="iconeft">
            </button>
        </form>

        <button class="menu-hamburguer" id="menu-hamburguer">
            &#9776;
        </button>

        <ul class="menu-link" id="menu-link">
            <li><a href="index.html">Início</a></li>
            <li><a href="carrinho.html"><img src="img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
        </ul>
    </header>

    <section class="editar-perfil">
        <h3>Editar Perfil</h3>
        <form class="form-perfil" action="editarPerfilUsuario.php" method="POST" enctype="multipart/form-data">     
            <div class="campo-form" style="display:none">
                <label for="id">id</label>
                <input type="text" id="id" name="id" value="<?php echo $usuario['id'] ?>">
            </div>

            <div class="campo-form">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" value="<?php echo $usuario['nome_completo'] ?>">
            </div>
        
            <div class="campo-form">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?php echo $usuario['email'] ?>">
            </div>
          
            <div class="campo-form">
                <label for="email">Telefone</label>
                <input type="text" id="email" name="telefone" value="<?php echo $usuario['telefone'] ?>">
            </div>
          
            <div class="campo-form">
                <label for="email">CPF</label>
                <input type="text" id="email" name="cpf" value="<?php echo $usuario['cpf'] ?>" disabled>
            </div>

            <!--/*TODO converter data de exibição para DD/MM/YYYY -->
            <div class="campo-form">
                <label for="email">Data de nascimento</label>
                <input type="email" id="email" name="email" value="<?php echo $usuario['dt_nasc'] ?>" disabled>
            </div>
        
            <div class="campo-form">
                <label for="senha-atual">Senha Atual</label>
                <input type="password" id="senha-atual" name="senha_atual">
            </div>
        
            <div class="campo-form">
                <label for="nova-senha">Nova Senha</label>
                <input type="password" id="nova-senha" name="nova_senha">
            </div>
        
            <div class="campo-form">
                <label for="confirmar-senha">Confirmar Nova Senha</label>
                <input type="password" id="confirmar-senha" name="confirmar_senha">
            </div>
          
            <div class="campo-form-foto">
                <label>Foto de Perfil</label>
                
                <div class="container-fotos">
                    <div class="foto-interna">
                        <img src="<?= $imagemUsuario ?>" alt="Perfil">
                        <label for="foto">Foto atual</label>
                    </div>
                    <div class="foto-interna">
                        <label for="foto">Nova foto</label>
                        <input type="file" id="foto" name="novaImagem">
                    </div>
                </div>
            </div>
          </div>
          <button type="submit" class="btn-salvar" name="salvar">Salvar Alterações</button>
        </form>
      </section>
</body>

</html>