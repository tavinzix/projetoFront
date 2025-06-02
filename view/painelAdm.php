<?php
    session_start();
    require_once('../bd/config.inc.php');
    ini_set('default_charset', 'utf-8');

    $cpf = $_SESSION['cpf'] ?? null;
    $imagemUsuario = '..img/users/avatar.jpg';

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
    <link rel="stylesheet" href="../css/painelAdm.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Painel Administrativo | ICONST</title>
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
            <input type="text" class="busca-input" placeholder="Procurar produto ou loja">
            <button type="submit" class="lupa-icone">
                <img src="../img/site/lupa.png" id="iconeft">
            </button>
        </form>

        <button class="menu-hamburguer" id="menu-hamburguer">
            &#9776;
        </button>

        <ul class="menu-link" id="menu-link">
            <li><a href="../index.php">Início</a></li>
            <li><a href="carrinho.html"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
        </ul>
    </header>

    <main class="painelAdm-exterior">
        <!-- menu  -->
        <nav class="opcoes-menu">
            <h3>Menu</h3>
            <ul>
                <li><a href="categorias.php">Categorias</a></li>
                <li><a href="solicitacaoPendente.php">Solicitações de vendedores</a></li>
                <li><a href="#">Solicitações de reembolso</a></li>
                <li><a href="#">Vendedores</a></li>
                <li><a href="#">Usuários</a></li>
            </ul>
        </nav>

        <!-- grafico e informações  -->
        <main class="info-plataforma">
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
<script src="../js/painelAdm.js"></script>

</html>