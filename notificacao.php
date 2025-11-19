<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// Recebe dados do dashboard
$input = json_decode(file_get_contents("php://input"), true);

$email      = $input["email"]      ?? null;
$nome       = $input["nome"]       ?? null;
$pedido     = $input["pedido"]     ?? null;
$status     = $input["status"]     ?? null;
$dataHora   = $input["dataHora"]   ?? date("d/m/Y H:i:s");

// Validação simples
if (!$email || !$pedido || !$status) {
    echo json_encode(["erro" => "Dados insuficientes"]);
    exit;
}

try {
    $mail = new PHPMailer(true);

    // Config SMTP — pode usar qualquer um (Gmail, Outlook, etc)
    $mail->isSMTP();
    $mail->Host       = "smtp.gmail.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "lucenaryan02@gmail.com";  
    $mail->Password   = "gnmmukjcoahytxaz";  
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;

    // Remetente
    $mail->setFrom("lucenaryan02@gmail.com", "SuperSonic Transportes");

    // Destinatário
    $mail->addAddress($email, $nome);

    // Conteúdo
    $mail->isHTML(true);
    $mail->Subject = "Atualizacao do pedido #$pedido";
    $mail->Body = "
        <h2>Olá, <strong>$nome</strong></h2>
        <p>O status do seu pedido <strong>#$pedido</strong> foi atualizado para:</p>
        <h3>$status</h3>
        <p><strong>Data/Hora:</strong> $dataHora</p>
        <br>
        <p>Obrigado por utilizar a SuperSonic Transportes. Fique Atento para novas Mensagens</p>
    ";

    $mail->send();

    echo json_encode(["sucesso" => true, "mensagem" => "Email enviado"]);
} 
catch (Exception $e) {
    echo json_encode(["erro" => $mail->ErrorInfo]);
}