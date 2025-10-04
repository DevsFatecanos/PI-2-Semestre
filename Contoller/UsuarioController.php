<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../conexao.php';

class UsuarioController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $conn;

            $nome  = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
            $role  = $_POST['role'] ?? 'cliente';

            $usuario = new Usuario($nome, $email, $senha, $role);

            if ($usuario->salvar($conn)) {
                echo "Usuário registrado com sucesso!";
                header("Location: /view/login.html");
                exit;
            } else {
                echo "Erro ao registrar usuário.";
            }
        }
    }
}
