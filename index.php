<?php
require_once 'db.php';
require_once 'models/User.php';

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function requireLogin() {
    if (empty($_SESSION['user_id'])) {
        header('Location: ?action=login');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['user_role'] !== User::ROLE_ADMIN) {
        http_response_code(403);
        die("Bu sayfaya erişim yetkiniz yok.");
    }
}

function requireStaff() {
    requireLogin();
    if (!in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)) {
        http_response_code(403);
        die("Bu sayfaya erişim yetkiniz yok.");
    }
}

function verifyCsrf() {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die("Geçersiz istek.");
    }
}

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->loginForm();
        break;
    case 'do_login':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->login();
        break;
    case 'logout':
        require_once 'controllers/AuthController.php';
        $controller = new AuthController($pdo);
        $controller->logout();
        break;
    case 'books':
        requireLogin();
        require_once 'controllers/BookController.php';
        $controller = new BookController($pdo);
        $controller->index();
        break;
    case 'add_book':
        requireStaff();
        require_once 'controllers/BookController.php';
        $controller = new BookController($pdo);
        $controller->add();
        break;
    case 'edit_book':
        requireStaff();
        require_once 'controllers/BookController.php';
        $controller = new BookController($pdo);
        $controller->edit();
        break;
    case 'delete_book':
        requireStaff();
        require_once 'controllers/BookController.php';
        $controller = new BookController($pdo);
        $controller->delete();
        break;
    case 'users':
        requireStaff();
        require_once 'controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->index();
        break;
    case 'add_user':
        requireStaff();
        require_once 'controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->add();
        break;
    case 'edit_user':
        requireStaff();
        require_once 'controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->edit();
        break;
    case 'delete_user':
        requireStaff();
        require_once 'controllers/UserController.php';
        $controller = new UserController($pdo);
        $controller->delete();
        break;
    case 'loans':
        requireStaff();
        require_once 'controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->index();
        break;
    case 'add_loan':
        requireStaff();
        require_once 'controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->add();
        break;
    case 'edit_loan':
        requireStaff();
        require_once 'controllers/LoanController.php';
        $controller = new LoanController($pdo);
        $controller->edit();
        break;
    case 'reservations':
        requireStaff();
        require_once 'controllers/ReservationController.php';
        $controller = new ReservationController($pdo);
        $controller->index();
        break;
    case 'complete_reservation':
        requireStaff();
        require_once 'controllers/ReservationController.php';
        $controller = new ReservationController($pdo);
        $controller->complete();
        break;
    case 'reserve_book':
        requireLogin();
        require_once 'controllers/ReservationController.php';
        $controller = new ReservationController($pdo);
        $controller->reserve();
        break;
    case 'my_reservations':
        requireLogin();
        require_once 'controllers/ReservationController.php';
        $controller = new ReservationController($pdo);
        $controller->myReservations();
        break;
    case 'cancel_reservation':
        requireLogin();
        require_once 'controllers/ReservationController.php';
        $controller = new ReservationController($pdo);
        $controller->cancel();
        break;
    case 'my_library':
        requireLogin();
        require_once 'controllers/MyLibraryController.php';
        $controller = new MyLibraryController($pdo);
        $controller->index();
        break;
    case 'add_to_library':
        requireLogin();
        require_once 'controllers/MyLibraryController.php';
        $controller = new MyLibraryController($pdo);
        $controller->add();
        break;
    case 'update_library_status':
        requireLogin();
        require_once 'controllers/MyLibraryController.php';
        $controller = new MyLibraryController($pdo);
        $controller->update();
        break;
    default:
        if (!empty($_SESSION['user_id'])) {
            header('Location: ?action=books');
            exit;
        }
        header('Location: ?action=login');
        exit;
}