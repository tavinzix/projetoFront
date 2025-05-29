<?php
    session_start();
    require_once('../bd/config.inc.php');

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

   //atualizar a imagem
    if($nome_arquivo != ''){
        $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);
        $novo_nome = $id . '.' . $extensao;

        //deletar imagem antiga
        $caminho_imagem_antiga = "../img/users/" . $novo_nome;
        if (file_exists($caminho_imagem_antiga)) {
            unlink($caminho_imagem_antiga);
        }

        //mover imagem para a pasta
        if (move_uploaded_file($arquivo_temporario, "../img/users/$novo_nome")){
            echo " Upload do arquivo foi concluído com sucesso <br>";
        }else{
            echo "Arquivo não pode ser copiado para o servidor.";
        }
        
        //valida se senha está preenchida
        if(isset($senha_atual) && $senha_atual !='' && isset($nova_senha) && $nova_senha !='' && isset($confirmar_senha) && $confirmar_senha !=''){
            $sql = "UPDATE usuarios SET nome_completo = :nome, email = :email, senha = :nova_senha, telefone = :telefone, img_user = :imagem WHERE id = :id";
            $statement = $connection->prepare($sql); 
        }
        //não atualiza senha
        else{
            $sql = "UPDATE usuarios SET nome_completo = :nome, email = :email, telefone = :telefone, img_user = :imagem WHERE id = :id";
            $statement = $connection->prepare($sql); 
        }
        
        $statement = $connection->prepare($sql);
        $statement->bindParam(':imagem', $novo_nome, PDO::PARAM_INT);

    //atualizar demais campos
    }else{
        //valida se senha está preenchida
        if(isset($senha_atual) && $senha_atual !='' && isset($nova_senha) && $nova_senha !='' && isset($confirmar_senha) && $confirmar_senha !=''){
            $sql = "UPDATE usuarios SET nome_completo = :nome, email = :email, senha = :nova_senha, telefone = :telefone WHERE id = :id";
            $statement = $connection->prepare($sql); 
        }
        //nao atualiza senha
        else{
            $sql = "UPDATE usuarios SET nome_completo = :nome, email = :email, telefone = :telefone WHERE id = :id";
            $statement = $connection->prepare($sql); 
        }
    }
   
   $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
   $statement->bindParam(':email', $email, PDO::PARAM_STR);
   $statement->bindParam(':telefone', $telefone, PDO::PARAM_STR);
   $statement->bindParam(':id', $id, PDO::PARAM_INT);

   $statement->execute();

   if ($statement) {
        $_SESSION['msgSucesso'] = 'Alteração Efetuada com sucesso';
        header("Location:../view/perfilUsuario.php");
   } else {
      echo "Erro";
   }
?>