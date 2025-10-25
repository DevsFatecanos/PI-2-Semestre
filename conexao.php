<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

try {
    // Conexão com o PostgreSQL do Supabase (via pooler)
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);

    // Modo de erro: exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<div style='color:red; font-family:sans-serif;'>
            ❌ Erro ao conectar ao Supabase:<br>" . $e->getMessage() . "
          </div>";
}
?>
