<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:../view/login.html");
}

$cpf = $_SESSION['cpf'] ?? null;
$userId = $_SESSION['usuario_id'];

if ($cpf) {
    $sql = "SELECT img_user FROM usuarios WHERE cpf = :cpf";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && !empty($usuario['img_user'])) {
        $imagemUsuario = '../img/users/' . ($usuario['img_user']);
    }

    //itens
    $sql_itens = "SELECT ci.*, p.*, u.id, p.nome, v.*, (SELECT imagem_url FROM produto_imagens WHERE produto_id = p.id ORDER BY ordem ASC LIMIT 1) AS imagem_url
                    FROM carrinho_itens ci  
                    JOIN carrinho c ON c.id = ci.carrinho_id 
                    JOIN produtos p ON p.id = ci.produto_id
                    JOIN vendedores_produtos vp on vp.produto_id = p.id
                    JOIN vendedores v on v.id = vp.vendedor_id
                    JOIN usuarios u ON u.id = c.usuario_id 
                    WHERE u.id = :userId";

    $stmt_itens = $connection->prepare($sql_itens);
    $stmt_itens->bindValue(':userId', $userId);
    $stmt_itens->execute();

    //resumo da compra
    $sql_valor = "SELECT SUM(preco_unitario * quantidade) AS total_compra FROM carrinho_itens ci JOIN carrinho c ON ci.carrinho_id = c.id
        JOIN usuarios u ON u.id = c.usuario_id WHERE u.id = :userId";

    $stmt_total = $connection->prepare($sql_valor);
    $stmt_total->bindValue(':userId', $userId);
    $stmt_total->execute();

    $usuario = $stmt_total->fetch(PDO::FETCH_ASSOC);
    $total = $usuario['total_compra'];
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/carrinho.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Carrinho | Iconst</title>
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
            <li><a href="carrinho.html"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a>
            </li>
        </ul>
    </header>

    <section class="pagina-carrinho">
        <h2>Meu Carrinho</h2>
        <div class="carrinho-exterior">
            <ul class="lista-carrinho">
                <?php
                while ($carrinho = $stmt_itens->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <li class="item-carrinho">
                        <button class="remover-item" title="Remover do carrinho" onclick="removerItem(<?= $carrinho['produto_id'] ?>)">X</button>
                        <input type="checkbox" class="selecionar-item">
                        <img src="../img/produtos/<?php echo $carrinho['imagem_url'] ?>" alt="<?php echo $carrinho['nome'] ?>" class="imagem-produto">
                        <div class="conteudo-item">
                            <div class="detalhes-item">
                                <h4><?php echo $carrinho['nome'] ?></h4>
                                <p>Vendido por: <a href="loja.html"><strong><?php echo $carrinho['nome_loja'] ?></strong></a></p>
                                <ul class="especificacoes">
                                    <li><?php echo $carrinho['marca'] ?></li>
                                </ul>
                                <p class="preco">R$<?php echo $carrinho['preco_unitario'] ?></p>
                            </div>
                            <div class="controle-quantidade">
                                <label for="quantidade">Quantidade:</label>
                                <div class="quantidade-container">
                                    <button onclick="alterarQuantidadeCarrinho(this, -1)">-</button>
                                    <input id="quantidade-item-<?php echo $carrinho['produto_id'] ?>" type="number" value="<?= $carrinho['quantidade'] ?>" min="1">
                                    <button onclick="alterarQuantidadeCarrinho(this, 1)">+</button>
                                </div>
                                <button class="altera-qtd" onclick="atualizarQuantidade(<?php echo $carrinho['produto_id'] ?>)">Atualizar quantidade</button>
                            </div>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>

            <div class="janela-resumo">
                <div class="resumo-compra">
                    <h3>Resumo da Compra</h3>
                    <!--TODO lógica para contar quantidade e calcular valor -->
                    <p>Itens Selecionados: 1</p>
                    <p>Total dos itens: <strong>R$ <?php echo $total ?></strong></p>
                    <a href="checkoutCarrinho.html"><button class="btn-finalizar">Finalizar Compra</button></a>
                </div>
            </div>
        </div>
    </section>
</body>

<script src="../js/global.js"></script>
<script src="../js/carrinho.js"></script>

</html>