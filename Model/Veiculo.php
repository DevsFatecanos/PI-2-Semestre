<?php
class Veiculo {
    public $id_veiculo;
    public $modelo;
    public $placa;
    public $valor_por_km;
    public $status;

    public function __construct($id_veiculo, $modelo, $placa,$valor_por_km, $status) {
        $this->id_veiculo = $id_veiculo;
        $this->modelo = $modelo;
        $this->placa = $placa;
        $this->status = $status;
        $this->valor_por_km = $valor_por_km;
    }
}
?>