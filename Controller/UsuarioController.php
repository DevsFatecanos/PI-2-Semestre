<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../Model/Usuario.php';
require_once __DIR__ . '/../conexao.php';

class UsuarioController {
    public function registrar() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
                die("Preencha todos os campos obrigatórios!");
            }

            $nome = trim($_POST['nome']);
            $telefone = trim($_POST['telefone']);
            $documento = trim($_POST['documento']);
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha']);

            // Verifica se o e-mail já existe
            $check = $pdo->prepare("SELECT id FROM cliente WHERE email = :email");
            $check->bindParam(':email', $email);
            $check->execute();

            if ($check->rowCount() > 0) {
                ("Email já cadastrado!");
            }

            // Cria e salva
            $usuario = new Usuario($nome, $telefone, $documento, $email, $senha);

            if ($usuario->salvar($pdo)) {
                header("Location: ../view/login.html");
                exit;
            } else {
                echo "Erro ao registrar usuário.";
            }
        } else {
            echo "Método inválido.";
        }
    }
}

$controller = new UsuarioController();
if (isset($_GET['action']) && $_GET['action'] === 'registrar') {
    $controller->registrar();
}
?>
