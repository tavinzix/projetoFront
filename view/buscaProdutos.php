<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');

// busca o usuario para setar a imagem no header
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

// pega a url e busca os itens
if (isset($_GET['url'])) {
    //trata caracter especial como ' 
    $pesquisa = addslashes($_GET['url']);
    $pesquisa = '%' . $pesquisa . '%';

    //busca os produtos
    //TODO tratar acentos
    $sql_produto = "SELECT p.*, vp.*, pi.*, p.id as produto_id FROM produtos p
                JOIN vendedores_produtos vp ON vp.produto_id = p.id
                JOIN vendedores v on vp.vendedor_id = v.id
                LEFT JOIN produto_imagens pi ON p.id = pi.produto_id AND pi.ordem = 1
                WHERE p.nome ILIKE :pesquisa or v.nome_loja ILIKE :pesquisa";

    $stmt_produto = $connection->prepare($sql_produto);
    $stmt_produto->bindParam(':pesquisa', $pesquisa,  PDO::PARAM_STR);
    $stmt_produto->execute();

    //implementar busca pela loja
    //SELECT v.*, vi.* FROM vendedores v JOIN vendedor_imagens vi ON vi.vendedor_id = v.id WHERE v.nome_loja ILIKE '%loja%'

    if ($stmt_produto->rowCount() === 0) {
        //TODO mesma página porém alertando que não encontrou o item
        //header("Location:../view/paginaNaoEncontrada.html");
        exit();
    }
} else {
    //header("Location:../view/paginaNaoEncontrada.html");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/produtoCategoria.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Itens da categoria | Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="../img/site/icone.png" type="image/x-icon">
</head>

<body>
    <!--CABEÇALHO-->
    <header class="menu">
        <div class="logo">
            <a href="../index.php"> <img src="../img/site/logo.png"></a>
        </div>

        <form action="buscaProdutos.php" method="GET" class="busca-container" id="buscaProduto">
            <input type="text" class="busca-input" id="caixa-pesquisa" name="url" placeholder="Procurar produto ou loja">

            <button type="button" id="microfone" onclick="buscaAudio()">
                <img src="../img/site/microfone.png" id="iconeft" alt="Microfone">
            </button>

            <button type="submit" class="lupa-icone">
                <img src="../img/site/lupa.png" id="iconeft">
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

    <div class="titulo-categoria">
        <h1>Você buscou por: <?php echo $_GET['url'] ?></h1>
    </div>

    <main class="layout-itensCategoria">
        <!--Filtros-->
        <aside class="filtro-lateral">
            <h2>Filtros</h2>

            <!-- Ordenação -->
            <div class="itens-filtros">
                <label for="ordenar">Buscar por:</label>
                <select id="ordenar" name="ordenar">
                    <option value="produtos">Produtos</option>
                    <option value="lojas">Lojas</option>
                    <option value="ambos">Ambos</option>
                </select>
            </div>

            <!--Preço -->
            <div class="itens-filtros">
                <label for="valor-min">Valor Mínimo:</label>
                <input type="number" id="valor-min" name="valor-min" placeholder="R$ 0,00">

                <label for="valor-max">Valor Máximo:</label>
                <input type="number" id="valor-max" name="valor-max" placeholder="R$ 1.000,00">
            </div>

            <!-- Avaliação -->
            <div class="itens-filtros">
                <label>Avaliação mínima:</label>
                <div>
                    <input type="radio" name="avaliacao" value="4" id="estrelas-4">
                    <label for="estrelas-4">4 estrelas ou mais</label>
                </div>
                <div>
                    <input type="radio" name="avaliacao" value="3" id="estrelas-3">
                    <label for="estrelas-3">3 estrelas ou mais</label>
                </div>
                <div>
                    <input type="radio" name="avaliacao" value="2" id="estrelas-2">
                    <label for="estrelas-2">2 estrelas ou mais</label>
                </div>
            </div>

            <!-- Ordenação -->
            <div class="itens-filtros">
                <label for="ordenar">Ordenar por:</label>
                <select id="ordenar" name="ordenar">
                    <option value="relevancia">Mais Vendidos</option>
                    <option value="preco_asc">Menor preço</option>
                    <option value="preco_desc">Maior preço</option>
                    <option value="avaliacao">Melhor avaliados</option>
                </select>
            </div>

            <!-- Localização -->
            <div class="itens-filtros">
                <label for="localizacao">Localização:</label>
                <input type="text" id="localizacao" name="localizacao" placeholder="Cidade">
            </div>

            <!-- Estoque -->
            <div class="itens-filtros">
                <input type="checkbox" id="disponivel" name="disponivel">
                <label for="disponivel">Somente produtos em estoque</label>
            </div>

            <button class="btn-filtrar">Aplicar Filtros</button>
        </aside>

        <!--Itens-->
        <section class="itensCategoria-exterior">
            <?php
            while ($itemEncontrado = $stmt_produto->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="produto-card">
                    <img src="../img/produtos/<?php echo $itemEncontrado['imagem_url'] ?>" alt="<?php echo $itemEncontrado['nome'] ?>">
                    <p style="display:none">Id produto<?php echo $itemEncontrado['produto_id'] ?></p>
                    <h3><?php echo $itemEncontrado['nome'] ?></h3>
                    <p>R$<?php echo $itemEncontrado['preco'] ?></p>
                    <a href="produto.php?id=<?php echo $itemEncontrado['produto_id'] ?>">
                        <button>Ver Detalhes</button>
                    </a>
                </div>
            <?php
            }
            ?>

        </section>
    </main>
    <script src="../js/global.js"></script>
</body>

</html>