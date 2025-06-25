<?php
   Class enderecoUsuario_DAO{
    private $conexao;

    public function __construct(PDO $conexao){
        $this->conexao = $conexao;  
    }

    function inserirEndereco(Endereco $endereco){
       try {
            $query = $this->conexao->prepare("INSERT INTO enderecos (user_id, tipo, cep, estado, cidade, bairro, rua, numero, complemento)
                                            VALUES (:user_id, :tipo, :cep, :estado, :cidade, :bairro, :rua, :numero, :complemento)");

            $resultado = $query->execute(['user_id' => $endereco->getUserId(),'tipo' => $endereco->getTipo(),
                                        'cep' => $endereco->getCep(),'estado' => $endereco->getEstado(),
                                        'cidade' => $endereco->getCidade(),'bairro' => $endereco->getBairro(),
                                        'rua' => $endereco->getRua(),'numero' => $endereco->getNumero(),
                                        'complemento' => $endereco->getComplemento()]);
            
            return $resultado;
            
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function alterarEndereco(Endereco $endereco){
        try {
            $query = $this->conexao->prepare("UPDATE enderecos SET tipo = :tipo, cep = :cep, estado = :estado, cidade = :cidade, bairro = :bairro, rua = :rua, numero = :numero, complemento = :complemento 
                                            WHERE id = :id AND user_id = :user_id");
            $resultado = $query->execute(['id' => $endereco->getId(), 'user_id' => $endereco->getUserId(), 'tipo' => $endereco->getTipo(), 
                                        'cep' => $endereco->getCep(), 'estado' => $endereco->getEstado(), 'cidade' => $endereco->getCidade(), 
                                        'bairro' => $endereco->getBairro(), 'rua' => $endereco->getRua(), 'numero' => $endereco->getNumero(), 
                                        'complemento' => $endereco->getComplemento()]);   
            return $resultado;
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function deletarEndereco($endereco){
        try {
            $query = $this->conexao->prepare("DELETE FROM enderecos WHERE id = :id AND user_id = :userId");
            $resultado = $query->execute(['id' => $endereco->getId(), 'userId' => $endereco->getUserId()]);   
            return $resultado;
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    function listarEndereco($cpf){
        try {
            $query = $this->conexao->prepare("SELECT * from usuarios u join enderecos e on e.user_id = u.id 
                                            where u.cpf = :cpf");
            $query->execute(['cpf' => $cpf]);
            return $query;
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function listarEnderecoPorId($enderecoId, $userId){
        try {
            $query = $this->conexao->prepare("SELECT * FROM enderecos WHERE id = :id AND user_id = :userId LIMIT 1");

            $query->execute(['id' => $enderecoId, 'userId' => $userId]);
            return $query->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
   ?>