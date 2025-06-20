<?php
class vendedor_DAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    function cadastrarVendedor(Vendedor $vendedor)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO vendedores (user_id, nome_loja, cnpj, email, telefone, cep, estado, cidade, bairro, rua, numero, categoria, descricao_loja, status, img_vendedor) 
                                            VALUES (:userId, :nomeLoja, :cnpj, :email, :telefone, :cep, :estado, :cidade, :bairro, :rua, :numero, :categoria, :descricaoLoja, 1, 'semImagem.jpg')");

            $resultado = $query->execute([
                'userId' => $vendedor->getUserId(),
                'nomeLoja' => $vendedor->getNomeLoja(),
                'cnpj' => $vendedor->getCnpj(),
                'email' => $vendedor->getEmail(),
                'telefone' => $vendedor->getTelefone(),
                'cep' => $vendedor->getCep(),
                'estado' => $vendedor->getEstado(),
                'cidade' => $vendedor->getCidade(),
                'bairro' => $vendedor->getBairro(),
                'rua' => $vendedor->getRua(),
                'numero' => $vendedor->getNumero(),
                'categoria' => $vendedor->getCategoria(),
                'descricaoLoja' => $vendedor->getDescricaoLoja()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    
}
