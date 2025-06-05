<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:../view/login.html");
}

$cpf = $_SESSION['cpf'] ?? null;
$userId = $_SESSION['usuario_id'];
$imagemUsuario = '../img/users/avatar.jpg';

// busca cpf para setar a imagem do header
if ($cpf) {
    $sql_imagem = "SELECT * FROM usuarios WHERE cpf = :cpf";
    $stmt = $connection->prepare($sql_imagem);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    $imagem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($imagem && !empty($imagem['img_user'])) {
        $imagemUsuario = '../img/users/' . ($imagem['img_user']);
    }

    $_SESSION['id'] = $imagem['id'];

    // busca endereços cadastrados 
    $sql_forma = "SELECT * from usuarios u join formas_pagamento fp on fp.user_id = u.id where u.cpf = :cpf";
    $stmt_forma = $connection->prepare($sql_forma);
    $stmt_forma->bindParam(':cpf', $cpf);
    $stmt_forma->execute();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/perfilUsuario.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Editar formas de pagamento | Iconst</title>
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

        <form action="buscaProdutos.php" method="GET" class="busca-container" id="buscaProduto">
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
            <li><a href="../index.php">Início</a></li>
            <li><a href="carrinho.php"><img src="../img/site/carrinho.png"></a></li>
            <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a>
            </li>
        </ul>
    </header>

    <section class="editar-pagamento">
        <!-- lista formas de pagamento cadastradas -->
        <h3>Minhas formas de pagamento</h3>
        <div class="lista-formas">
            <?php
            if ($cpf) {
                if ($stmt_forma->rowCount() > 0) {
                    while ($usuario = $stmt_forma->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="forma-pagamento">
                            <!-- dados da forma de pagamento  -->
                            <p><strong><?php echo $usuario['nome_cartao'] ?></strong></p>
                            <p><?php echo $usuario['nome_titular'] ?></p>
                            <p><?php echo $usuario['numero_cartao'] . ' - ' . $usuario['validade'] ?></p>

                            <!-- editar ou remover forma de pagamento  -->
                            <div class="acoes-formas">
                                <form action="../bd/editarFormaPagamentoUsuario.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                    <input type="hidden" name="userId" value="<?= $usuario['user_id'] ?>">
                                    <a onclick='abrirJanelaPagamento(<?php echo json_encode($usuario) ?>)'>
                                        <button type="button" class="btn-editar">Editar</button>
                                    </a>
                                    <button class="btn-remover" name="acao" value="excluir">Remover</button>
                                </form>
                            </div>
                        </div>
                    <?php
                    }
                    // mensagem caso não tenha nenhum endereço 
                } else { ?>
                    <div class="forma-pagamento">
                        <h3>Ainda não há formas de pagamento cadastradas</h3>
                    </div>
            <?php
                }
            }
            ?>
        </div>

        <!-- modal para editar endereço -->
        <div id="janela-pagamento" class="janela-pagamento">
            <div class="janela-conteudo-pagamento">
                <span onclick="fecharJanelaPagamento()">&#10005;</span>
                <h2>Detalhes da forma de pagamento</h2>

                <form action="../bd/editarFormaPagamentoUsuario.php" method="POST" id="formularioEdicaoPagamento">
                    <div class="informacao-pagamento" style="display:none">
                        <strong>Id da forma:</strong>
                        <p id="id_forma" name="id_forma"></p>
                    </div>

                    <div class="informacao-pagamento" style="display:none">
                        <strong>Id do usuario:</strong>
                        <p id="id_usuario" name="id_usuario"></p>
                    </div>

                    <div class="informacao-pagamento">
                        <strong>Nome do titular:</strong><br>
                        <input id="nome_titular" name="nome_titular">
                    </div>

                    <div class="informacao-pagamento">
                        <strong>Nome do cartão:</strong><br>
                        <input id="nome_cartao" name="nome_cartao">
                    </div>

                    <div class="informacao-pagamento">
                        <strong>Número do cartão:</strong><br>
                        <input id="numero_cartao" name="numero_cartao">
                    </div>

                    <div class="informacao-pagamento">
                        <strong>Validade:</strong><br>
                        <input id="validade" name="validade">
                    </div>

                    <div class="informacao-pagamento">
                        <strong>CVV:</strong><br>
                        <input id="cvv" name="cvv">
                    </div>

                    <!-- editar pagamento -->
                    <button onclick="editar()" class="btn-editar" type="button" id="editarBtn">Editar</button>
                </form>
            </div>
        </div>


        <!-- cadastro de forma de pagamento  -->
        <form class="form-forma" action="../bd/editarFormaPagamentoUsuario.php" method="POST">
            <h4>Adicionar nova forma de pagamento</h4>

            <div class="campo-form" style="display:none">
                <label for="userId">User ID</label>
                <input type="text" id="userId" name="userId" value="<?php echo $userId ?>">
            </div>

            <div class="campo-form">
                <label for="nome">Nome do titular</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="campo-form">
                <label for="cartao">Nome do cartão</label>
                <input type="text" id="cartao" name="cartao" required>
            </div>

            <div class="campo-form">
                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero" required>
            </div>

            <div class="campo-form">
                <label for="validade">Data de Validade</label>
                <input type="text" id="validade" name="validade" placeholder="MM/AA" required>
            </div>

            <div class="campo-form">
                <label for="cvv">CVV</label>
                <input type="number" id="cvv" name="cvv" minlength="3" maxlength="3" required>
            </div>

            <button type="submit" class="btn-salvar" name="acao" value="salvar">Salvar</button>
        </form>
    </section>
</body>
<script src="../js/global.js"></script>
<script src="../js/perfilUsuario.js"></script>

</html>