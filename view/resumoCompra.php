<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:../view/login.html");
    exit;
}

$cpf = $_SESSION['cpf'];
$userId = $_SESSION['usuario_id'];

if (!isset($_SESSION['carrinhotemp'])) {
    die('Carrinho vazio.');
}

$itens = $_SESSION['carrinhotemp'];
$endereco_id = $_POST['endereco_id'] ?? null;
$pagamento_id = $_POST['pagamento_id'] ?? null;

$_SESSION['endereco_id'] = $endereco_id;
$_SESSION['pagamento_id'] = $pagamento_id;

if (!$endereco_id || !$pagamento_id) {
    die('Endereço ou forma de pagamento não selecionado.');
}

// Busca endereço selecionado
$sql_endereco = "SELECT * FROM enderecos WHERE id = :id AND user_id = :userId LIMIT 1";
$stmt_endereco = $connection->prepare($sql_endereco);
$stmt_endereco->bindValue(':id', $endereco_id);
$stmt_endereco->bindValue(':userId', $userId);
$stmt_endereco->execute();
$endereco = $stmt_endereco->fetch(PDO::FETCH_ASSOC);

// Busca forma de pagamento selecionada
$sql_pagamento = "SELECT * FROM formas_pagamento WHERE id = :id AND user_id = :userId LIMIT 1";
$stmt_pagamento = $connection->prepare($sql_pagamento);
$stmt_pagamento->bindValue(':id', $pagamento_id);
$stmt_pagamento->bindValue(':userId', $userId);
$stmt_pagamento->execute();
$pagamento = $stmt_pagamento->fetch(PDO::FETCH_ASSOC);

// busca cpf para setar a imagem do header
if ($cpf) {
    $sql = "SELECT img_user FROM usuarios WHERE cpf = :cpf";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && !empty($usuario['img_user'])) {
        $imagemUsuario = '../img/users/' . $usuario['img_user'];
    }
}

$produtosIds = array_column($itens, 'id');

if (empty($produtosIds)) {
    die('Nenhum produto no carrinho.');
}


$placeholders = implode(',', array_fill(0, count($produtosIds), '?'));

// SQL para buscar os detalhes dos produtos específicos do usuário
$sql_itens = "  SELECT ci.*, p.*, u.id, p.nome, v.*, (SELECT imagem_url FROM produto_imagens WHERE produto_id = p.id ORDER BY ordem ASC LIMIT 1) AS imagem_url
                    FROM carrinho_itens ci  
                    JOIN carrinho c ON c.id = ci.carrinho_id 
                    JOIN produtos p ON p.id = ci.produto_id
                    JOIN vendedores_produtos vp on vp.produto_id = p.id
                    JOIN vendedores v on v.id = vp.vendedor_id
                    JOIN usuarios u ON u.id = c.usuario_id WHERE u.id = ? and p.id IN ($placeholders)";

$parametros = array_merge([$userId], $produtosIds);
$stmt_itens = $connection->prepare($sql_itens);
$stmt_itens->execute($parametros);

$itensFinal = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);


$subtotal = 0;
$frete = 25.00;

foreach ($itensFinal as $item) {
    $subtotal += $item['preco_unitario'] * $item['quantidade'];
}
$total = $subtotal + $frete;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/carrinho.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Resumo da compra | Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="../img/site/icone.png" type="image/x-icon">

</head>

<body>
    <!--CABEÇALHO-->
    <header class="menu">
        <div class="logo">
            <a href="../index.php"> <img src="../img/site/logo.png"></a>
        </div>

        <form action="buscaProdutos.php" method="GET" class="busca-container">
            <input type="text" class="busca-input" id="caixa-pesquisa" name="url" placeholder="Procurar produto ou loja">

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
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
        </ul>
    </header>

    <section class="pagina-carrinho">
        <h2>Resumo da Compra</h2>
        <a href="checkoutCarrinho.php" class="voltar-link">← Voltar</a>

        <div class="resumo-container">
            <!-- itens -->
            <div class="coluna-esquerda">
                <h3>Itens do Pedido</h3>
                <ul class="lista-produtos-resumo">
                    <?php foreach ($itensFinal as $item): ?>

                        <li class="produto-resumo">
                            <img src="../img/produtos/<?php echo $item['imagem_url'] ?>" alt="Produto" />
                            <div>
                                <p><strong><?php echo $item['nome'] ?></strong></p>
                                <p>Tipo:<?php echo $item['marca'] ?></p>
                                <p>Quantidade: <?php echo $item['quantidade'] ?></p>
                                <p>Preço unitário: R$ <?php echo $item['preco_unitario'] ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- valores e endereço  -->
            <div class="coluna-direita">
                <div class="bloco-resumo">
                    <h4>Endereço de Entrega</h4>
                    <p>
                        <?php echo $endereco['rua'] ?>, <?php echo $endereco['numero'] ?><br>
                        <?php echo $endereco['bairro'] ?>, <?php echo $endereco['cidade'] ?><br>
                        CEP: <?php echo $endereco['cep'] ?>
                    </p>
                </div>

                <div class="bloco-resumo">
                    <h4>Forma de Pagamento</h4>
                    <p>
                        <?php echo $pagamento['nome_cartao'] ?>, <?php echo $pagamento['numero_cartao'] ?><br>
                        Titular: <?php echo $pagamento['nome_titular'] ?>
                    </p>
                </div>

                <div class="resumo-total">
                    <p>Subtotal: <?php echo number_format($subtotal, 2, ',', '.'); ?></p>
                    <p>Frete: <?php echo number_format($frete, 2, ',', '.'); ?></p>
                    <br>
                    <p class="total">Total: <strong><?php echo number_format($total, 2, ',', '.'); ?></strong></p>
                </div>

                <form method="POST" action="../bd/pedido.php">
                    <input type="hidden" name="endereco_id" value="<?php echo $endereco_id; ?>">
                    <input type="hidden" name="pagamento_id" value="<?php echo $pagamento_id; ?>">
                    <input type="hidden" name="total" value="<?php echo $total; ?>">

                    <div class="acoes-resumo">
                        <!--TODO habilitar opção-->
                        <button name="acao" value="finalizar" class="botao-confirmar">Confirmar Pedido</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>

<script src="../js/global.js"></script>

</html>