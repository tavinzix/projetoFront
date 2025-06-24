<?php
class carrinho_DAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }
    
    function verificaCarrinho(Carrinho $carrinho)
    {
        try {
            $query = $this->conexao->prepare("SELECT id FROM carrinho WHERE usuario_id = :userId LIMIT 1");

            $query->execute([
                'userId' => $carrinho->getUsuarioId()
            ]);

            return $query->fetchColumn();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function criarCarrinho(Carrinho $carrinho)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO carrinho (usuario_id) VALUES (:usuario_id)");

            $query->execute([
                'usuario_id' => $carrinho->getUsuarioId()
            ]);

            return $this->conexao->lastInsertId();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function verificarItemCarrinho(Carrinho $carrinho)
    {
        try {
            $query = $this->conexao->prepare("SELECT id, quantidade FROM carrinho_itens 
                                            WHERE carrinho_id = :carrinho_id AND produto_id = :produto_id");

            $query->execute([
                'carrinho_id' => $carrinho->getId(),
                'produto_id' => $carrinho->getProdutoId()
            ]);

            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function atualizarQuantidadeItemCarrinho(Carrinho $carrinho, $novaQuantidade)
    {
        try {
            $query = $this->conexao->prepare("UPDATE carrinho_itens SET quantidade = :quantidade WHERE produto_id = :itemId and carrinho_id = :carrinhoId");

            $resultado = $query->execute([
                'quantidade' => $novaQuantidade,
                'itemId' => $carrinho->getProdutoId(),
                'carrinhoId' => $carrinho->getId()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }



    function adicionarProduto(Carrinho $carrinho)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO carrinho_itens (carrinho_id, produto_id, quantidade, preco_unitario) 
                                            VALUES (:codcarrinho, :cod, :qtd, :preco_unitario)");

            $resultado = $query->execute([
                'codcarrinho' => $carrinho->getId(),
                'cod' => $carrinho->getProdutoId(),
                'qtd' => $carrinho->getQuantidade(),
                'preco_unitario' => $carrinho->getPrecoUnitario()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function removerItemCarrinho(Carrinho $carrinho)
    {
        try {
            $query = $this->conexao->prepare("DELETE FROM carrinho_itens WHERE produto_id = :itemId and carrinho_id = :carrinhoId");

            $resultado = $query->execute([
                'itemId' => $carrinho->getProdutoId(),
                'carrinhoId' => $carrinho->getId()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscaItensCarrinho($userId)
    {
        try {
            $query = $this->conexao->prepare("SELECT ci.*, p.*, u.id, p.nome, v.*, (SELECT imagem_url FROM produto_imagens WHERE produto_id = p.id ORDER BY ordem ASC LIMIT 1) AS imagem_url
                                            FROM carrinho_itens ci  
                                            JOIN carrinho c ON c.id = ci.carrinho_id 
                                            JOIN produtos p ON p.id = ci.produto_id
                                            JOIN vendedores_produtos vp on vp.produto_id = p.id
                                            JOIN vendedores v on v.id = vp.vendedor_id
                                            JOIN usuarios u ON u.id = c.usuario_id 
                                            WHERE u.id = :userId");

            $query->execute(['userId' => $userId]);

            return $query;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}