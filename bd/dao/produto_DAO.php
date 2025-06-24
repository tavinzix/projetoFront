<?php
class produto_DAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    function inserirProduto(Produto $produto)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO produtos (nome, descricao, categoria_id, marca)
                                            VALUES (:nome, :descricao, :categoria_id, :marca)");

            $resultado = $query->execute([
                'nome' => $produto->getNome(),
                'descricao' => $produto->getDescricao(),
                'categoria_id' => $produto->getCategoriaId(),
                'marca' => $produto->getMarca()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function inserirImagens(Produto $produto)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO produto_imagens (produto_id, imagem_url, ordem) 
                                            VALUES (:produto_id, :imagem_url, :ordem)");

            $resultado = $query->execute([
                'produto_id' => $produto->getId(),
                'imagem_url' => $produto->getImagemUrl(),
                'ordem' => $produto->getOrdem()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function cadastrarVendedorProduto(Produto $produto, $produto_id)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO vendedores_produtos (vendedor_id, produto_id, preco, estoque, ativo)
                                            VALUES (:vendedor_id, :produto_id, :preco, :estoque, '1')");

            $resultado = $query->execute([
                'vendedor_id' => $produto->getVendedorId(),
                'produto_id' => $produto_id,
                'preco' => $produto->getPreco(),
                'estoque' => $produto->getEstoque()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscarProdutoPorId($produto_id)
    {
        try {
            $query = $this->conexao->prepare("SELECT p.*, vp.*, v.*, pi.*, p.id as produto_id FROM produtos p 
                                        JOIN vendedores_produtos vp ON vp.produto_id = p.id
                                        JOIN vendedores v on vp.vendedor_id = v.id
                                        LEFT JOIN produto_imagens pi ON p.id = pi.produto_id 
                                        WHERE p.id = :produto_id");
            $query->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscarImagensProduto($produto_id)
    {
        try {
            $query = $this->conexao->prepare("SELECT * FROM produto_imagens WHERE produto_id = :produto_id ORDER BY ordem ASC");
            $query->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscarProdutosNomeLoja($pesquisa)
    {
        try {
            $query = $this->conexao->prepare("SELECT p.*, vp.*, pi.*, p.id as produto_id FROM produtos p
                                        JOIN vendedores_produtos vp ON vp.produto_id = p.id
                                        JOIN vendedores v on vp.vendedor_id = v.id
                                        LEFT JOIN produto_imagens pi ON p.id = pi.produto_id AND pi.ordem = 1
                                        WHERE p.nome ILIKE :pesquisa OR v.nome_loja ILIKE :pesquisa");
            $query->bindParam(':pesquisa', $pesquisa, PDO::PARAM_STR);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
}
