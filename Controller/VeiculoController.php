<?php
require_once __DIR__ . '/../Model/Veiculo.php';
require_once __DIR__ . '/../conexao.php';

class VeiculoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM veiculo ORDER BY id_veiculo ASC");
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $veiculos = [];

        foreach ($dados as $v) {
            $veiculos[] = new Veiculo($v['id_veiculo'], $v['modelo'], $v['placa'], $v['status']);
        }

        return $veiculos;
    }

    public function adicionar(Veiculo $v) {
        $sql = "INSERT INTO veiculo (modelo, placa, status) VALUES (:modelo, :placa, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':modelo' => $v->modelo,
            ':placa' => $v->placa,
            ':status' => $v->status
        ]);
    }

    public function atualizar(Veiculo $v) {
        $sql = "UPDATE veiculo SET modelo=:modelo, placa=:placa, status=:status WHERE id_veiculo=:id_veiculo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id_veiculo' => $v->id_veiculo,
            ':modelo' => $v->modelo,
            ':placa' => $v->placa,
            ':status' => $v->status
        ]);
    }

    public function remover($id_veiculo) {
        $sql = "DELETE FROM veiculo WHERE id_veiculo=:id_veiculo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_veiculo' => $id_veiculo]);
    }
}
?>