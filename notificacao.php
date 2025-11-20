<?php
require 'vendor/autoload.php';

use Resend\Resend;

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$input = json_decode(file_get_contents("php://input"), true);

$email    = $input["email"]    ?? null;
$nome     = $input["nome"]     ?? null;
$pedido   = $input["pedido"]   ?? null;
$status   = $input["status"]   ?? null;
$dataHora = $input["dataHora"] ?? date("d/m/Y H:i:s");

if (!$email || !$pedido || !$status) {
    echo json_encode(["erro" => "Dados insuficientes"]);
    exit;
}

$resend = new Resend("re_FShomgwa_B5QdJTFw1gnkdG3qeKp2vSJx"); // coloque sua chave aqui

$html = "
<div style='font-family: Arial; padding: 20px; background: #f5f5f5;'>
    <div style='max-width: 600px; margin: auto; background: white; padding: 25px; border-radius: 10px;'>
        
        <h2 style='color: #1e40af;'>Atualização do Pedido #$pedido</h2>

        <p>Olá, <strong>$nome</strong>!</p>

        <p>O status do seu pedido foi alterado para:</p>

        <div style='padding: 12px 18px; background: #e0e7ff; border-left: 4px solid #1e3a8a; font-size: 18px;'>
            <strong>$status</strong>
        </div>

        <p><strong>Data/Hora da alteração:</strong> $dataHora</p>

        <br>

        <a href='https://pi-2-semestre-zjto.onrender.com/' 
           style='display: inline-block; padding: 12px 18px; background: #1e40af; color: white; 
                  text-decoration: none; border-radius: 6px; font-size: 16px;'>
            Acessar o Pedido
        </a>

        <br><br>

        <p style='color: #555;'>Obrigado por usar a SuperSonic Transportes 🚚⚡</p>
    </div>
</div>
";

try {
    $resend->emails->send([
        'from' => 'SuperSonic <onboarding@resend.dev>',
        'to'   => $email,
        'subject' => "Atualização do pedido #$pedido",
        'html' => $html,
    ]);

    echo json_encode(["sucesso" => true, "mensagem" => "Email enviado via Resend"]);
} 
catch (Exception $e) {
    echo json_encode(["erro" => $e->getMessage()]);
}