<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:../view/login.html");
}

$cpf = $_SESSION['cpf'] ?? null;
$userId = $_SESSION['usuario_id'];

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

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/perfilLoja.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Painel do vendedor | Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <li><a href="carrinho.php"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a>
            </li>
        </ul>
    </header>

    <div class="loja-info">
        <img src="../img/lojas/l1.jpg" alt="Logo da Loja" />
        <div class="loja-dados">
            <h2>Loja do vendedor</h2>
            <p>Localizada em Pelotas, RS<br> Avaliação: 4.8 ★<br>Vendedor desde 2025</p>
        </div>
    </div>

    <!-- menu -->
    <main class="perfilLoja-exterior">
        <nav class="opcoes-loja">
            <h3>Menu</h3>
            <ul>
                <li><a href="gerenciarProduto.php">Gerenciar produtos</a></li>
                <li><a href="#">Cadastrar formas de entrega</a></li>
                <li><a href="#">Cadastrar ofertas</a></li>
                <li><a href="#">Editar perfil da loja</a></li>
                <li><a href="#">Gerenciar pedidos</a></li>
            </ul>
        </nav>

         <!-- grafico e informações  -->
        <main class="info-vendas">
            <section class="metricas">
                <div class="card">
                    <h3>Total de Vendas</h3>
                    <p><strong>R$ 10.000,00</strong></p>
                </div>
                <div class="card">
                    <h3>Pedidos do Mês</h3>
                    <p><strong>80</strong></p>
                </div>
                <div class="card">
                    <h3>Produtos Ativos</h3>
                    <p><strong>15</strong></p>
                </div>
            </section>

            <section>
                <h3>Gráfico de Vendas (Últimos 6 meses)</h3>
                <canvas id="graficoVendas" height="100"></canvas>
            </section>
        </main>
    </main>
</body>

<script src="../js/global.js"></script>
<script src="../js/grafico.js"></script>

</html>