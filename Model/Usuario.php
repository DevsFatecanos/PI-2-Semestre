<?php
class Usuario {
    public $nome;
    public $telefone; // string é melhor que int
    public $documento;
    public $email;
    public $senha;
    public $role;

    public function __construct($nome, $telefone, $documento, $email, $senha, $role = 'cliente') {
        $this->nome = trim($nome);
        $this->telefone = trim($telefone);
        $this->documento = trim($documento);
        $this->email = trim($email);
        $this->senha = trim($senha);
        $this->role = trim($role);
    }

    public function salvar($pdo) {
        $sql = "INSERT INTO cliente (nome, telefone, documento, email, senha, role)
                VALUES (:nome, :telefone, :documento, :email, :senha, :role)";
        $stmt = $pdo->prepare($sql);

        // Cria o hash da senha
        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

        return $stmt->execute([
            ':nome' => $this->nome,
            ':telefone' => $this->telefone,
            ':documento' => $this->documento,
            ':email' => strtolower($this->email),
            ':senha' => $senhaHash,
            ':role' => $this->role
        ]);
    }
}
?>
