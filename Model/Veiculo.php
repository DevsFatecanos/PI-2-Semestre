<?php
class Veiculo {
    public $id;
    public $nome;
    public $marca;
    public $placa;
    public $capacidade;
    public $ano;
    public $status;

    public function __construct($id, $nome, $marca, $placa, $status, $capacidade = null, $ano = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->marca = $marca;
        $this->placa = $placa;
        $this->status = $status;
        $this->capacidade = $capacidade;
        $this->ano = $ano;
    }
}

?>