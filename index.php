<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('bd/dao/conexao.php');
require_once('bd/dao/usuario_DAO.php');
require_once('bd/dao/categoria_DAO.php');
$conexao = (new Conexao())->conectar();

$cpf = $_SESSION['cpf'] ?? null;
$imagemUsuario = 'img/users/avatar.jpg';

if ($cpf) {
    $listaUsuario = new usuario_DAO($conexao);
    $usuario = $listaUsuario->buscaUsuario($cpf);

    if ($usuario && !empty($usuario['img_user'])) {
        $imagemUsuario = 'img/users/' . ($usuario['img_user']);
    }
}

$listaCategoria = new categoria_DAO($conexao);
$categorias = $listaCategoria->listarCategoriaAtiva();

$sql_produtoDestaque = "SELECT p.*, vp.*, pi.* FROM produtos p 
                        JOIN vendedores_produtos vp ON vp.produto_id = p.id 
                        LEFT JOIN produto_imagens pi ON p.id = pi.produto_id AND pi.ordem = 1";

$stmt_produtoDestaque = $conexao->prepare($sql_produtoDestaque);
$stmt_produtoDestaque->execute();

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <title>Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="img/site/icone.png" type="image/x-icon">
</head>

<body>
    <!--CABEÇALHO-->
    <header class="menu">
        <div class="logo">
            <a href="index.php"> <img src="img/site/logo.png"></a>
        </div>

        <form action="view/buscaProdutos.php" method="GET" class="busca-container">
            <input type="text" class="busca-input" id="caixa-pesquisa" name="url" placeholder="Procurar produto ou loja">

            <button type="button" id="microfone" onclick="buscaAudio()">
                <img src="img/site/microfone.png" id="iconeft" alt="Microfone">
            </button>

            <button type="submit" class="lupa-icone">
                <img src="img/site/lupa.png" id="iconeft">
            </button>
        </form>

        <button class="menu-hamburguer" id="menu-hamburguer">
            &#9776;
        </button>

        <ul class="menu-link" id="menu-link">
            <li><a href="index.php">Início</a></li>
            <li><a href="view/carrinho.php"><img src="img/site/carrinho.png"></a></li>
            <li><a href="view/perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
        </ul>
    </header>

    <!--CATEGORIAS-->
    <section>
        <div class="categorias-exterior">
            <div class="topo-categorias">
                <h1>Categorias</h1>
            </div>

            <button class="btn seta-esquerda-categoria">&#10094;</button>

            <div class="categorias">
                <?php foreach ($categorias as $categoria): ?>
                    <div class="itens-categoria">
                        <a href="view/itensCategoria.php?url=<?php echo $categoria['url']; ?>">
                            <div class="img-categoria">
                                <img src="img/categoria/<?= $categoria['imagem'] ?>" alt="<?php echo $categoria['nome'] ?>">
                            </div>
                            <p><?php echo $categoria['nome'] ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="btn seta-direita-categoria">&#10095;</button>
        </div>
    </section>

    <!--OFERTAS EM DESTAQUE-->
    <!-- TODO Listar ofertas com maior diferença de preço
        quase acabando 
        publicadas por último-->
    <section>
        <div class="carrossel-exterior">
            <div class="topo-carrossel">
                <h1>Ofertas recentes</h1>
                <a href="pagina com todos os itens"> Ver todos os itens</a>
            </div>

            <button class="btn seta-esquerda-oferta">&#10094;</button>

            <div class="carrossel-oferta">
                <div class="ofertas">
                    <a href="#"></a>
                    <img src="img/oferta/p1.jpg" alt="Produto 1">
                    <h3>Betoneira</h3>
                    <p>Betoneira CSM de 50kg</p>
                    <p>R$ 1.000,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p2.jpg" alt="Produto 1">
                        <h3>Luva de Pedreiro</h3>
                        <p>Luva de pedreiro 100% algodão feita especialmente para pedreiros qualificados </p>
                        <p>R$ 30,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p3.jpg" alt="Produto 1">
                        <h3>Telha</h3>
                        <p>Telha de amianto com 20cm de espessura ideal </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p4.jpg" alt="Produto 1">
                        <h3>Cimento Votoran</h3>
                        <p>Cimento de qualidade para obras </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#"></a>
                    <img src="img/oferta/p1.jpg" alt="Produto 1">
                    <h3>Betoneira</h3>
                    <p>Betoneira CSM de 50kg</p>
                    <p>R$ 1.000,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p2.jpg" alt="Produto 1">
                        <h3>Luva de Pedreiro</h3>
                        <p>Luva de pedreiro 100% algodão feita especialmente para pedreiros qualificados </p>
                        <p>R$ 30,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p3.jpg" alt="Produto 1">
                        <h3>Telha</h3>
                        <p>Telha de amianto com 20cm de espessura ideal </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p4.jpg" alt="Produto 1">
                        <h3>Cimento Votoran</h3>
                        <p>Cimento de qualidade para obras </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#"></a>
                    <img src="img/oferta/p1.jpg" alt="Produto 1">
                    <h3>Betoneira</h3>
                    <p>Betoneira CSM de 50kg</p>
                    <p>R$ 1.000,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p2.jpg" alt="Produto 1">
                        <h3>Luva de Pedreiro</h3>
                        <p>Luva de pedreiro 100% algodão feita especialmente para pedreiros qualificados </p>
                        <p>R$ 30,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p3.jpg" alt="Produto 1">
                        <h3>Telha</h3>
                        <p>Telha de amianto com 20cm de espessura ideal </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p4.jpg" alt="Produto 1">
                        <h3>Cimento Votoran</h3>
                        <p>Cimento de qualidade para obras </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#"></a>
                    <img src="img/oferta/p1.jpg" alt="Produto 1">
                    <h3>Betoneira</h3>
                    <p>Betoneira CSM de 50kg</p>
                    <p>R$ 1.000,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p2.jpg" alt="Produto 1">
                        <h3>Luva de Pedreiro</h3>
                        <p>Luva de pedreiro 100% algodão feita especialmente para pedreiros qualificados </p>
                        <p>R$ 30,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p3.jpg" alt="Produto 1">
                        <h3>Telha</h3>
                        <p>Telha de amianto com 20cm de espessura ideal </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>
                <div class="ofertas">
                    <a href="#">
                        <img src="img/oferta/p4.jpg" alt="Produto 1">
                        <h3>Cimento Votoran</h3>
                        <p>Cimento de qualidade para obras </p>
                        <p>R$ 10,00</p>
                    </a>
                </div>

            </div>
            <button class="btn seta-direita-oferta">&#10095;</button>
        </div>
    </section>

    <!--PRODUTOS EM DESTAQUE-->
    <!--TODO listar itens em 6 colunas e algumas linhas, os demais clicar no botão para abrir nova pagina com itens-->
    <main>
        <div class="produtos-exterior">
            <div class="topo-produtos">
                <h1>Produtos em destaque</h1>
            </div>

            <div class="produtos-container">
                <?php
                while ($produtoDestaque = $stmt_produtoDestaque->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <div class="produtos">
                        <a href="view/produto.php?id=<?php echo $produtoDestaque['produto_id'] ?>">
                            <img src="img/produtos/<?php echo $produtoDestaque['imagem_url'] ?>" alt="<?php echo $produtoDestaque['nome'] ?>">
                            <h3><?php echo $produtoDestaque['nome'] ?></h3>
                            <p><?php echo $produtoDestaque['descricao'] ?></p>
                            <p>R$<?php echo $produtoDestaque['preco'] ?></p>
                        </a>
                    </div>
                <?php
                }
                ?>

            </div>

            <div class="botao-produtos">
                <a href="pagina com alguns itens"><button>Ver mais produtos</button></a>
            </div>

        </div>
    </main>

    <script src="js/global.js"></script>
    <script src="js/index.js"></script>
</body>

</html>

<!--TODO arrumar responsividade das paginas php
    TODO editar produto
    TODO editar pagina da loja
    TODO implementar busca pela loja
    
    TODO verificar js{
        Feedback claro ao usuário sobre ações e erros. 
        Validação de formulários.
    }
    TODO funcionalidades do adm
    TODO funcionalidades do vendedor
    TODO mudar toda logica de aceitar a solicitação{
        nome do adm que aceitou a solicitação
        criar possivelmente um user pro adm
        mudar tabela da solicitação para incluir o nome
        incluir novo check no insert
        pensar em uma lógica para listar também quem aprovou a solicitação
    }

    TODO mascara nos inputs
    TODO criar filtros nas páginas{
        solicitações pendentes
        cadastro de produtos
        categorias
        página principal    
    }

     -->
<!--

    /*
    Uso                                     Cor                 Código HEX 
    Primária (confiança/profissionalismo)   Azul Escuro         #1F3C88 
    Secundária (neutro/moderno)             Cinza Claro         #F2F2F2 
    Texto principal                         Cinza Grafite       #333333 
    Botões/CTAs (ação/energia)              Laranja Vibrante    #FF6B00 
    Destaques ou ícones                     Amarelo Industrial  #FFD100 
    Complementar (detalhes/links)           Azul Claro          #4A90E2
    azul claro #4987ff
    Amarelo construção #f9a825;
    cinza #4d4d51
    */


    lista icones hexa html
    https://www.compart.com/en/unicode/category
    https://erikasarti.com/html/dingbats-simbolos-desenhos/



    icone carrinho https://icons8.com.br/icon/5esIoe7Rz8YD/buying
    icone lupa https://icons8.com.br/icon/7695/search
    icone microfone http://icons8.com.br/icons/set/microfone
-->