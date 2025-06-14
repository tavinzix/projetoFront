<?php
   Class formaPagamentoUsuario_DAO{
    private $conexao;

    public function __construct(PDO $conexao){
        $this->conexao = $conexao;  
    }

    function inserirForma(Formapagamento $formapagamento){
       try {
            $query = $this->conexao->prepare("INSERT INTO formas_pagamento (user_id, nome_titular, nome_cartao, numero_cartao, validade, cvv) 
                                            VALUES (:user_id, :nome_titular, :nome_cartao, :numero_cartao, :validade, :cvv)");

            $resultado = $query->execute(['user_id' => $formapagamento->getUserId(), 'nome_titular' => $formapagamento->getNomeTitular(),
                                        'nome_cartao' => $formapagamento->getNomeCartao(),'numero_cartao' => $formapagamento->getNumeroCartao(),
                                        'validade' => $formapagamento->getValidade(),'cvv' => $formapagamento->getCvv()]);
                       
            return $resultado;
            
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function alterarForma(FormaPagamento $formapagamento){
        try {
            $query = $this->conexao->prepare("UPDATE formas_pagamento SET nome_titular = :nome_titular, nome_cartao = :nome_cartao, numero_cartao = :numero_cartao, validade = :validade, cvv = :cvv
                                            WHERE id = :id AND user_id = :user_id");


            $resultado = $query->execute(['id' => $formapagamento->getId(), 'user_id' => $formapagamento->getUserId(), 
                                        'nome_titular' => $formapagamento->getNomeTitular(), 'nome_cartao' => $formapagamento->getNomeCartao(), 
                                        'numero_cartao' => $formapagamento->getNumeroCartao(), 'validade' => $formapagamento->getValidade(), 
                                        'cvv' => $formapagamento->getCvv()]);
              
            return $resultado;
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function deletarForma(FormaPagamento $formapagamento){
        try {
            $query = $this->conexao->prepare("DELETE FROM formas_pagamento WHERE id = :id AND user_id = :userId");
            $resultado = $query->execute(['id' => $formapagamento->getId(), 'userId' => $formapagamento->getUserId()]);   
            return $resultado;
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    function listarForma($cpf){
        try {
            $query = $this->conexao->prepare("SELECT * from usuarios u join formas_pagamento fp 
                                            on fp.user_id = u.id where u.cpf = :cpf");
            $query->execute(['cpf' => $cpf]);
            return $query;
        }catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
   ?>