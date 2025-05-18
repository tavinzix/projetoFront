<?php
    session_start();
    require_once('config.inc.php');

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $url = $_POST['url'];
    $nome_arquivo=$_FILES['novaImagem']['name'];  
    $tamanho_arquivo=$_FILES['novaImagem']['size']; 
    $arquivo_temporario=$_FILES['novaImagem']['tmp_name']; 

    if($nome_arquivo != ''){
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $url . '.' . $extensao;

        $caminho_imagem_antiga = "img/categoria/" . $novo_nome;
        if (file_exists($caminho_imagem_antiga)) {
            unlink($caminho_imagem_antiga);
        }

        if (move_uploaded_file($arquivo_temporario, "img/categoria/$novo_nome")){
            echo " Upload do arquivo foi concluído com sucesso <br>";
        }else{
            echo "Arquivo não pode ser copiado para o servidor.";
        }

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
        header("Location:categorias.php");
    } else {
      echo "Erro";
    }
?>