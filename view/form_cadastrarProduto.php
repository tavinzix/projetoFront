<?php
session_start();
ini_set('default_charset', 'utf-8');
require_once('../bd/dao/conexao.php');
require_once('../bd/dao/categoria_DAO.php');
$conexao = (new Conexao())->conectar();

// busca categorias 
$listaCategoria = new categoria_DAO($conexao);
$categorias = $listaCategoria->listarCategoriaAtiva();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/gerenciarProduto.css">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="stylesheet" href="../css/responsivo.css">
    <title>Cadastrar produto | Iconst</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="icon" href="../img/site/icone.png" type="image/x-icon">
</head>

<body>
    <div class="cadastroProduto-exterior">
        <h2>Cadastrar Novo Produto</h2>

        <form id="formProduto" action="../bd/controller/Produto_controller.php" method="POST" enctype="multipart/form-data"
            class="cadastroProduto-formulario">

            <label for="nome">Nome do Produto</label>
            <input type="text" name="nome" id="nome" required />

            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="4" required></textarea>

            <label for="categoria">Categoria</label>

            <select name="categoria" id="categoria" required>
                <option value="">Selecione</option>
                <?php
                foreach ($categorias as $categoria):
                ?>
                    <option value="<?php echo $categoria['id'] ?>"><?php echo $categoria['nome'] ?></option>
                <?php
                endforeach;
                ?>
            </select>

            <div id="camposDinamicos"></div>

            <label for="marca">Marca</label>
            <input type="text" name="marca" id="marca" required />

            <label for="preco">Preço</label>
            <input type="number" name="preco" step="0.01" min="0,01" id="preco" required />

            <label for="estoque">Estoque</label>
            <input type="number" name="estoque" id="estoque" min="1" required />

            <label for="imagens">Fotos do Produto (até 5 imagens)</label>

            <div class="imagens-exterior">
                <div class="img-upload">
                    <input type="file" id="foto1" name="imagem1"
                        onchange="mostrarImagem(this, 'img1', 'span1')" required>
                    <img id="img1" style="max-width:150px;">
                    <span id="span1">+</span>
                </div>

                <div class="img-upload">
                    <input type="file" id="foto2" name="imagem2"
                        onchange="mostrarImagem(this, 'img2', 'span2')">
                    <img id="img2" style="max-width:150px;">
                    <span id="span2">+</span>
                </div>

                <div class="img-upload">
                    <input type="file" id="foto3" name="imagem3"
                        onchange="mostrarImagem(this, 'img3', 'span3')">
                    <img id="img3" style="max-width:150px;">
                    <span id="span3">+</span>
                </div>

                <div class="img-upload">
                    <input type="file" id="foto4" name="imagem4"
                        onchange="mostrarImagem(this, 'img4', 'span4')">
                    <img id="img4" style="max-width:150px;">
                    <span id="span4">+</span>
                </div>

                <div class="img-upload">
                    <input type="file" id="foto5" name="imagem5"
                        onchange="mostrarImagem(this, 'img5', 'span5')">
                    <img id="img5" style="max-width:150px;">
                    <span id="span5">+</span>
                </div>
                <input type="hidden" name="detalhes" id="detalhes" />

                <button type="submit" name="acao" value="cadastrar">Cadastrar Produto</button>
                
        </form>
    </div>
    <script src="../js/global.js"></script>
    <script src="../js/painelVendedor.js"></script>
</body>


</html>