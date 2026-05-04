<?php
require_once 'models/UserBook.php';
require_once 'models/Book.php';

class MyLibraryController {
    private $pdo;
    private $userBookModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userBookModel = new UserBook($pdo);
    }

    public function index() {
        if (empty($_SESSION['user_id'])) {
            header("Location: ?action=login");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $allBooks = $this->userBookModel->getUserBooks($userId);
        
        $readBooks = [];
        $wishlistBooks = [];
        
        foreach ($allBooks as $book) {
            if ($book['status'] === 'read') {
                $readBooks[] = $book;
            } else {
                $wishlistBooks[] = $book;
            }
        }

        ob_start();
        require 'views/my_library/index.php';
        $content = ob_get_clean();
        require 'views/layout.php';
    }

    public function add() {
        if (empty($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Giriş yapmalısınız.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'], $_POST['status'])) {
            verifyCsrf();
            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'];
            $status = $_POST['status']; 
            
            $this->userBookModel->addBookToLibrary($userId, $bookId, $status);
            
            header("Location: ?action=my_library");
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'], $_POST['status'])) {
            verifyCsrf();
            $userId = $_SESSION['user_id'];
            $bookId = $_POST['book_id'];
            $status = $_POST['status'];
            
            $this->userBookModel->updateStatus($userId, $bookId, $status);
            header("Location: ?action=my_library");
            exit;
        }
    }
}
