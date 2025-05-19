<?php
    session_start();
    require_once('../bd/config.inc.php');
    ini_set('default_charset', 'utf-8');

    $cpf = $_SESSION['cpf'] ?? null;
    $imagemUsuario = '../img/users/avatar.jpg';

    if ($cpf) {
        $sql = "SELECT img_user FROM usuarios WHERE cpf = :cpf";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && !empty($usuario['img_user'])) {
            $imagemUsuario = '../img/users/' . ($usuario['img_user']);
        }
    }

    //TODO paginar os restantes
    //TODO css proprio
    //TODO editar categoria
    //TODO remover categoria
    $sql = "SELECT * FROM categorias LIMIT 10";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/gerenciarProduto.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!--CABEÇALHO-->
    <header class="menu">
        <div class="logo">
            <a href="../index.php"> <img src="../img/site/logo.png"></a>
        </div>

        <form action="buscar produto do banco" method="GET" class="busca-container">
            <input type="text" class="busca-input" placeholder="Procurar produto ou loja">
            <button type="submit" class="lupa-icone">
                <img src="../img/site/lupa.png" id="iconeft">
            </button>
        </form>

        <button class="menu-hamburguer" id="menu-hamburguer">
            &#9776;
        </button>

        <ul class="menu-link" id="menu-link">
            <li><a href="../view/index.php">Início</a></li>
            <li><a href="carrinho.html"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="../view/perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
        </ul>
    </header>

    <main class="painel-produtos">
        <div class="topo-painel">
            <h2>Categorias</h2>
            <a href="form_cadastrarCategoria.php" class="btn-novo-produto">Nova categoria</a>
        </div>

        <!--Filtros-->
        <div class="filtros">
            <form method="GET" class="filtro-form">
                <input type="text" name="busca" placeholder="Buscar por nome" />

                <button type="submit">Filtrar</button>
            </form>
        </div>

        <!--Lista de categorias-->
        <table class="tabela-produtos">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>URL</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                ?>
                <tr>                 
                    <td><img class="imagem-produto" src="../img/categoria/<?= $categoria['imagem'] ?>" id="imagem-categoria" alt="imagem-categoria"></td>
                    <td><?php echo $categoria['nome']?></td>
                    <td><?php echo $categoria['descricao']?></td>
                    <td><?php echo $categoria['url']?></td>
                    <td><a href="#"><button class="btn-editar">Editar</button></a></td>
                </tr>
                <?php 
                    } 
                ?>
            </tbody>
        </table>
    </main>
    <?php
    if(isset($_SESSION['msgSucesso'])){
        echo '<script>alert("'.$_SESSION['msgSucesso'].'")</script>';
        unset($_SESSION["msgSucesso"]);
    }
?>
</body>
<script src="../js/js.js"></script>
</html>