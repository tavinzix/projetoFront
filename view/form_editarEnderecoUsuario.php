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
    $sql_endereco = "SELECT * from usuarios u join enderecos e on e.user_id = u.id where u.cpf = :cpf";
    $stmt_endereco = $connection->prepare($sql_endereco);
    $stmt_endereco->bindParam(':cpf', $cpf);
    $stmt_endereco->execute();
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
    <title>Editar endereços | Iconst</title>
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

    <section class="editar-enderecos">
        <!-- lista endereços cadastrados -->
        <h3>Meus Endereços</h3>
        <div class="lista-enderecos">
            <?php
            if ($cpf) {
                if ($stmt_endereco->rowCount() > 0) {
                    while ($usuario = $stmt_endereco->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="endereco">
                            <!-- dados do endereço  -->
                            <p><strong><?= $usuario['tipo'] ?></strong></p>
                            <p><?= $usuario['rua'] . ', ' . $usuario['numero'] . ' - ' . $usuario['bairro'] ?></p>
                            <p><?= $usuario['cidade'] . ' - ' . $usuario['estado'] . ', ' . $usuario['cep'] ?></p>

                            <!-- editar ou remover endereço  -->
                            <div class="acoes-endereco">
                                <form action="../bd/editarEnderecoUsuario.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                    <input type="hidden" name="userId" value="<?= $usuario['user_id'] ?>">
                                    <a onclick='abrirJanelaEndereco(<?php echo json_encode($usuario) ?>)'>
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
                    <div class="endereco">
                        <h3>Ainda não há endereços cadastrados</h3>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        
        <!-- modal para editar endereço -->
        <div id="janela-endereco" class="janela-endereco">
            <div class="janela-conteudo-endereco">
                <span onclick="fecharJanelaEndereco()">&#10005;</span>
                <h2>Detalhes do endereço</h2>

                <form action="../bd/editarEnderecoUsuario.php" method="POST" id="formularioEdicaoEndereco">
                    <div class="informacao-endereco" style="display:none">
                        <strong>Id do endereço:</strong>
                        <p id="id_endereco" name="id_endereco"></p>
                    </div>
                    
                    <div class="informacao-endereco" style="display:none">
                        <strong>Id do usuario:</strong>
                        <p id="id_usuario" name="id_usuario"></p>
                    </div>

                    <div class="informacao-endereco">
                        <!-- TODO tipo radio para mostrar o endereço -->
                        <strong>Tipo do endereço:</strong> <br>
                        <input id="tipo" name="tipo">
                    </div>

                    <div class="informacao-endereco">
                        <strong>CEP:</strong><br>
                        <textarea id="cep" name="cep"></textarea>
                    </div>

                    <div class="informacao-endereco">
                        <strong>Estado:</strong><br>
                        <input id="estado" name="estado">
                    </div>

                    <div class="informacao-endereco">
                        <strong>Cidade:</strong><br>
                        <input id="cidade" name="cidade">
                    </div>

                    <div class="informacao-endereco">
                        <strong>Bairro:</strong><br>
                        <input id="bairro" name="bairro">
                    </div>

                    <div class="informacao-endereco">
                        <strong>Rua:</strong><br>
                        <input id="rua" name="rua">
                    </div>

                    <div class="informacao-endereco">
                        <strong>Número:</strong><br>
                        <input id="numero" name="numero">
                    </div>
                    
                    <div class="informacao-endereco">
                        <strong>Complemento:</strong><br>
                        <input id="complemento" name="complemento">
                    </div>

                    <!-- editar endereço -->
                    <button onclick="editar()" class="btn-editar" type="button" id="editarBtn">Editar</button>
                </form>
            </div>
        </div>

        <!-- cadastro de endereço  -->
        <form class="form-endereco" action="../bd/editarEnderecoUsuario.php" method="POST">
            <h4>Adicionar novo endereço</h4>

            <div class="campo-form">
                <div class="itens-filtros">
                    <label>Tipo de endereço:</label>
                    <div>
                        <input type="radio" name="tipo" value="Casa">
                        <label for="casa">Casa</label>
                    </div>
                    <div>
                        <input type="radio" name="tipo" value="Trabalho">
                        <label for="trabalho">Trabalho</label>
                    </div>
                </div>
            </div>

            <div class="campo-form" style="display:none">
                <label for="userId">User ID</label>
                <input type="text" id="userId" name="userId" value="<?php echo $userId ?>">
            </div>

            <div class="campo-form">
                <label for="cep">CEP</label>
                <input type="text" id="cep" name="cep" onclick="getMyLocation()" required>
            </div>

            <div class="campo-form">
                <label for="estado">Estado</label>
                <input type="text" id="estado" name="estado" required>
            </div>

            <div class="campo-form">
                <label for="cidade">Cidade</label>
                <input type="text" id="cidade" name="cidade" required>
            </div>

            <div class="campo-form">
                <label for="rua">Rua</label>
                <input type="text" id="rua" name="rua" required>
            </div>

            <div class="campo-form">
                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero" required>
            </div>

            <div class="campo-form">
                <label for="bairro">Bairro</label>
                <input type="text" id="bairro" name="bairro" required>
            </div>

            <div class="campo-form">
                <label for="complemento">Complemento</label>
                <input type="text" id="complemento" name="complemento">
            </div>

            <button type="submit" class="btn-salvar" name="acao" value="salvar">Salvar Endereço</button>
        </form>
    </section>
</body>
<script src="../js/global.js"></script>
<script src="../js/perfilUsuario.js"></script>

</html>