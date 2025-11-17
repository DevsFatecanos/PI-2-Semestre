<?php
class Veiculo {
    public $id_veiculo;
    public $modelo;
    public $placa;
    public $status;

    public function __construct($id_veiculo, $modelo, $placa, $status) {
        $this->id_veiculo = $id_veiculo;
        $this->modelo = $modelo;
        $this->placa = $placa;
        $this->status = $status;
    }
}
?>