<?php
include("conexao.php");

// Validações básicas
if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
    die("Preencha todos os campos!");
}

$nome = trim($_POST['nome']);
$telefone = trim($_POST['telefone']);
$documento = trim($_POST['documento']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

// Verifica se email já existe
$check = $conn->prepare("SELECT id FROM cliente WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die("Email já cadastrado!");
}

// Insere sem hash (APENAS PARA TESTES)
$stmt = $conn->prepare("INSERT INTO cliente (nome, telefone, documento, email, senha) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nome,$telefone, $documento, $email, $senha);

if ($stmt->execute()) {
    
    exit();
} else {
    die("Erro no cadastro: " . $conn->error);
}
?>