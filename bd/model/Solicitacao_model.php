<?php
require_once 'Vendedor_model.php';

class Solicitacao extends Vendedor{
    private $id;
    private $motivo_rejeicao;

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setMotivoRejeicao($motivo_rejeicao)
    {
        $this->motivo_rejeicao = $motivo_rejeicao;
    }

    public function getMotivoRejeicao()
    {
        return $this->motivo_rejeicao;
    }
    
}
