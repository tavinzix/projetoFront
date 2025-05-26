<?php
    session_start();
    require_once('config.inc.php');
    function erro_json($mensagem){
        header('Content-Type: application/json', true);
        echo json_encode(['status' => 'error', 'mensagem' => $mensagem]);
        exit();
    }
    
    if ($_POST['acao'] == 'cadastrar') {
        $userId = $_POST['user_id'];
        $nome = $_POST['nome_loja'];
        $cnpj = $_POST['cnpj'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $cep = $_POST['cep'];
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $categoria = $_POST['categoria'];
        $descricao = $_POST['descricao'];

        $sql = "INSERT INTO solicitacoes_vendedor (user_id, nome_loja, cnpj, email, telefone, cep, estado, cidade, bairro, rua, numero, categoria, descricao_loja, status) 
                VALUES (:userId, :nome, :cnpj, :email, :telefone, :cep, :estado, :cidade, :bairro, :rua, :numero, :categoria, :descricao, 1)";

        $statement = $connection->prepare($sql);
        
        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $statement->bindParam(':nome', $nome, PDO::PARAM_STR);
        $statement->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':telefone', $telefone, PDO::PARAM_STR);
        $statement->bindParam(':cep', $cep, PDO::PARAM_STR);
        $statement->bindParam(':estado', $estado, PDO::PARAM_STR);
        $statement->bindParam(':cidade', $cidade, PDO::PARAM_STR);
        $statement->bindParam(':bairro', $bairro, PDO::PARAM_STR);
        $statement->bindParam(':rua', $rua, PDO::PARAM_STR);
        $statement->bindParam(':numero', $numero, PDO::PARAM_STR);
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

        $userId = $_POST['id_user'];
        $nome = $_POST['nome'];
        $cnpj = $_POST['cnpj'];
        $descricao = $_POST['descricao'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $categoria = $_POST['categoria'];
        $cep = $_POST['cep'];
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
      
        try {
            $connection->beginTransaction();

            $sqlInsertVendedor = "INSERT INTO vendedores (user_id, nome_loja, cnpj, descricao_loja, email, telefone, categoria, cep, estado, cidade, bairro, rua, numero)
                                    VALUES (:userId, :nome, :cnpj, :descricao, :email, :telefone, :categoria, :cep, :estado, :cidade, :bairro, :rua, :numero )";

            $statementInsert = $connection->prepare($sqlInsertVendedor);
            $statementInsert->bindParam(':userId', $userId, PDO::PARAM_INT);
            $statementInsert->bindParam(':nome', $nome, PDO::PARAM_STR);
            $statementInsert->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
            $statementInsert->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $statementInsert->bindParam(':email', $email, PDO::PARAM_STR);
            $statementInsert->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $statementInsert->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $statementInsert->bindParam(':cep', $cep, PDO::PARAM_STR);
            $statementInsert->bindParam(':estado', $estado, PDO::PARAM_STR);
            $statementInsert->bindParam(':cidade', $cidade, PDO::PARAM_STR);
            $statementInsert->bindParam(':bairro', $bairro, PDO::PARAM_STR);
            $statementInsert->bindParam(':rua', $rua, PDO::PARAM_STR);
            $statementInsert->bindParam(':numero', $numero, PDO::PARAM_STR);

            if (!$statementInsert->execute()) {
                throw new Exception('Erro ao inserir vendedor.');
            }

            $sqlDeleteSolicitacao = "UPDATE solicitacoes_vendedor set status = 3 WHERE user_id = :userId";

            $statementDelete = $connection->prepare($sqlDeleteSolicitacao);
            $statementDelete->bindParam(':userId', $userId, PDO::PARAM_INT);

            if (!$statementDelete->execute()) {
                throw new Exception('Erro ao remover solicitação.');
            }

            $connection->commit();

            $_SESSION['msgSucesso'] = 'Solicitação aprovada com sucesso';
            header("Location:../view/solicitacaoPendente.php");
            exit();

        } catch (Exception $e) {
            $connection->rollBack();
            echo 'Erro: ' . $e->getMessage();
        }
    }else if($_POST['acao'] == 'rejeitar'){
        $solicitacaoId = $_POST['id_pedido'];
        $motivo = $_POST['motivo'];

        try {
            $sql = "UPDATE solicitacoes_vendedor SET status = 2, motivo_rejeicao = :motivo WHERE id = :id_pedido";
            $statement = $connection->prepare($sql);

            $statement->bindParam(':id_pedido', $solicitacaoId, PDO::PARAM_INT);
            $statement->bindParam(':motivo', $motivo, PDO::PARAM_STR);

            if ($statement->execute()) {
                $_SESSION['msgSucesso'] = 'Solicitação reprovada com sucesso';
                header("Location:../view/solicitacaoPendente.php");
                exit();
            } else {
                throw new Exception('Erro ao reprovar a solicitação.');
            }
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }
?>