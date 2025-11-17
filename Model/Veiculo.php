<?php
class Veiculo {
    public $id;
    public $modelo;
    public $placa;
    public $status;

    public function __construct($id, $modelo, $placa, $status) {
        $this->id = $id;
        $this->modelo = $modelo;
        $this->placa = $placa;
        $this->status = $status;
    }
}
?>