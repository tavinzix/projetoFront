<?php
    session_start();
    require_once('config.inc.php');

   $id = $_POST['id'];
   $nome = $_POST['nome'];
   $email = $_POST['email'];
   $telefone = $_POST['telefone'];
   $senha_atual = $_POST['senha_atual'];
   $nova_senha = $_POST['nova_senha'];
   $confirmar_senha = $_POST['confirmar_senha'];
   $nome_arquivo=$_FILES['novaImagem']['name'];  
   $tamanho_arquivo=$_FILES['novaImagem']['size']; 
   $arquivo_temporario=$_FILES['novaImagem']['tmp_name']; 

    if($nome_arquivo != ''){
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $id . '.' . $extensao;

        $caminho_imagem_antiga = "img/users/" . $novo_nome;
        if (file_exists($caminho_imagem_antiga)) {
            unlink($caminho_imagem_antiga);
        }

        if (move_uploaded_file($arquivo_temporario, "img/users/$novo_nome")){
            echo " Upload do arquivo foi concluído com sucesso <br>";
        }else{
            echo "Arquivo não pode ser copiado para o servidor.";
        }
        
        $sql = "UPDATE usuarios SET nome_completo = :nome, email = :email, senha = :nova_senha, telefone = :telefone, img_user = :imagem WHERE id = :id";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':imagem', $novo_nome, PDO::PARAM_INT);
    }else{
        /*TODO se não alterar a senha esta ficando em branco */
        if(isset($senha_atual) && isset($nova_senha) && isset($confirmar_senha)){
            $sql = "UPDATE usuarios SET nome_completo = :nome, email = :email, senha = :nova_senha, telefone = :telefone WHERE id = :id";
            $statement = $connection->prepare($sql); 
        }else{
            $sql = "UPDATE usuarios SET nome_completo = :nome, email = :email, telefone = :telefone WHERE id = :id";
            $statement = $connection->prepare($sql); 
        }
    }
   
   $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
   $statement->bindParam(':email', $email, PDO::PARAM_STR);
   $statement->bindParam(':nova_senha', $nova_senha, PDO::PARAM_STR);
   $statement->bindParam(':telefone', $telefone, PDO::PARAM_STR);
   $statement->bindParam(':id', $id, PDO::PARAM_INT);

   $statement->execute();

   if ($statement) {
        $_SESSION['msgSucesso'] = 'Alteração Efetuada com sucesso';
        header("Location:perfilUsuario.php");

   } else {
      echo "Erro";
   }
?>

<br><a href="form_cad_usuario.php" style="text-decoration: none;">Cadastrar usuário</a><br>
<a href="listagem_usuario.php" style="text-decoration: none;">Listar usuário</a><br>
<a href="form_cad_prod.php" style="text-decoration: none;">Cadastrar produto</a><br>
<a href="listagem_produto.php" style="text-decoration: none;">Listar produto</a><br>