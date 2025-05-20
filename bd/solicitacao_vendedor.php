<?php
    session_start();
    require_once('config.inc.php');
    
    if ($_POST['acao'] == 'cadastrar') {
        $userId = $_POST['user_id'];
        $nome = $_POST['nome_loja'];
        $cnpj = $_POST['cnpj'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $endereco = $_POST['endereco'];
        $categoria = $_POST['categoria'];
        $descricao = $_POST['descricao'];

        $sql = "INSERT INTO solicitacoes_vendedor (user_id, nome_loja, cnpj, email, telefone, cep, endereco, categoria, descricao_loja, status) 
                VALUES (:userId, :nome, :cnpj, :email, :telefone, :cep, :endereco, :categoria, :descricao, 1)";

        $statement = $connection->prepare($sql);

        $statement->bindParam(':userId', $userId, PDO::PARAM_STR);
        $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
        $statement->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':telefone', $telefone, PDO::PARAM_STR);
        $statement->bindParam(':cep', $cep, PDO::PARAM_STR);
        $statement->bindParam(':endereco', $endereco, PDO::PARAM_STR);
        $statement->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $statement->bindParam(':descricao', $descricao, PDO::PARAM_STR);

        $statement->execute();

        if ($statement) {
            $_SESSION['msgSucesso'] = 'Solicitação enviada com sucesso, aguarde o resultado';
            header("Location:../view/solicitacaoCadastroVendedor.php");
            exit();
        } else {
            echo "Erro";
        }
    }else if($_POST['acao'] == 'aprovar'){
        //TODO 1 begin em tudo
        //TODO 2 insert na vendedor
        //TODO 3 remover na solicitacao
    }else if($_POST['acao'] == 'reprovar'){
        //TODO logica para digitar motivo
        //TODO update no status e motivo
    }
?>