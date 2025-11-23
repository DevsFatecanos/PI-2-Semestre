<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Model/Usuario.php'; 
require_once __DIR__ . '/../conexao.php';

class UsuarioController {
    public $erro = "";
    private $debug = false; // Coloque true só para depurar

    // =================== REGISTRO ===================
    public function registrar() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $telefone = trim($_POST['telefone'] ?? '');
            $documento = trim($_POST['documento'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $senha = trim($_POST['senha'] ?? '');
            $role = trim($_POST['role'] ?? 'cliente');

            if (empty($nome) || empty($email) || empty($senha)) {
                $this->erro = "Preencha todos os campos obrigatórios!";
                return false;
            }

            $check = $pdo->prepare("SELECT id_usuario FROM usuario WHERE email = :email");
            $check->execute([':email' => $email]);

            if ($check->rowCount() > 0) {
                $this->erro = "Email já cadastrado!";
                return false;
            }

            $usuario = new Usuario($nome, $telefone, $documento, $email, $senha, $role);

            if ($usuario->salvar($pdo)) {
                header("Location: ../View/login.html");
                exit;
            } else {
                $this->erro = "Erro ao registrar usuário.";
                return false;
            }
        } else {
            $this->erro = "Método inválido.";
            return false;
        }
    }

    // =================== LOGIN ===================
    public function login() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $senha = trim($_POST['senha'] ?? '');

            if (empty($email) || empty($senha)) {
                $this->erro = "Preencha todos os campos!";
                return false;
            }

            $stmt = $pdo->prepare("SELECT id_usuario, nome, email, senha, role FROM usuario WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                $this->erro = "Usuário não encontrado!";
                return false;
            }

            if (password_verify($senha, $usuario['senha'])) {
                session_start();
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_role'] = $usuario['role'];
                $_SESSION['usuario_email'] = $usuario['email'];

                // Redirecionamento
                if ($usuario['role'] === 'admin') {
                    header("Location: ../View/dashboard.php");
                } else {
                    header("Location: ../View/home.html");
                }
                exit;
            } else {
                $this->erro = "Email ou senha incorretos!";
                return false;
            }
        } else {
            $this->erro = "Método inválido (esperado POST)";
            return false;
        }
    }
}

// =================== EXECUÇÃO ===================
$controller = new UsuarioController();
$action = $_GET['action'] ?? '';

ob_start(); // Evita erros de cabeçalho (header already sent)

switch ($action) {
    case 'registrar':
        $controller->registrar();
        break;
    case 'login':
        $controller->login();
        break;
    default:
        echo "Ação inválida.";
        break;
}

ob_end_flush();
?>
