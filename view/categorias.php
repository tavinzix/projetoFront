<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');

$cpf = $_SESSION['cpf'] ?? null;
$imagemUsuario = '../img/users/avatar.jpg';

// busca cpf para setar a imagem do header
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

//TODO paginar depois de exibir 10

// busca as categorias cadastradas
$sql = "SELECT *, CASE WHEN status = '1' then 'Ativo' 
            WHEN status = '2' then 'Inativo' end AS status_texto FROM categorias";
$stmt = $connection->prepare($sql);
$stmt->execute();

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/painelAdm.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Categorias | Iconst</title>
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
            <input type="text" class="busca-input" id="caixa-pesquisa" placeholder="Procurar produto ou loja">

            <button type="button" id="microfone" onclick="buscaAudio()">
                <img src="../img/site/microfone.png" id="iconeft" alt="Microfone">
            </button>

            <button type="submit" class="lupa-icone">
                <img src="../img/site/lupa.png" id="iconeft" alt="Lupa">
            </button>
        </form>

        <button class="menu-hamburguer" id="menu-hamburguer">
            &#9776;
        </button>

        <ul class="menu-link" id="menu-link">
            <li><a href="../index.php">Início</a></li>
            <li><a href="carrinho.php"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a>
            </li>
        </ul>
    </header>

    <main class="categorias-exterior">
        <div class="topo-painel">
            <h2>Categorias</h2>
            <a href="form_cadastrarCategoria.html" class="btn-nova-categoria">Nova categoria</a>
        </div>

        <!--Filtros-->
        <div class="filtros">
            <form method="GET" class="filtro-form">
                <input type="text" name="busca" placeholder="Buscar por nome" />

                <button type="submit">Filtrar</button>
            </form>
        </div>

        <!--Lista de categorias-->
        <table class="tabela-categoria">
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
                        <td><?php echo $categoria['nome'] ?></td>
                        <td><?php echo $categoria['descricao'] ?></td>
                        <td><?php echo $categoria['url'] ?></td>
                        <td><span class="tag <?php echo $categoria['status_texto'] ?>"><?php echo $categoria['status_texto'] ?></span></td>

                        <!-- abre modal com todas as informações da loja -->
                        <td><a onclick='abrirJanelaCategoria(<?php echo json_encode($categoria) ?>)'><button class="btn-editar">Editar</button></a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </main>

    <div id="janela-categoria" class="janela-categoria">
        <!-- detalhes da categoria -->
        <div class="janela-conteudo-categoria">
            <span onclick="fecharJanelaCategoria()">&#10005;</span>
            <h2>Detalhes da categoria</h2>

            <form action="../bd/cadastro_categoria.php" method="post" enctype="multipart/form-data" id="formularioCategoria">
                <div class="informacao-categoria" style="display:none"> 
                    <strong>Id da categoria:</strong>
                    <p id="id_categoria" name="id_categoria"></p>
                </div>

                <div class="informacao-categoria">
                    <strong>Nome da categoria:</strong> <br>
                    <input id="nome" name="nome">
                </div>

                <div class="informacao-categoria">
                    <strong>Descrição:</strong><br>
                    <textarea id="descricao" name="descricao"></textarea>
                </div>

                <div class="informacao-categoria">
                    <strong>URL:</strong><br>
                    <input id="url" name="url">
                </div>

                <div class="informacao-categoria">
                    <strong>Imagem atual:</strong><br>
                    <img id="imagemAtual" style="max-width: 200px;"><br>

                    <strong>Alterar imagem:</strong><br>
                    <input type="file" id="imagem" name="novaImagem">
                </div>

                <!-- editar, inativar ou ativar categoria -->
                <button onclick="editar()" class="btn-editar" type="button" id="editarBtn">Editar</button>
                <button onclick="inativar()" class="btn-editar" type="button" id="inativarBtn" style="display: none;">Inativar</button>
                <button onclick="ativar()" class="btn-editar" type="button" id="ativarBtn" style="display: none;">Ativar</button>
            </form>
        </div>
    </div>

    <?php
    if (isset($_SESSION['msgSucesso'])) {
        echo '<script>alert("' . $_SESSION['msgSucesso'] . '")</script>';
        unset($_SESSION["msgSucesso"]);
    }
    ?>
</body>
<script src="../js/global.js"></script>
<script src="../js/painelAdm.js"></script>

</html>