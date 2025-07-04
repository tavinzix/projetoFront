<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/dao/usuario_DAO.php');
require_once('../bd/dao/produto_DAO.php');
require_once('../bd/dao/vendedor_DAO.php');
$conexao = (new Conexao())->conectar();

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:login.php");
    exit;
}

$cpf = $_SESSION['cpf'];
$userId = $_SESSION['usuario_id'];
$imagemUsuario = '../img/users/avatar.jpg';

// busca o usuario para setar a imagem no header
$listaUsuario = new usuario_DAO($conexao);
$usuario = $listaUsuario->buscaUsuario($cpf);

if ($usuario && !empty($usuario['img_user'])) {
    $imagemUsuario = '../img/users/' . ($usuario['img_user']);
}

// busca os dados do vendedor com base no id do usuario 
$listaVendedor = new vendedor_DAO($conexao);
$vendedor = $listaVendedor->buscaVendedorPorIdUser($userId);
$vendedor_id = $vendedor['id'];

$listaProdutosVendedor = new produto_DAO($conexao);
$produtosVendedor = $listaProdutosVendedor->buscaProdutosPorVendedor($vendedor_id);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/gerenciarProduto.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Gerenciar produto | Iconst</title>
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

    <main class="painel-produtos">
        <div class="topo-painel">
            <h2>Meus Produtos</h2>
            <a href="form_cadastrarProduto.php" class="btn-novo-produto">Novo Produto</a>
        </div>

        <!--Filtros-->
        <div class="filtros">
            <form method="GET" class="filtro-form">
                <input type="text" name="busca" placeholder="Buscar por nome do produto" />

                <select name="status">
                    <option value="">Status</option>
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                </select>

                <select name="estoque">
                    <option value="">Estoque</option>
                    <option value="com">Com estoque</option>
                    <option value="sem">Sem estoque</option>
                </select>

                <button type="submit">Filtrar</button>
            </form>
        </div>
        <!--TODO deixar responsivo-->
        <!-- tabela com os produtos do vendedor  -->
        <table class="tabela-produtos">
            <thead>
                <tr>
                    <th>Imagem</th>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verifica se há produtos para o vendedor
                if ($produtosVendedor->rowCount() !== 0) {
                    foreach ($produtosVendedor as $produto):
                ?>
                    <tr>
                        <td><img src="../img/produtos/<?= $produto['imagem_url'] ?>" class="imagem-produto" /></td>
                        <td><?php echo $produto['nome'] ?></td>
                        <td><?php echo $produto['preco'] ?></td>
                        <td><?php echo $produto['estoque'] ?></td>
                        <td><span class="tag <?php echo $produto['status_texto'] ?>"><?php echo $produto['status_texto'] ?></span></td>
                        <td>
                            <!-- TODO criar funcionalidade -->
                            <a href="editarProduto.html"><button class="btn-editar">Editar</button></a>
                        </td>
                    </tr>
                <?php  endforeach ; }else{

                ?>
                    <tr>
                        <td colspan="6" class="sem-produtos">Nenhum produto encontrado.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>

<script src="../js/global.js"></script>

</html>