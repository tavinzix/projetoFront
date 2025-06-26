<?php
class usuario_DAO
{
    private $conexao;

    public function __construct(PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    function cadastrarUsuario(Usuario $usuario)
    {
        try {
            $query = $this->conexao->prepare("INSERT INTO usuarios (nome_completo, email, senha, telefone, dt_nasc, cpf)
                                            VALUES (:nome, :email, :senha, :telefone, :nascimento, :cpf)");

            $resultado = $query->execute([
                'nome' => $usuario->getNomeCompleto(),
                'email' => $usuario->getEmail(),
                'senha' => $usuario->getSenha(),
                'telefone' => $usuario->getTelefone(),
                'nascimento' => $usuario->getDtNasc(),
                'cpf' => $usuario->getCpf()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function editarTodosOsCampos(Usuario $usuario)
    {
        try {
            $query = $this->conexao->prepare("UPDATE usuarios SET nome_completo = :nome, email = :email, senha = :nova_senha, telefone = :telefone, img_user = :imagem WHERE id = :id");
            $resultado = $query->execute([
                'id' => $usuario->getId(),
                'nome' => $usuario->getNomeCompleto(),
                'email' => $usuario->getEmail(),
                'nova_senha' => $usuario->getSenha(),
                'telefone' => $usuario->getTelefone(),
                'imagem' => $usuario->getImgUser()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function editarSemSenha(Usuario $usuario)
    {
        try {
            $query = $this->conexao->prepare("UPDATE usuarios SET nome_completo = :nome, email = :email, telefone = :telefone, img_user = :imagem WHERE id = :id");
            $resultado = $query->execute([
                'id' => $usuario->getId(),
                'nome' => $usuario->getNomeCompleto(),
                'email' => $usuario->getEmail(),
                'telefone' => $usuario->getTelefone(),
                'imagem' => $usuario->getImgUser()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function editarSemImagemComSenha(Usuario $usuario)
    {
        try {
            $query = $this->conexao->prepare("UPDATE usuarios SET nome_completo = :nome, email = :email, senha = :nova_senha, telefone = :telefone WHERE id = :id");
            $resultado = $query->execute([
                'id' => $usuario->getId(),
                'nome' => $usuario->getNomeCompleto(),
                'email' => $usuario->getEmail(),
                'nova_senha' => $usuario->getSenha(),
                'telefone' => $usuario->getTelefone()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function editarSemImagemSemSenha(Usuario $usuario)
    {
        try {
            $query = $this->conexao->prepare("UPDATE usuarios SET nome_completo = :nome, email = :email, telefone = :telefone WHERE id = :id");
            $resultado = $query->execute([
                'id' => $usuario->getId(),
                'nome' => $usuario->getNomeCompleto(),
                'email' => $usuario->getEmail(),
                'telefone' => $usuario->getTelefone()
            ]);

            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function removerFoto(Usuario $usuario)
    {
        try {
            $query = $this->conexao->prepare("UPDATE usuarios SET img_user = 'avatar.jpg' WHERE id = :id");
            $resultado = $query->execute(['id' => $usuario->getId()]);
            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function buscaUsuario($cpf)
    {
        try {
            $query = $this->conexao->prepare("SELECT *, to_char(dt_nasc, 'DD/MM/YYYY') as data_nascimento FROM usuarios 
                                            WHERE cpf = :cpf");
            $query->execute(['cpf' => $cpf]);
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function verificaLogin(Usuario $usuario){
        try {
            $query = $this->conexao->prepare("SELECT * from usuarios WHERE cpf = :cpf and senha = :senha LIMIT 1");
            $query->execute(['cpf' => $usuario->getCpf(), 'senha' => $usuario->getSenha()]);
            
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function verificaUsuarioAdm($user_id){
        try {
            $query = $this->conexao->prepare("SELECT COUNT(*) FROM administradores WHERE user_id = :userId AND status = '1'");
            $query->execute(['userId' => $user_id]);
            
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function verificaUsuarioVendedor($user_id){
        try {
            $query = $this->conexao->prepare("SELECT COUNT(*) FROM vendedores WHERE user_id = :userId AND status = '1'");
            $query->execute(['userId' => $user_id]);
            
            $resultado = $query->fetchColumn();
            return $resultado;
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }



}
