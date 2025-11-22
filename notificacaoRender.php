<?php
require 'vendor/autoload.php'; // Certifique-se de que PHPMailer está instalado via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



$mail = new PHPMailer(true);

try {
    // Configuração do servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.resend.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'resend';
    $mail->Password   = 're_37L7VrdD_2h9nHAeDSecjEDesseBCjU6T';
    $mail->SMTPSecure = 'ssl'; // ou 'tls' se preferir porta 587
    $mail->Port       = 465;

    // Remetente e destinatário
    $mail->setFrom('onboarding@resend.dev', 'Seu App');
    $mail->addAddress($email, $nome); // $email e $nome vindos do seu JS

    // Conteúdo do email
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
    echo "Erro ao enviar email: {$mail->ErrorInfo}";
}
?>
