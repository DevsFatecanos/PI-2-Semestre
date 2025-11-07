<?php
// REMOVA as linhas do Dotenv, pois as variáveis serão injetadas pelo Render.
// require_once __DIR__ . '/vendor/autoload.php'; // Remova este require se ele for SÓ para o Dotenv
// use Dotenv\Dotenv;
// $dotenv = Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// *** Utilize getenv() ou $_ENV diretamente. ***
// Se você está usando o Composer para outras dependências (além do Dotenv),
// mantenha o autoload.php no topo:
require_once __DIR__ . '/vendor/autoload.php';

// As variáveis DB_HOST, DB_PORT, etc., serão lidas diretamente do ambiente do servidor Render.
// getenv() é geralmente mais confiável que $_ENV em ambientes de servidor.
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASS');

try {
    // Conexão com o PostgreSQL
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);

    // Modo de erro: exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Opcional: Para testar se a conexão foi bem sucedida
    // echo "<div style='color:green; font-family:sans-serif;'>✅ Conexão ao Supabase OK!</div>";

} catch (PDOException $e) {
    echo "<div style='color:red; font-family:sans-serif;'>
              ❌ Erro ao conectar ao Supabase:<br>" . $e->getMessage() . "
          </div>";
    
    // Opcional: Se for um ambiente de produção, pare o script aqui.
    // die(); 
}
?>