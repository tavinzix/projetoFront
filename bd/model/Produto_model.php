<?php
class Produto
{
    private $id;
    private $vendedor_id;
    private $nome;
    private $descricao;
    private $categoira_id;
    private $marca;
    private $imagem_url;
    private $ordem;
    private $preco;
    private $estoque;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setVendedorId($vendedor_id)
    {
        $this->vendedor_id = $vendedor_id;
    }

    public function getVendedorId()
    {
        return $this->vendedor_id;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setCategoriaId($categoria_id)
    {
        $this->categoira_id = $categoria_id;
    }

    public function getCategoriaId()
    {
        return $this->categoira_id;
    }

    public function setMarca($marca)
    {
        $this->marca = $marca;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function setImagemUrl($imagem_url)
    {
        $this->imagem_url = $imagem_url;
    }
    public function getImagemUrl()
    {
        return $this->imagem_url;
    }

    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
    }

    public function getOrdem()
    {
        return $this->ordem;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setEstoque($estoque)
    {
        $this->estoque = $estoque;
    }

    public function getEstoque()
    {
        return $this->estoque;
    }
}
