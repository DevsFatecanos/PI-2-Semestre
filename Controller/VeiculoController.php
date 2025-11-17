<?php
require_once __DIR__ . '/../Model/Veiculo.php';
require_once __DIR__ . '/../conexao.php';

class VeiculoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ================= LISTAR ====================
    public function listar() {
        // coluna correta no PostgreSQL
        $stmt = $this->pdo->query("SELECT * FROM veiculo ORDER BY id_veiculo ASC");
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $veiculos = [];

        foreach ($dados as $v) {
            $veiculos[] = new Veiculo(
                $v['id_veiculo'],
                $v['nome'],
                $v['marca'],
                $v['placa'],
                $v['status']
            );
        }

        return $veiculos;
    }

    // ================ ADICIONAR ====================
    public function adicionar(Veiculo $v) {
        $sql = "INSERT INTO veiculo (nome, marca, placa, capacidade, ano, status)
                VALUES (:nome, :marca, :placa, :capacidade, :ano, :status)";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $v->nome,
            ':marca' => $v->marca,
            ':placa' => $v->placa,
            ':capacidade' => $v->capacidade,
            ':ano' => $v->ano,
            ':status' => $v->status
        ]);
    }

    // ================ ATUALIZAR ====================
    public function atualizar(Veiculo $v) {
        $sql = "UPDATE veiculo 
                SET nome=:nome, marca=:marca, placa=:placa, capacidade=:capacidade, ano=:ano, status=:status
                WHERE id_veiculo=:id";
        
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':id' => $v->id,
            ':nome' => $v->nome,
            ':marca' => $v->marca,
            ':placa' => $v->placa,
            ':capacidade' => $v->capacidade,
            ':ano' => $v->ano,
            ':status' => $v->status
        ]);
    }

    // ================ REMOVER ====================
    public function remover($id) {
        $sql = "DELETE FROM veiculo WHERE id_veiculo=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}
?>
