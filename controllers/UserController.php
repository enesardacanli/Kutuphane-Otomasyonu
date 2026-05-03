<?php
require_once 'models/User.php';

class UserController {
    private $model;

    public function __construct($pdo) {
        $this->model = new User($pdo);
    }

    public function index() {
        if ($_SESSION['user_role'] === User::ROLE_STAFF) {
            $users = $this->model->getMembers();
        } else {
            $users = $this->model->getAll();
        }
        require 'views/users/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            $allowedRoles = $_SESSION['user_role'] === User::ROLE_ADMIN ? [User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_MEMBER] : [User::ROLE_MEMBER];

            if (!empty($name) && !empty($email) && !empty($password) && in_array($role, $allowedRoles, true) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->model->create($name, $email, $password, $role);
                header('Location: ?action=users');
                exit;
            }
        }
        require 'views/users/add.php';
    }

    public function edit() {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?action=users');
            exit;
        }

        $user = $this->model->getById($id);
        if (!$user) {
            header('Location: ?action=users');
            exit;
        }

        if ($_SESSION['user_role'] === User::ROLE_STAFF && $user['role'] !== User::ROLE_MEMBER) {
            http_response_code(403);
            die("Bu sayfaya erişim yetkiniz yok.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role = $_POST['role'] ?? '';
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            $allowedRoles = $_SESSION['user_role'] === User::ROLE_ADMIN ? [User::ROLE_ADMIN, User::ROLE_STAFF, User::ROLE_MEMBER] : [User::ROLE_MEMBER];

            if (!empty($name) && !empty($email) && in_array($role, $allowedRoles, true) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->model->update($id, $name, $email, $role, $password);
                header('Location: ?action=users');
                exit;
            }
        }

        require 'views/users/edit.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=users');
            exit;
        }
        verifyCsrf();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $user = $this->model->getById($id);
            if ($user) {
                if ($_SESSION['user_role'] === User::ROLE_STAFF && $user['role'] !== User::ROLE_MEMBER) {
                    http_response_code(403);
                    die("Bu sayfaya erişim yetkiniz yok.");
                }
                $this->model->delete($id);
            }
        }
        header('Location: ?action=users');
        exit;
    }
}