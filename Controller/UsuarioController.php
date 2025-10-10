<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Model/Usuario.php'; 
require_once __DIR__ . '/../conexao.php';

class UsuarioController {
    public $erro = "";

    public function registrar() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
                echo "Preencha todos os campos obrigatórios!";
                return false;
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
                echo "Email já cadastrado!";
                return false;
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

public function login() {
    global $pdo;
    $debug = true; // coloca false depois que tudo estiver ok

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->erro = "Preencha todos os campos!";
            if ($debug) echo "DEBUG: email or senha vazios<br>";
            return false;
        }

        // Busca usuário (normalizando email)
        $stmt = $pdo->prepare("SELECT id, nome, email, senha FROM cliente WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            if ($debug) echo "DEBUG: Usuário NÃO encontrado para email: " . htmlspecialchars($email) . "<br>";
            $this->erro = "Email ou senha incorretos!";
            return false;
        }

        if ($debug) {
            echo "DEBUG: Usuário encontrado. email=" . htmlspecialchars($usuario['email']) . "<br>";
            echo "DEBUG: senha armazenada (hash) = " . htmlspecialchars($usuario['senha']) . "<br>";
        }

        $ok = password_verify($senha, $usuario['senha']);

        if ($debug) echo "DEBUG: password_verify result = " . ($ok ? 'true' : 'false') . "<br>";

        if ($ok) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            if ($debug) {
                echo "<div style='color:green'>Login OK. Redirecionando...</div>";
                echo "<script>setTimeout(()=>location.href='../view/PagianaDeFrete.html', 800)</script>";
            } else {
                header("Location: ../view/PagianaDeFrete.html");
            }
            exit;
        } else {
            $this->erro = "Email ou senha incorretos!";
            return false;
        }
    } else {
        if ($debug) echo "DEBUG: Método inválido (esperado POST).<br>";
    }
}
}

$controller = new UsuarioController();

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'registrar') {
        $controller->registrar();
    } elseif ($_GET['action'] === 'login') {
        $controller->login();
    } else {
        echo "Ação inválida.";
    }
} else {
    echo "Nenhuma ação informada.";
}
?>
