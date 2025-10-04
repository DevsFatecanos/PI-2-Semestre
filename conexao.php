<?php
$servidor = "localhost";   // Host do banco
$usuario  = "root";        // Usuário do banco
$senha    = "";            // Senha do banco
$banco    = "banco";       // Nome do banco

try {
    // Criando a conexão
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);

    // Configura o modo de erro para lançar exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    die("Falha na conexão: " . $e->getMessage());
}
?>
