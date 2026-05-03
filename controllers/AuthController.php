<?php
require_once 'models/User.php';

class AuthController {
    private $model;

    public function __construct($pdo) {
        $this->model = new User($pdo);
    }

    public function loginForm() {
        if (!empty($_SESSION['user_id'])) {
            header('Location: ?action=books');
            exit;
        }
        require 'views/login.php';
    }

    public function login() {
        if (!empty($_SESSION['user_id'])) {
            header('Location: ?action=books');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=login');
            exit;
        }

        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            header('Location: ?action=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = "E-posta ve şifre zorunludur.";
            require 'views/login.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Geçersiz e-posta formatı.";
            require 'views/login.php';
            return;
        }

        $user = $this->model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = "E-posta veya şifre hatalı.";
            require 'views/login.php';
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        header('Location: ?action=books');
        exit;
    }

    public function logout() {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header('Location: ?action=login');
        exit;
    }
}
