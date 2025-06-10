<?php
class Conexao
{
    private $servername = "localhost";
    private $port = "5432";
    private $username = "postgres";
    private $password = "123456";
    private $dbname = "projeto_if";

    public function conectar()
    {
        try {
            $conexao = new PDO("pgsql:host={$this->servername};
                            port={$this->port};
                            dbname={$this->dbname};
                            user={$this->username};
                            password={$this->password}");
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexao;
        } catch (PDOException $e) {
            die("Sistema não está funcionando ----- pgsql:host={$this->servername}; port={$this->port}; dbname={$this->dbname}; user={$this->username}; password={$this->password}"
                . $e->getMessage());
            return null;
        }
    }
}
