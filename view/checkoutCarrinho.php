<?php
session_start();
require_once('../bd/config.inc.php');
ini_set('default_charset', 'utf-8');

if (!isset($_SESSION['cpf']) || !isset($_SESSION['logado'])) {
    header("Location:../view/login.html");
}

$cpf = $_SESSION['cpf'] ?? null;


if ($cpf) {
    $sql = "SELECT img_user FROM usuarios WHERE cpf = :cpf";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && !empty($usuario['img_user'])) {
        $imagemUsuario = 'img/users/' . ($usuario['img_user']);
    }

    $sql_endereco = "SELECT * from usuarios u join enderecos e on e.user_id = u.id where u.cpf = :cpf";
    $stmt_endereco = $connection->prepare($sql_endereco);
    $stmt_endereco->bindParam(':cpf', $cpf);
    $stmt_endereco->execute();

    $sql_pagamento = "SELECT * from usuarios u join formas_pagamento fp on fp.user_id = u.id where u.cpf = :cpf";
    $stmt_pagamento = $connection->prepare($sql_pagamento);
    $stmt_pagamento->bindParam(':cpf', $cpf);
    $stmt_pagamento->execute();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/carrinho.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Conferir carrinho | Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="icon" href="../img/site/icone.png" type="image/x-icon">
</head>

<body>
    <section class="pagina-carrinho">
        <div class="checkout-exterior">
            <h1>Confirmação de Compra</h1>

            <form method="POST" action="resumoCompra.php">
                <section>
                    <h2>Escolha um Endereço</h2>
                    <div class="lista-enderecos">
                        <?php
                        if ($stmt_endereco->rowCount() > 0) {
                            while ($endereco = $stmt_endereco->fetch(PDO::FETCH_ASSOC)) { ?>
                                <label class="card">
                                    <input type="radio" name="endereco_id" value="<?= $endereco['id'] ?>" required />
                                    <div>
                                        <p><strong><?= $endereco['tipo'] ?></strong></p>
                                        <p><?= $endereco['rua'] . ', ' . $endereco['numero'] . ' - ' . $endereco['bairro'] ?></p>
                                        <p><?= $endereco['cidade'] . ' - ' . $endereco['estado'] . ', ' . $endereco['cep'] ?></p>
                                    </div>
                                </label>
                            <?php }
                        } else { ?>
                            <div class="card">
                                <h3>Ainda não há endereços cadastrados</h3>
                            </div>
                        <?php } ?>
                    </div>
                    <a href="form_editarEnderecoUsuario.php"><button type="button" class="adicionar-opcao">+ Adicionar novo endereço</button></a>
                </section>

                <section>
                    <h2>Forma de Pagamento</h2>
                    <div class="lista-pagamento">
                        <?php
                        if ($stmt_pagamento->rowCount() > 0) {
                            while ($pagamento = $stmt_pagamento->fetch(PDO::FETCH_ASSOC)) { ?>
                                <label class="card">
                                    <input type="radio" name="pagamento_id" value="<?= $pagamento['id'] ?>" required />
                                    <div>
                                        <p><strong><?= $pagamento['nome_cartao'] ?></strong></p>
                                        <p><?= $pagamento['nome_titular'] . ', ' . $pagamento['numero_cartao'] ?></p>
                                    </div>
                                </label>
                        <?php }
                        } ?>
                        <!-- Opções adicionais fixas -->
                        <label class="card">
                            <input type="radio" name="pagamento_id" value="pix" required />
                            <div>
                                <p><strong>PIX</strong></p>
                                <p>Aprova imediatamente</p>
                            </div>
                        </label>

                        <label class="card">
                            <input type="radio" name="pagamento_id" value="boleto" required />
                            <div>
                                <p><strong>Boleto</strong></p>
                                <p>Aprova em até 3 dias úteis</p>
                            </div>
                        </label>
                    </div>
                    <a href="form_editarFormaPagamentoUsuario.php"><button type="button" class="adicionar-opcao">+ Adicionar nova forma de pagamento</button></a>
                </section>

                <div class="links">
                    <a href="carrinho.php">← Voltar</a>
                </div>

                <div class="botao-avancar">
                    <button type="submit" class="botao-avancar">Avançar</button>
                </div>
            </form>
        </div>
    </section>
</body>

<script src="../js/global.js"></script>

</html>