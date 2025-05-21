<?php
    session_start();
    require_once('../bd/config.inc.php');
    ini_set('default_charset', 'utf-8');

    $cpf = $_SESSION['cpf'] ?? null;
    $imagemUsuario = '../img/users/avatar.jpg';

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
    
    $sql = "SELECT * FROM solicitacoes_vendedor LIMIT 10";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/painelAdm.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Iconst</title>
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
            <li><a href="../view/index.php">Início</a></li>
            <li><a href="carrinho.html"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="../view/perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
        </ul>
    </header>

    <main class="solicitacoes-exterior">
        <div class="topo-painel">
            <h2>Solicitações</h2>
        </div>

        <!--Filtros-->
        <div class="filtros">
            <form method="GET" class="filtro-form">
                <input type="text" name="busca" placeholder="Buscar por nome ou categoria" />
                <select name="status">
                    <option value="">Status</option>
                    <option value="ativo">Pendente</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>
        </div>

        <!--Lista de solicitacoes
        TODO arrumar css-->
        <table class="tabela-solicitacao">
            <thead>
                <tr>
                    <th>Nome da loja</th>
                    <th>CNPJ</th>
                    <th>Endereço</th>
                    <th>Status</th>
                    <th>Ação</th>

                </tr>
            </thead>
            <tbody>
                <?php
                    while ($solicitacao = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                ?>
                <tr>                 
                    <td><?php echo $solicitacao['nome_loja']?></td>
                    <td><?php echo $solicitacao['cnpj']?></td>
                    <td><?php echo $solicitacao['endereco']?></td>
                    <!--TODO adicionar status do pedido de acordo com o banco-->
                    <td><span class="tag pendente">Pendente</span></td>
                    <td><a href="#" onclick='abrirJanelaSolicitacao(<?php echo json_encode($solicitacao) ?>)'><button class="btn-editar">Avaliar</button></a></td>
                </tr>
                <?php 
                    } 
                ?>
            </tbody>
        </table>
    </main>

    <div id="janela-solicitacoes" class="janela-solicitacao">
        <div class="janela-conteudo-solicitacoes">
            <span onclick="fecharJanelaSolicitacao()">&#10005;</span>
            <h2>Detalhes da loja</h2>
            
            <form action="../bd/solicitacao_vendedor.php" method="post">
                <div class="informacao-loja" style="display:none">
                    <p><strong>Id do pedido:</strong><p id="id_pedido" name="id_pedido"></p> </p>
                </div>

                <div class="informacao-loja" style="display:none">
                    <p><strong>Id usuario:</strong><p id="id_user" name="id_user"></p> </p>
                </div>
                
                <div class="informacao-loja">
                    <p><strong>Nome da loja:</strong><p id="nome" name="nome"></p> </p>
                </div>

                <div class="informacao-loja">
                    <p><strong>CNPJ:</strong><p id="cnpj" name="cnpj"></p> </p>
                </div>

                <div class="informacao-loja">
                    <p><strong>Email:</strong><p id="email" name="email"></p> </p>
                </div>

                <div class="informacao-loja">
                    <p><strong>Telefone:</strong><p id="telefone" name="telefone"></p> </p>
                </div>

                <div class="informacao-loja">
                    <p><strong>CEP:</strong><p id="cep" name="cep"></p></p>
                </div>

                <div class="informacao-loja">
                    <p><strong>Endereco</strong><p id="endereco" name="endereco"></p> </p>
                </div>

                <div class="informacao-loja">
                    <p><strong>Categoria:</strong><p id="categoria" name="categoria"></p> </p>
                </div>

                <div class="informacao-loja">
                    <p><strong>Descrição:</strong><p id="descricao" name="descricao"></p> </p>
                </div>

                <div class="informacao-loja">
                    <p><strong>Data do pedido:</strong><p id="data"></p> </p>
                </div>
                
                <button class="btn-editar" name="acao" value="aprovar">Aprovar</button>
                <!--TODO comentario para rejeitar-->
                <button class="btn-editar" name="acao" value="rejeitar">Rejeitar</button></a>
            </form>
        </div>
    </div>
    <script src="../js/js.js"></script>
</body>
</html>