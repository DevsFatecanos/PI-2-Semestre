<?php
$servidor = "localhost";       // Mude para o host do seu banco (ex: mysql.seusite.com)
$usuario = "root";             // Mude para o usuário do banco fornecido pela hospedagem
$senha = "";                   // Coloque a senha do banco
$banco = "nome_do_banco";      // O nome do banco que você criou no cPanel ou painel da hospedagem


// Criar conexão
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
} else {
    // echo "Conexão bem-sucedida!";
}
?>
