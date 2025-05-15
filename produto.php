<?php
    session_start();
    require_once('config.inc.php');
    ini_set('default_charset', 'utf-8');

    $cpf = $_SESSION['cpf'] ?? null;
    $imagemUsuario = 'img/users/avatar.jpg';

    if ($cpf) {
        $sql = "SELECT img_user FROM usuarios WHERE cpf = :cpf";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && !empty($usuario['img_user'])) {
            $imagemUsuario = 'img/users/' . ($usuario['img_user']);
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/produto.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <title>Detalhes do produto</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
   <!--CABEÇALHO-->
    <header class="menu">
    <div class="logo">
        <a href="index.php"> <img src="img/site/logo.png"></a>
    </div>

    <form action="buscar produto do banco" method="GET" class="busca-container">
        <input type="text" class="busca-input" placeholder="Procurar produto ou loja">
        <button type="submit" class="lupa-icone">
            <img src="img/site/lupa.png" id="iconeft">
        </button>
    </form>

    <button class="menu-hamburguer" id="menu-hamburguer">
        &#9776;
    </button>

    <ul class="menu-link" id="menu-link">
        <li><a href="index.php">Início</a></li>
        <li><a href="carrinho.html"><img src="img/site/carrinho.png"></a></li>
        <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
    </ul>
    </header>

    <main class="detalheProduto-container">
        <!--IMAGENS-->
        <!--/*TODO puxar imagens do banco -->
        <section class="galeria-produto">
            <div class="imagem-principal">
                <img src="img/produtos/p1.jpg" id="imagem-grande" alt="Produto">
            </div>
    
            <div class="faixa-miniaturas">
                <button class="btn seta-esquerda-miniatura">&#10094;</button>
                <div class="miniaturas">
                    <img src="img/produtos/p1.jpg" onclick="trocarImagem(this)">
                    <img src="img/produtos/p2.png" onclick="trocarImagem(this)">
                    <img src="img/produtos/p3.png" onclick="trocarImagem(this)">
                    <img src="img/produtos/p1.jpg" onclick="trocarImagem(this)">
                    <img src="img/produtos/p2.png" onclick="trocarImagem(this)">
                    <img src="img/produtos/p3.png" onclick="trocarImagem(this)">
                    <img src="img/produtos/p1.jpg" onclick="trocarImagem(this)">
                    <img src="img/produtos/p2.png" onclick="trocarImagem(this)">
                    <img src="img/produtos/p3.png" onclick="trocarImagem(this)">
                </div>
                <button class="btn seta-direita-miniatura">&#10095;</button>
            </div>
        </section>

        <!--OPÇÕES DO ITEM-->
        <section class="informacoes-produto">
            <h1>Tijolo Furado</h1>
            <p class="descricao">Tijolo com 9 furos ideal para sua obra. Compre nosso tijolo agora mesmo e garanta que
                sua casa não irá cair tão cedo</p>
            <p class="preco">R$ 1,00</p>
            <p>Avaliação: ★★★★☆(125 vendas)</p>


            <div class="quantidade-container">
                <label for="quantidade">Quantidade:</label>
                <button onclick="alterarQtd(-1)">-</button>
                <input type="number" id="quantidade" value="1" min="1">
                <button onclick="alterarQtd(1)">+</button>
            </div>

            <div class="especificacoes-container">
                <div class="especificacao">
                    <label for="cor">Cor:</label>
                    <select id="cor">
                        <option>Vermelho</option>
                        <option>Marrom</option>
                        <option>Natural</option>
                    </select>
                </div>

                <div class="especificacao">
                    <label for="tamanho">Tamanho:</label>
                    <select id="tamanho">
                        <option>Pequeno</option>
                        <option>Médio</option>
                        <option>Grande</option>
                    </select>
                </div>

                <div class="especificacao">
                    <label for="voltagem">Voltagem:</label>
                    <select id="voltagem">
                        <option>110V</option>
                        <option>220V</option>
                        <option>Bivolt</option>
                    </select>
                </div>
            </div>

            <div class="acoes">
                <button class="comprar" id="comprar">Comprar Agora</button>
                <button class="carrinho" id="carrinho">Adicionar ao Carrinho</button>
            </div>

            <div class="frete">
                <input type="text" placeholder="Digite seu CEP">
                <button>Calcular Frete</button>
            </div>

            <div class="vendedor">
                <h3>Vendedor</h3>
                <div class="vendedor-info">
                    <img src="img/lojas/l1.jpg" alt="Logo da loja">
                    <div>
                        <a href="loja.html" class="nome-loja">Loja do vendedor</a>
                        <p>Cidade: Pelotas - RS</p>
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
                <img src="img/produtos/p1.jpg" alt="Tinta Acrílica">
                <p class="nome">Betoneira</p>
                <p class="preco">R$ 1.500,00</p>
            </div>

            <div class="item-recomendado">
                <img src="img/produtos/p2.png" alt="Martelo">
                <p class="nome">Tijolo Furado</p>
                <p class="preco">R$ 2,50</p>
            </div>

            <div class="item-recomendado">
                <img src="img/produtos/p3.png" alt="Kit de Brocas">
                <p class="nome">Tijolo Maciço</p>
                <p class="preco">R$ 3,50</p>
            </div>
            <div class="item-recomendado">
                <img src="img/produtos/p1.jpg" alt="Tinta Acrílica">
                <p class="nome">Betoneira</p>
                <p class="preco">R$ 1.500,00</p>
            </div>

            <div class="item-recomendado">
                <img src="img/produtos/p2.png" alt="Martelo">
                <p class="nome">Tijolo Furado</p>
                <p class="preco">R$ 2,50</p>
            </div>

            <div class="item-recomendado">
                <img src="img/produtos/p3.png" alt="Kit de Brocas">
                <p class="nome">Tijolo Maciço</p>
                <p class="preco">R$ 3,50</p>
            </div>
            <div class="item-recomendado">
                <img src="img/produtos/p1.jpg" alt="Tinta Acrílica">
                <p class="nome">Betoneira</p>
                <p class="preco">R$ 1.500,00</p>
            </div>

            <div class="item-recomendado">
                <img src="img/produtos/p2.png" alt="Martelo">
                <p class="nome">Tijolo Furado</p>
                <p class="preco">R$ 2,50</p>
            </div>

            <div class="item-recomendado">
                <img src="img/produtos/p3.png" alt="Kit de Brocas">
                <p class="nome">Tijolo Maciço</p>
                <p class="preco">R$ 3,50</p>
            </div>
        </div>
    </section>

    <script src="js/js.js"></script>
</body>

</html>