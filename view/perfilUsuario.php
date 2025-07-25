<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/dao/usuario_DAO.php');
require_once('../bd/dao/pedido_DAO.php');

$conexao = (new Conexao())->conectar();

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:login.php");
    exit;
}

$cpf = $_SESSION['cpf'] ?? null;
$userId = $_SESSION['usuario_id'];
$imagemUsuario = '../img/users/avatar.jpg';

// busca cpf para setar a imagem do header
if ($cpf) {
    $listaUsuario = new usuario_DAO($conexao);
    $usuario = $listaUsuario->buscaUsuario($cpf);

    if ($usuario && !empty($usuario['img_user'])) {
        $imagemUsuario = '../img/users/' . ($usuario['img_user']);
    }
}

$listaPedidos = new pedido_DAO($conexao);
$pedido = $listaPedidos->buscaPedidosComStatus($userId);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/perfilUsuario.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Perfil do usuário | Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
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
                <img src="../img/site/lupa.png" id="iconeft">
            </button>
        </form>

        <button class="menu-hamburguer" id="menu-hamburguer">
            &#9776;
        </button>

        <ul class="menu-link" id="menu-link">
            <li><a href="index.html">Início</a></li>
            <?php if ($_SESSION['tipo_usuario'] == 'admin') { ?>
                <li><a href="painelAdm.php">Painel Administrativo</a></li><?php } ?>
            <?php if ($_SESSION['tipo_usuario'] == 'vendedor') { ?>
                <li><a href="painelVendedor.php">Painel do vendedor</a></li><?php } ?>
            <?php if ($_SESSION['tipo_usuario'] == 'usuario') { ?>
                <li><a href="solicitacaoCadastroVendedor.php">Quero vender na plataforma</a></li>
            <?php } ?>          
            <li><a href="carrinho.php"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
        </ul>
    </header>

    <main class="pagina-usuario">
        <!-- perfil do usuario -->
        <section class="perfil-usuario">
            <img src="<?= $imagemUsuario ?>" class="foto-usuario" alt="Foto do usuário">
            <div class="info-usuario">
                <h2><?php echo $usuario['nome_completo'] ?></h2>
                <p><?php echo $usuario['email'] ?></p>
                <p><?php echo $usuario['telefone'] ?></p>
                <a href="form_editarPerfilUsuario.php"> <button class="btn-edicao">Editar perfil</button></a>
                <a href="form_editarEnderecoUsuario.php"> <button class="btn-edicao">Editar endereços</button></a>
                <a href="form_editarFormaPagamentoUsuario.php"> <button class="btn-edicao">Formas de pagamento</button></a>
                <a href="../bd/sair.php"> <button class="btn-edicao">Finalizar sessão</button></a>
            </div>
        </section>

        <!--/*TODO conectar com o banco-->
        <section class="historico-compras">
            <h3>Histórico de Pedidos</h3>

            <ul class="filtros-pedidos">
                <li class="aba ativa" onclick="filtrarPedidos('todos')">Todos</li>
                <li class="aba" onclick="filtrarPedidos('a-caminho')">A caminho</li>
                <li class="aba" onclick="filtrarPedidos('enviado')">Enviados</li>
                <li class="aba" onclick="filtrarPedidos('aguardando-envio')">Aguardando envio</li>
                <li class="aba" onclick="filtrarPedidos('aguardando-pagamento')">Aguardando pagamento</li>
                <li class="aba" onclick="filtrarPedidos('entregue')">Entregues</li>
                <li class="aba" onclick="filtrarPedidos('reembolsado')">Reembolsado</li>
                <li class="aba" onclick="filtrarPedidos('cancelado')">Cancelados</li>
                <li class="aba" onclick="filtrarPedidos('estornado')">Estornados</li>
                <li class="aba" onclick="filtrarPedidos('devolvido')">Devolvido</li>
            </ul>

            <ul class="lista-pedidos">
                <?php
                foreach($pedido as $pedidos): 
                ?>
                    <li class="pedido">
                        <img src="../img/produtos/<?php echo $pedidos['imagem_url'] ?>" alt="Produto" class="foto-produto">
                        <div class="info-pedido">
                            <h4><?php echo $pedidos['nome'] ?></h4>
                            <p>Data: <?php echo date('d/m/Y', strtotime($pedidos['data_pedido'])) ?></p>
                            <p>Status: <span class="status <?php echo strtolower(str_replace(' ', '-', $pedidos['status_texto'])) ?>"><?php echo $pedidos['status_texto'] ?></span></p>
                            <p>Valor: R$ <?php echo number_format(($pedidos['preco_unitario']*$pedidos['quantidade']), 2, ',', '.') ?></p>
                            <button class="btn-detalhes">Ver Detalhes</button>
                        </div>
                    <?php endforeach; ?>
                    </li>
            </ul>
        </section>
    </main>

    <!--/*TODO mostrar mensagem depois de carregar a pagina-->
    <?php
    if (isset($_SESSION['msgSucesso'])) {
        echo '<script>alert("' . $_SESSION['msgSucesso'] . '")</script>';
        unset($_SESSION["msgSucesso"]);
    }
    ?>
</body>

<script src="../js/global.js"></script>
<script src="../js/perfilUsuario.js"></script>

</html>