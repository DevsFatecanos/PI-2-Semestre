<?php
class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $role;

    public function __construct($nome, $email, $senha, $role = 'cliente') {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->role = $role;
    }

    public function salvar($conn) {
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $this->nome, $this->email, $this->senha, $this->role);
        return $stmt->execute();
    }
}
