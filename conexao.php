<?php
$host = "aws-1-sa-east-1.pooler.supabase.com";
$port = "5432";
$dbname = "postgres";
$user = "postgres.oudhyeawauuzvkrhsgsk";
$password = "Medicina12@mendes"; // sua senha do Supabase

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
