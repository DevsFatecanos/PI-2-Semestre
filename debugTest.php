<?php
require_once __DIR__ . '/conexao.php'; 

$email = 'seu-email-de-teste@exemplo.com';
$stmt = $pdo->prepare("SELECT email, senha FROM cliente WHERE email = :email");
$stmt->execute([':email' => $email]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Usuário NÃO encontrado para email: $email";
} else {
    echo "Usuário encontrado: " . htmlspecialchars($row['email']) . "<br>";
    echo "Senha armazenada no banco: " . htmlspecialchars($row['senha']) . "<br>";
    echo "Comprimento do hash: " . strlen($row['senha']) . " bytes<br>";
}
?>
