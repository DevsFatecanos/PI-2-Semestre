<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer via Composer

// Lê o JSON enviado pelo fetch
$input = json_decode(file_get_contents('php://input'), true);

// Extrai os campos
$email = $input['email'] ?? null;
$nome = $input['nome'] ?? null;
$pedido = $input['pedido'] ?? null;
$status = $input['status'] ?? null;
$valor = $input['valor'] ?? null;
$dataHora = $input['dataHora'] ?? null;

// Validação básica
if (!$email || !$nome) {
    http_response_code(400);
    echo "Email ou nome não enviados!";
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.resend.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'resend';
    $mail->Password   = 're_37L7VrdD_2h9nHAeDSecjEDesseBCjU6T';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->setFrom('onboarding@resend.dev', 'Seu App');
    $mail->addAddress($email, $nome);

    $mail->isHTML(true);
    $mail->Subject = 'Atualização do pedido #' . $pedido;
    $mail->Body    = "
        <p>Olá <strong>$nome</strong>,</p>
        <p>O status do seu pedido <strong>#$pedido</strong> foi atualizado para <strong>$status</strong>.</p>
        <p>Valor: R$ $valor</p>
        <p>Data/Hora: $dataHora</p>
    ";

    $mail->send();
    echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
    http_response_code(500);
    echo "Erro ao enviar email: {$mail->ErrorInfo}";
}
?>
