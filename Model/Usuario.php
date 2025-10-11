<?php
class Usuario {
    public $nome;
    public $telefone;
    public $documento;
    public $email;
    public $senha;

    public function __construct($nome, $telefone, $documento, $email, $senha = 'cliente') {
        $this->nome = trim($nome);
        $this->telefone = trim($telefone);
        $this->documento = trim($documento);
        $this->email = trim($email);
        $this->senha = trim($senha);
    }

  public function salvar($pdo) {
    $sql = "INSERT INTO cliente (nome, telefone, documento, email, senha)
            VALUES (:nome, :telefone, :documento, :email, :senha)"; 
    $stmt = $pdo->prepare($sql);

    // Cria o hash da senha
    $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

    return $stmt->execute([
        ':nome' => $this->nome,
        ':telefone' => $this->telefone,
        ':documento' => $this->documento,
        ':email' => strtolower($this->email),
        ':senha' => $senhaHash
    ]);
}
}

