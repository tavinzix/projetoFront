<?php
class categoria_DAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    function inserirCategoria(Categoria $categoria)
    {
        try {
            $query = $this->conexao->prepare(" INSERT INTO categorias (nome, descricao, imagem, url) VALUES (:nome, :descricao, :imagem, :url)");
            $resultado = $query->execute([
                'nome' => $categoria->getNome(),
                'descricao' => $categoria->getDescricao(),
                'imagem' => $categoria->getImagem(),
                'url' => $categoria->getUrl()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function editarCategoriaComImagem(Categoria $categoria)
    {
        try {
            $query = $this->conexao->prepare("UPDATE categorias SET nome = :nome, descricao = :descricao, imagem = :imagem, url = :url WHERE id = :id");
            $resultado = $query->execute([
                'id' => $categoria->getId(),
                'nome' => $categoria->getNome(),
                'descricao' => $categoria->getDescricao(),
                'imagem' => $categoria->getImagem(),
                'url' => $categoria->getUrl()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function editarCategoriaSemImagem(Categoria $categoria)
    {
        try {
            $query = $this->conexao->prepare("UPDATE categorias SET nome = :nome, descricao = :descricao, url = :url WHERE id = :id");
            $resultado = $query->execute([
                'id' => $categoria->getId(),
                'nome' => $categoria->getNome(),
                'descricao' => $categoria->getDescricao(),
                'url' => $categoria->getUrl()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function inativarCategoria($id)
    {
        try {
            $query = $this->conexao->prepare("UPDATE categorias SET status = 2 where id = :id");
            $resultado = $query->execute(['id' => $id->getId()]);
            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function ativarCategoria($id)
    {
        try {
            $query = $this->conexao->prepare("UPDATE categorias SET status = 1 where id = :id");
            $resultado = $query->execute(['id' => $id->getId()]);
            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function listarCategorias()
    {
        try {
            $query = $this->conexao->prepare("SELECT * FROM categorias WHERE status = 1");
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function listarCategoriaComStatus()
    {
        try {
            $query = $this->conexao->prepare("SELECT *, CASE WHEN status = '1' THEN 'Ativo'
                                             WHEN status = '2' THEN 'Inativo' END AS status_texto FROM categorias");

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    function listarCategoriaAtiva()
    {
        try {
            $query = $this->conexao->prepare("SELECT *, CASE WHEN status = '1' THEN 'Ativo'
                                             WHEN status = '2' THEN 'Inativo' END AS status_texto FROM categorias WHERE status = '1'");

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
