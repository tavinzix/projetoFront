<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/dao/usuario_DAO.php');
require_once('../bd/dao/produto_DAO.php');
require_once('../bd/dao/vendedor_DAO.php');
$conexao = (new Conexao())->conectar();

$cpf = $_SESSION['cpf'] ?? null;
$imagemUsuario = '../img/users/avatar.jpg';

// busca a imagem para setar no header 
if ($cpf) {
    $listaUsuario = new usuario_DAO($conexao);
    $usuario = $listaUsuario->buscaUsuario($cpf);

    if ($usuario && !empty($usuario['img_user'])) {
        $imagemUsuario = '../img/users/' . ($usuario['img_user']);
    }

    if (isset($_SESSION["msg"])) {
        $mensagem = $_SESSION["msg"];
        echo "<script>alert('$mensagem');</script>";
        unset($_SESSION["msg"]);
    }
}

// pega o id do produto da url e busca os dados
if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    $listaProduto = new produto_DAO($conexao);
    $produto = $listaProduto->buscarProdutoPorId($produto_id);
    $imagensProduto = $listaProduto->buscarImagensProduto($produto_id);

    // define a galeria de imagens do produto 
    $imagemPrincipal = null;
    $miniaturas = [];

    // percorre todas as imagens até encontrar a com ordem 1 para definir como a imagem principal 
    foreach ($imagensProduto as $img) {
        if ($img['ordem'] == 1) {
            $imagemPrincipal = $img;
            break;
        }
    }

    $vendedorId = $produto['vendedor_id'];
    $listaVendedor = new vendedor_DAO($conexao);
    $imagemVendedor = $listaVendedor->buscarVendedorPorId($vendedorId);

    // se não encontrar o id do produto, direciona para outra página
    if (!$produto) {
        header("Location:../view/paginaNaoEncontrada.html");
        exit();
    }
} else {
    header("Location:../view/paginaNaoEncontrada.html");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/produto.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Detalhes do produto | Iconst</title>
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
            <li><a href="carrinho.php"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a>
            </li>
        </ul>
    </header>

    <main class="detalheProduto-container">
        <!--IMAGENS-->
        <section class="galeria-produto">
            <!-- seta a imagem principal  -->
            <div class="imagem-principal">
                <img src="../img/produtos/<?php echo $imagemPrincipal['imagem_url'] ?>" id="imagem-grande" alt="Produto">
            </div>

            <div class="faixa-miniaturas">
                <button class="btn seta-esquerda-miniatura">&#10094;</button>
                <div class="miniaturas">
                    <!-- percorre todas as imagens e define como miniatura  -->
                    <?php
                    foreach ($imagensProduto as $miniatura):
                    ?>
                        <img src="../img/produtos/<?php echo $miniatura['imagem_url'] ?>" onclick="trocarImagem(this)">
                    <?php
                    endforeach;
                    ?>
                </div>
                <button class="btn seta-direita-miniatura">&#10095;</button>
            </div>
        </section>

        <!--OPÇÕES DO ITEM-->
        <section class="informacoes-produto">
            <!-- seta os dados do produto  -->
            <form action="../bd/controller/Carrinho_controller.php" method="post">
                <h1><?php echo $produto['nome'] ?></h1>
                <input style="display:none" name="produto_id" value="<?php echo $produto['produto_id'] ?>"></input>
                <p class="descricao"><?php echo $produto['descricao'] ?></p>
                <p class="preco"> R$<?php echo $produto['preco'] ?></p>
                <input style="display:none" name="preco" value="<?php echo $produto['preco'] ?>"></input>
                <!-- TODO puxar do banco -->
                <p>Avaliação: ★★★★☆(125 vendas)</p>

                <!-- alterar a quantidade que vai adicionar ao carrinho -->
                <div class="quantidade-container">
                    <label for="quantidade">Quantidade:</label>
                    <button onclick="alterarQtd(-1)" type="button">-</button>
                    <input type="number" id="quantidade" name="quantidade" value="1" min="1">
                    <button onclick="alterarQtd(1)" type="button">+</button>
                </div>

                <!-- especificações do produto -->
                <div class="especificacoes-container">
                    <div class="especificacao">
                        <label for="marca">Marca:</label>
                        <select id="marca">
                            <option><?php echo $produto['marca'] ?></option>
                        </select>
                    </div>
                </div>

                <!-- ações do produto  -->
                <div class="acoes">
                    <!-- TODO criar funcionalidade de comprar agora -->
                    <button class="comprar" name="acao" value="comprar">Comprar Agora</button>
                    <button class="carrinho" name="acao" value="adicionar">Adicionar ao Carrinho</button>
                </div>
            </form>

            <div class="frete">
                <!-- TODO criar funcionalidade - integrar com api -->
                <input type="text" placeholder="Digite seu CEP">
                <button>Calcular Frete</button>
            </div>

            <!-- dados da loja -->
            <div class="vendedor">
                <h3>Vendedido por</h3>
                <div class="vendedor-info">
                    <img src="../img/lojas/<?php echo $imagemVendedor['img_vendedor'] ?>" alt="Logo da loja">
                    <div>
                        <a href="loja.html" class="nome-loja"><?php echo $produto['nome_loja'] ?></a>
                        <p>Localização: <?php echo $produto['cidade'] . ' - ' . $produto['estado'] ?></p>
                        <!-- TODO puxar do banco -->
                        <p>Avaliação: ★★★★☆(125 vendas)</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!--DETALHES DO PRODUTO-->
    <section class="detalhes-produto">
        <h2>Detalhes do Produto</h2>
        <div class="lista-detalhes">
            <ul>
                <li><strong>Categoria:</strong> Casa e Construção › Máquinas e Equipamentos › Betoneiras</li>
                <li><strong>Estoque:</strong> 15 unidades</li>
                <li><strong>País de Origem:</strong> Brasil</li>
                <li><strong>Capacidade do Tambor:</strong> 400 litros</li>
            </ul>
            <ul>
                <li><strong>Tipo de Motor:</strong> Elétrico - Monofásico</li>
                <li><strong>Voltagem:</strong> 220V</li>
                <li><strong>Dimensões:</strong> 140cm x 110cm x 150cm</li>
                <li><strong>Envio de:</strong> São Paulo, SP</li>
            </ul>
        </div>

    </section>

    <!--DESCRICAO DO PRODUTO-->
    <section class="descricao-produto">
        <h2>Descrição do Produto</h2>
        <p><strong>Betoneira Profissional 400L - Elétrica 220V</strong></p>
        <p>Alta Performance para Obras de Pequeno e Médio Porte!</p>
        <p>Ideal para obras residenciais e comerciais, a Betoneira 400L entrega eficiência, robustez e segurança para o
            preparo de concreto e argamassa com agilidade.</p>

        <div class="lista-detalhes">
            <ul>
                <li><strong>Tambor de 400 Litros:</strong> Capacidade ideal para otimizar o tempo e a produção.</li>
                <li><strong>Motor Elétrico 220V:</strong> Potente e econômico, com baixo nível de ruído.</li>
                <li><strong>Estrutura Reforçada:</strong> Fabricada em aço carbono com acabamento resistente à corrosão.
                </li>
            </ul>
            <ul>
                <li><strong>Rodízios para Transporte:</strong> Facilita a movimentação dentro da obra.</li>
                <li><strong>Fácil Manuseio:</strong> Sistema de giro suave e alavanca ergonômica para descarga.</li>
            </ul>
        </div>

        <p><strong>Garanta já a sua betoneira e aumente a produtividade da sua obra!</strong></p>
    </section>

    <!--COMENTARIOS-->
    <section class="comentarios">
        <h2>Comentários</h2>
        <div class="comentario">
            <strong>João S.</strong>
            <p>Produto de ótima qualidade, chegou no prazo!</p>
        </div>
        <div class="comentario">
            <strong>Maria C.</strong>
            <p>Boa durabilidade e preço justo.</p>
        </div>
    </section>

    <!--ITENS RECOMENDADOS-->
    <section class="recomendados">
        <h2>Produtos semelhantes</h2>
        <div class="recomendados-lista">
            <div class="item-recomendado">
                <img src="../img/produtos/13_1.jpg" alt="Tinta Acrílica">
                <p class="nome">Betoneira</p>
                <p class="preco">R$ 1.500,00</p>
            </div>

            <div class="item-recomendado">
                <img src="../img/produtos/p1.jpg" alt="Martelo">
                <p class="nome">Tijolo Furado</p>
                <p class="preco">R$ 2,50</p>
            </div>

            <div class="item-recomendado">
                <img src="../img/produtos/p2.jpg" alt="Kit de Brocas">
                <p class="nome">Tijolo Maciço</p>
                <p class="preco">R$ 3,50</p>
            </div>
            <div class="item-recomendado">
                <img src="../img/produtos/13_1.jpg" alt="Tinta Acrílica">
                <p class="nome">Betoneira</p>
                <p class="preco">R$ 1.500,00</p>
            </div>

            <div class="item-recomendado">
                <img src="../img/produtos/p1.jpg" alt="Martelo">
                <p class="nome">Tijolo Furado</p>
                <p class="preco">R$ 2,50</p>
            </div>

            <div class="item-recomendado">
                <img src="../img/produtos/p2.jpg" alt="Kit de Brocas">
                <p class="nome">Tijolo Maciço</p>
                <p class="preco">R$ 3,50</p>
            </div>
            <div class="item-recomendado">
                <img src="../img/produtos/p1.jpg" alt="Tinta Acrílica">
                <p class="nome">Betoneira</p>
                <p class="preco">R$ 1.500,00</p>
            </div>

            <div class="item-recomendado">
                <img src="../img/produtos/p2.jpg" alt="Martelo">
                <p class="nome">Tijolo Furado</p>
                <p class="preco">R$ 2,50</p>
            </div>

            <div class="item-recomendado">
                <img src="../img/produtos/p2.jpg" alt="Kit de Brocas">
                <p class="nome">Tijolo Maciço</p>
                <p class="preco">R$ 3,50</p>
            </div>
        </div>
    </section>

    <script src="../js/global.js"></script>
    <script src="../js/produto.js"></script>
</body>

</html>