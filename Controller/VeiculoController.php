<?php
require_once __DIR__ . '/../Model/Veiculo.php';
require_once __DIR__ . '/../conexao.php';

class VeiculoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    // =============== ESTATÍSTICAS SOBRE VEICULOS =================

public function contarDisponiveis() {
    $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM veiculo WHERE status = 'disponivel'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

public function contarManutencao() {
    $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM veiculo WHERE status = 'manutencao'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

public function contarEmUso() {
    $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM veiculo WHERE status = 'em uso'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM veiculo ORDER BY id_veiculo ASC");
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $veiculos = [];

        foreach ($dados as $v) {
            $veiculos[] = new Veiculo($v['id_veiculo'], $v['modelo'], $v['placa'],$v['valor_por_km'] ,$v['status']);
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
 if (isset($_GET['action'])) {
    $controller = new VeiculoController($pdo);

    if ($_GET['action'] === 'adicionar') {
        $v = new Veiculo(
            null,
            $_POST['modelo'],
            $_POST['placa'],
            $_POST['valor_por_km'],
            $_POST['status']
        );
        $controller->adicionar($v);
        header("Location: ../View/dashboard.php");
        exit;
    }

    if ($_GET['action'] === 'remover') {
        $controller->remover($_POST['id']);
        header("Location: ../View/dashboard.php");
        exit;
    }
}
   

?>