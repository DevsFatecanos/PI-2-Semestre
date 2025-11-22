<?php
// notificacao.php

// Recebe os dados do front
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['email'], $data['nome'], $data['pedido'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Dados inválidos']);
    exit;
}

$email = $data['email'];
$nome = $data['nome'];
$pedido = $data['pedido'];
$status = $data['status'];
$dataHora = $data['dataHora'] ?? date('d/m/Y H:i');

// Chave da API do Resend
$apiKey = 're_d5e82yzo_Dowh6pBxpNWoevDWu7GttJDJ';

// Corpo do email
$body = [
    "from" => "SuperSonic Transportes <onboarding@resend.com>",
    "to" => [$email],
    "subject" => "Atualização do Pedido #$pedido",
    "html" => "
        <h2>Olá, $nome!</h2>
        <p>O status do seu pedido <strong>#$pedido</strong> foi atualizado para: <strong>$status</strong>.</p>
        <p>Data/Hora da atualização: $dataHora</p>
        <p>Você pode acompanhar o seu pedido acessando a nossa plataforma.</p>
        <br>
        <p>Atenciosamente,<br>SuperSonic Transportes</p>
    "
];

// Envia via cURL
$ch = curl_init("https://api.resend.com/emails");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Email enviado via Resend']);
} else {
    http_response_code($httpCode);
    echo $response;
}