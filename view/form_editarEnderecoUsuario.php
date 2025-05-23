<?php
    session_start();
    require_once('../bd/config.inc.php');
    ini_set('default_charset', 'utf-8');

    $cpf = $_SESSION['cpf'] ?? null;
    $userId = $_SESSION['usuario_id'];

    $imagemUsuario = '../img/users/avatar.jpg';

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
    </head>
    <body>
        <header class="menu">
            <div class="logo">
                <a href="../index.php"><img src="../img/site/logo.png"></a>
            </div>
            <form action="#" method="GET" class="busca-container">
                <input type="text" class="busca-input" placeholder="Procurar produto ou loja">
                <button type="submit" class="lupa-icone">
                    <img src="../img/site/lupa.png" id="iconeft">
                </button>
            </form>
            <button class="menu-hamburguer">&#9776;</button>
            <ul class="menu-link">
                <li><a href="../index.php">Início</a></li>
                <li><a href="carrinho.html"><img src="../img/site/carrinho.png"></a></li>
                <li><a href="perfilUsuario.php"><img src="<?= $imagemUsuario ?>" id="icone-perfil" alt="Perfil"></a></li>
            </ul>
        </header>

        <section class="editar-enderecos">
            <h3>Meus Endereços</h3>
            <!--TODO arrumar botão de editar para carregar os dados para o input-->
            <div class="lista-enderecos">
                <?php 
                if ($cpf){
                    if($stmt_endereco->rowCount() > 0){
                        while ($usuario = $stmt_endereco->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="endereco">
                            <p><strong><?= $usuario['tipo'] ?></strong></p>
                            <p><?= $usuario['rua'] . ', ' . $usuario['numero'] . ' - ' . $usuario['bairro'] ?></p>
                            <p><?= $usuario['cidade'] . ' - ' . $usuario['estado'] . ', ' . $usuario['cep'] ?></p>

                            <div class="acoes-endereco">
                                <form action="../bd/editarEnderecoUsuario.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                    <input type="hidden" name="userId" value="<?= $usuario['user_id'] ?>">
                                    <input type="hidden" name="tipo" value="<?= $usuario['tipo'] ?>">
                                    <input type="hidden" name="cep" value="<?= $usuario['cep'] ?>">
                                    <input type="hidden" name="estado" value="<?= $usuario['estado'] ?>">
                                    <input type="hidden" name="cidade" value="<?= $usuario['cidade'] ?>">
                                    <input type="hidden" name="bairro" value="<?= $usuario['bairro'] ?>">
                                    <input type="hidden" name="rua" value="<?= $usuario['rua'] ?>">
                                    <input type="hidden" name="numero" value="<?= $usuario['numero'] ?>">
                                    <input type="hidden" name="complemento" value="<?= $usuario['complemento'] ?>">
                                    <button class="btn-editar" name="acao" value="editar">Editar</button>
                                    <button class="btn-remover" name="acao" value="excluir">Remover</button>
                                </form>
                            </div>
                        </div>
                    <?php
                        }
                    }else { ?>
                        <div class="endereco">
                            <h3>Ainda não há endereços cadastrados</h3>
                        </div>
                    <?php 
                    }
                }
            ?>
            </div>

            <form class="form-endereco" action="../bd/editarEnderecoUsuario.php" method="POST">
                <h4>Adicionar Novo Endereço</h4>

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
    <script src="../js/geolocation.js"></script>

</html>
