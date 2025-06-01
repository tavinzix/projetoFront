<?php
session_start();
require_once('config.inc.php');

//cadastrar categoria
if ($_POST['acao'] == 'cadastrar') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $url = $_POST['url'];
    $nome_arquivo = $_FILES['novaImagem']['name'];
    $tamanho_arquivo = $_FILES['novaImagem']['size'];
    $arquivo_temporario = $_FILES['novaImagem']['tmp_name'];

    if ($nome_arquivo != '') {
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $url . '.' . $extensao;

        //deleta imagem antiga
        $caminho_imagem_antiga = "../img/categoria/" . $novo_nome;
        if (file_exists($caminho_imagem_antiga)) {
            unlink($caminho_imagem_antiga);
        }

        //move para a pasta
        if (move_uploaded_file($arquivo_temporario, "../img/categoria/$novo_nome")) {
            echo " Upload do arquivo foi concluído com sucesso <br>";
        } else {
            echo "Arquivo não pode ser copiado para o servidor.";
        }

        //insere no banco
        $sql = "INSERT INTO categorias (nome, descricao, imagem, url) VALUES (:nome, :descricao, :imagem, :url)";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
        $statement->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $statement->bindParam(':imagem', $novo_nome, PDO::PARAM_STR);
        $statement->bindParam(':url', $url, PDO::PARAM_STR);

        $statement->execute();
    }

    if ($statement) {
        $_SESSION['msgSucesso'] = 'Categoria cadastrada com sucesso';
        header("Location:../view/categorias.php");
    } else {
        echo "Erro";
    }

}
else if ($_POST['acao'] == 'editar') {
    $id = $_POST['id_categoria'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $url = $_POST['url'];
    $nome_arquivo = $_FILES['novaImagem']['name'];
    $tamanho_arquivo = $_FILES['novaImagem']['size'];
    $arquivo_temporario = $_FILES['novaImagem']['tmp_name'];

    if ($nome_arquivo != '') {
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $url . '.' . $extensao;

        //deleta imagem antiga
        $caminho_imagem_antiga = "../img/categoria/" . $novo_nome;
        if (file_exists($caminho_imagem_antiga)) {
            unlink($caminho_imagem_antiga);
        }

        //move para a pasta
        if (move_uploaded_file($arquivo_temporario, "../img/categoria/$novo_nome")) {
            echo " Upload do arquivo foi concluído com sucesso <br>";
        } else {
            echo "Arquivo não pode ser copiado para o servidor.";
        }

        //atualiza no banco com imagem
        $sql = "UPDATE categorias SET nome = :nome, descricao = :descricao, imagem = :imagem, url = :url WHERE id = :id";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
        $statement->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $statement->bindParam(':imagem', $novo_nome, PDO::PARAM_STR);
        $statement->bindParam(':url', $url, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $statement->execute();
    } else {
        //atualiza no banco sem imagem
        $sql = "UPDATE categorias SET nome = :nome, descricao = :descricao, url = :url WHERE id = :id";

        $statement = $connection->prepare($sql);
        $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
        $statement->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $statement->bindParam(':url', $url, PDO::PARAM_STR);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        $statement->execute();
    }



} else if ($_POST['acao'] == 'inativar') {
    $id = $_POST['id_categoria'];

    $sql = "UPDATE categorias SET status = 2 where id = :id";

    $statement = $connection->prepare($sql);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    $statement->execute();
}
 else if ($_POST['acao'] == 'ativar') {
    $id = $_POST['id_categoria'];

    $sql = "UPDATE categorias SET status = 1 where id = :id";

    $statement = $connection->prepare($sql);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    $statement->execute();
}
