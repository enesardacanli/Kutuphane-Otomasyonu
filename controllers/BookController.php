<?php
require_once 'models/Book.php';
require_once 'models/Reservation.php';

class BookController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Book($pdo);
    }

    public function index() {
        $resModel = new Reservation($this->pdo);
        $resModel->expireOld();

        $tab = $_GET['tab'] ?? 'books';
        $selectedCategory = $_GET['category'] ?? null;
        $authorFilter = $_GET['author'] ?? null;
        $publisherFilter = $_GET['publisher'] ?? null;

        // Fetch filter options
        $distinctAuthors = $this->model->getDistinctAuthors();
        $distinctPublishers = $this->model->getDistinctPublishers();

        // Check active reservations for members
        $reservedBookIds = [];
        if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'member') {
            $userReservations = $resModel->getByUserId($_SESSION['user_id']);
            foreach ($userReservations as $r) {
                if ($r['status'] === Reservation::STATUS_ACTIVE) {
                    $reservedBookIds[] = (int) $r['book_id'];
                }
            }
        }

        if ($tab === 'categories' && empty($selectedCategory) && empty($authorFilter) && empty($publisherFilter)) {
            $categories = $this->model->getCategories();
            require 'views/books/index.php';
        } else {
            // "books" tab or active filters/category
            $books = $this->model->getAllFiltered($selectedCategory, $authorFilter, $publisherFilter);
            $categoryName = $selectedCategory ?? 'Tüm Kitaplar';
            require 'views/books/index.php';
        }
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $isbn = trim($_POST['isbn'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $category = trim($_POST['category'] ?? 'Genel');
            $publisher = trim($_POST['publisher'] ?? '');
            $stock_count = (int) ($_POST['stock_count'] ?? 0);

            $cover_image = $this->uploadCoverImage($isbn);

            $description = trim($_POST['description'] ?? '');

            if (!empty($isbn) && !empty($title) && !empty($author) && $stock_count >= 0) {
                $this->model->create($isbn, $title, $author, $category, $stock_count, $cover_image, $description, $publisher);
                header('Location: ?action=books');
                exit;
            }
        }
        $categories = $this->model->getCategories();
        require 'views/books/add.php';
    }

    public function edit() {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?action=books');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $isbn = trim($_POST['isbn'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $category = trim($_POST['category'] ?? 'Genel');
            $publisher = trim($_POST['publisher'] ?? '');
            $stock_count = (int) ($_POST['stock_count'] ?? 0);

            $cover_image = $this->uploadCoverImage($isbn);

            $description = trim($_POST['description'] ?? '');

            if (!empty($isbn) && !empty($title) && !empty($author) && $stock_count >= 0) {
                $this->model->update($id, $isbn, $title, $author, $category, $stock_count, $cover_image, $description, $publisher);
                header('Location: ?action=books&category=' . urlencode($category));
                exit;
            }
        }

        $book = $this->model->getById($id);
        $categories = $this->model->getCategories();
        require 'views/books/edit.php';
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=books');
            exit;
        }
        verifyCsrf();
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->model->delete($id);
        }
        header('Location: ?action=books');
        exit;
    }

    private function uploadCoverImage($isbn) {
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $allowedExts) && $_FILES['cover_image']['size'] <= 2 * 1024 * 1024) {
                $filename = 'cover_' . ($isbn ? $isbn . '_' : '') . uniqid() . '.' . $ext;
                if (!is_dir('uploads/covers')) {
                    @mkdir('uploads/covers', 0777, true);
                }
                $uploadPath = 'uploads/covers/' . $filename;
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                    return $uploadPath;
                }
            }
        }
        return null;
    }
}