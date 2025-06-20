<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/dao/usuario_DAO.php');
require_once('../bd/dao/solicitacao_DAO.php');
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
}

// busca solicitações pendentes 
$listaSolicitacao = new solicitacao_DAO($conexao);
$solicitacoes = $listaSolicitacao->buscaSolicitacaoComStatus();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/painelAdm.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Solicitações de vendedores | Iconst</title>
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
            <li><a href="../view/index.php">Início</a></li>
            <li><a href="carrinho.php"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
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

        <!--Lista de solicitacoes-->
        <table class="tabela-solicitacao">
            <thead>
                <tr>
                    <th>Nome da loja</th>
                    <th>CNPJ</th>
                    <th>Localização</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($solicitacoes as $solicitacao):        
                ?>
                    <tr>
                        <td><?php echo $solicitacao['nome_loja'] ?></td>
                        <td><?php echo $solicitacao['cnpj'] ?></td>
                        <td><?php echo $solicitacao['estado'] . ' - ' . $solicitacao['cidade'] ?></td>
                        <td><span class="tag <?php echo $solicitacao['status_texto'] ?>"><?php echo $solicitacao['status_texto'] ?></span></td>
                        <!-- abre modal com todas as informações da loja -->
                        <td><a onclick='abrirJanelaSolicitacao(<?php echo json_encode($solicitacao) ?>)'><button class="btn-editar">Avaliar</button></a></td>
                    </tr>
                <?php
                    endforeach;
                ?>
            </tbody>
        </table>
    </main>

    <div id="janela-solicitacoes" class="janela-solicitacao">
        <!-- detalhes da solicitação -->
        <div class="janela-conteudo-solicitacoes">
            <span onclick="fecharJanelaSolicitacao()">&#10005;</span>
            <h2>Detalhes da loja</h2>

            <form action="../bd/controller/Solicitacao_controller.php" method="post" id="formularioSolicitacao">
                <input id="id_pedido" name="id_pedido" type="hidden"></input>
                <input id="id_user" name="id_user" type="hidden"></input>
                <input id="status" name="status" type="hidden"></input>

                <div class="informacao-loja">
                    <strong>Nome da loja:</strong><br>
                    <input id="nome" name="nome" type="text" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>CNPJ:</strong><br>
                    <input id="cnpj" name="cnpj" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Email:</strong><br>
                    <input id="email" name="email" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Telefone:</strong><br>
                    <input id="telefone" name="telefone" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>CEP:</strong><br>
                    <input id="cep" name="cep" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Estado</strong><br>
                    <input id="estado" name="estado" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Cidade</strong><br>
                    <input id="cidade" name="cidade" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Bairro</strong><br>
                    <input id="bairro" name="bairro" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Rua</strong><br>
                    <input id="rua" name="rua" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Número</strong><br>
                    <input id="numero" name="numero" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Categoria:</strong><br>
                    <input id="categoria" name="categoria" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Descrição:</strong><br>
                    <input id="descricao" name="descricao" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Data do pedido:</strong><br>
                    <input id="data" readonly></input>
                </div>

                <div class="informacao-loja">
                    <strong>Motivo da rejeição:</strong><br>
                    <textarea id="motivo" name="motivo" rows="4" required></textarea>
                </div>
                <!-- aprovar ou rejeitar solicitação -->
                <button onclick="aprovar()" class="btn-editar" type="button" id="aprovarBtn">Aprovar</button>
                <button onclick="rejeitar()" class="btn-editar" type="button" id="rejeitarBtn">Rejeitar</button>
            </form>
        </div>
    </div>
    <script src="../js/painelAdm.js"></script>
    <script src="../js/global.js"></script>
</body>

</html>