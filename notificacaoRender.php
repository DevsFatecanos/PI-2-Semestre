<?php
// notificacaoRender.php
header('Content-Type: application/json');

// Permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Captura dados do corpo da requisição
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['email'], $input['nome'], $input['pedido'], $input['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados incompletos']);
    exit;
}

$email = $input['email'];
$nome = $input['nome'];
$pedido = $input['pedido'];
$status = $input['status'];
$dataHora = $input['dataHora'] ?? date('d/m/Y H:i:s');

// API Key do Resend (substitua pela sua)
$apiKey ='re_d5e82yzo_Dowh6pBxpNWoevDWu7GttJDJ';

// Configura o email usando o domínio de teste do Resend
$from = "SuperSonic Transportes <no-reply@resend.dev>";
$subject = "Atualização do Pedido #$pedido";
$html = "
    <h2>Olá, $nome!</h2>
    <p>O status do seu pedido <strong>#$pedido</strong> foi atualizado para: <strong>$status</strong>.</p>
    <p>Data/Hora da atualização: $dataHora</p>
    <p>Você pode acompanhar o seu pedido acessando a nossa plataforma.</p>
    <br>
    <p>Atenciosamente,<br>SuperSonic Transportes</p>
";

// Envia via cURL
$ch = curl_init("https://api.resend.com/emails");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "from" => $from,
    "to" => [$email],
    "subject" => $subject,
    "html" => $html
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true, 'message' => 'Email enviado com sucesso']);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao enviar email',
        'resend_response' => $response
    ]);
}
