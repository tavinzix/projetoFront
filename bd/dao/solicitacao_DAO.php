<?php
class solicitacao_DAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    function enviarSolicitacao(Solicitacao $solicitacao)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO solicitacoes_vendedor (user_id, nome_loja, cnpj, email, telefone, cep, estado, cidade, bairro, rua, numero, categoria, descricao_loja, status) 
                                            VALUES (:userId, :nomeLoja, :cnpj, :email, :telefone, :cep, :estado, :cidade, :bairro, :rua, :numero, :categoria, :descricaoLoja, 1)");

            $resultado = $query->execute([
                'userId' => $solicitacao->getUserId(),
                'nomeLoja' => $solicitacao->getNomeLoja(),
                'cnpj' => $solicitacao->getCnpj(),
                'email' => $solicitacao->getEmail(),
                'telefone' => $solicitacao->getTelefone(),
                'cep' => $solicitacao->getCep(),
                'estado' => $solicitacao->getEstado(),
                'cidade' => $solicitacao->getCidade(),
                'bairro' => $solicitacao->getBairro(),
                'rua' => $solicitacao->getRua(),
                'numero' => $solicitacao->getNumero(),
                'categoria' => $solicitacao->getCategoria(),
                'descricaoLoja' => $solicitacao->getDescricaoLoja()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function atualizaSolicitacaoAprovada(Solicitacao $solicitacao)
    {
        try {
            $query = $this->conexao->prepare("UPDATE solicitacoes_vendedor SET status = 3 WHERE user_id = :userId");
            $resultado = $query->execute(['userId' => $solicitacao->getUserId()]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function atualizaSolicitacaoRejeiada(Solicitacao $solicitacao)
    {
        try {
            $query = $this->conexao->prepare("UPDATE solicitacoes_vendedor SET status = 2, motivo_rejeicao = :motivo WHERE id = :id_pedido");
            $resultado = $query->execute([
                'id_pedido' => $solicitacao->getId(),
                'motivo' => $solicitacao->getMotivoRejeicao()
            ]);

            return $resultado;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscaSolicitacaoPorId($id)
    {
        try {
            $query = $this->conexao->prepare("SELECT * FROM solicitacoes_vendedor WHERE user_id = :userId");
            $query->bindParam(':userId', $id, PDO::PARAM_INT);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }  

    function buscaSolicitacaoComStatus(){
        try {
            $query = $this->conexao->prepare("SELECT *, CASE WHEN status = '1' then 'Pendente' 
                                            WHEN status = '2' then 'Recusado' else 'Aprovado' end AS status_texto
                                            FROM solicitacoes_vendedor ORDER BY status, data_solicitacao");
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscaSolicitacaoPendentePorUsuario($user_id){
        try {
            $query = $this->conexao->prepare("SELECT COUNT(*) from solicitacoes_vendedor where user_id = :user_id and status = '1'");
            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $query->execute();
            $resultado = $query->fetchColumn();

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscaSolicitacaoRecusadaPorUsuario($user_id){
        try {
            $query = $this->conexao->prepare("SELECT motivo_rejeicao from solicitacoes_vendedor where user_id = :user_id and status = '2'");
            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
