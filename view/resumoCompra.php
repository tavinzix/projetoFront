<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/dao/usuario_DAO.php');
require_once('../bd/dao/pedido_DAO.php');
require_once('../bd/dao/enderecoUsuario_DAO.php');
require_once('../bd/dao/formaPagamentoUsuario_DAO.php');
require_once('../bd/dao/carrinho_DAO.php');
$conexao = (new Conexao())->conectar();

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:../view/login.php");
    exit;
}

$cpf = $_SESSION['cpf'];
$userId = $_SESSION['usuario_id'];

if (!isset($_SESSION['carrinhotemp'])) {
    die('Carrinho vazio.');
}

//busca informação dos itens, endereço e forma de pagamento
$itens = $_SESSION['carrinhotemp'];
$endereco_id = $_POST['endereco_id'] ?? null;
$pagamento_id = $_POST['pagamento_id'] ?? null;

if (!$endereco_id || !$pagamento_id) {
    die('Endereço ou forma de pagamento não selecionado.');
}

// Busca endereço selecionado
$listaEndereco = new enderecoUsuario_DAO($conexao);
$endereco = $listaEndereco->listarEnderecoPorId($endereco_id, $userId);

// Busca forma de pagamento selecionada
$listaPagamento = new formaPagamentoUsuario_DAO($conexao);
$pagamento = $listaPagamento->listarFormaPorId($pagamento_id, $userId);

// busca cpf para setar a imagem do header
if ($cpf) {
    $listaUsuario = new usuario_DAO($conexao);
    $usuario = $listaUsuario->buscaUsuario($cpf);

    if ($usuario && !empty($usuario['img_user'])) {
        $imagemUsuario = '../img/users/' . ($usuario['img_user']);
    }
}

// Extrai somente os ids dos produtos do array
$produtosIds = array_column($itens, 'id');

if (empty($produtosIds)) {
    die('Nenhum produto no carrinho.');
}

//cria placeholders para consulta (?,?)
$placeholders = implode(',', array_fill(0, count($produtosIds), '?'));

//prepara os parametros para a query, userId e o id dos produtos
$parametros = array_merge([$userId], $produtosIds);
$listaItensCarrinho = new carrinho_DAO($conexao);
$itensFinal = $listaItensCarrinho->detalhesItensCarrinho($parametros, $placeholders);

//calcula o valor total
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

                <form method="POST" action="../bd/controller/Pedido_controller.php">
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