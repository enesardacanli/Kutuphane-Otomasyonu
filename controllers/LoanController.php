<?php
require_once 'models/Loan.php';
require_once 'models/Book.php';
require_once 'models/User.php';
require_once 'models/Reservation.php';

class LoanController {
    private $loanModel;
    private $bookModel;
    private $userModel;
    private $reservationModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->loanModel = new Loan($pdo);
        $this->bookModel = new Book($pdo);
        $this->userModel = new User($pdo);
        $this->reservationModel = new Reservation($pdo);
    }

    public function index() {
        $this->reservationModel->expireOld();
        $loans = $this->loanModel->getAll();
        require 'views/loans/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $user_id = (int) trim($_POST['user_id']);
            $book_id = (int) trim($_POST['book_id']);
            $loan_date = trim($_POST['loan_date']);
            $expected_return_date = trim($_POST['expected_return_date']);

            if ($user_id > 0 && $book_id > 0 && !empty($loan_date) && !empty($expected_return_date)) {
                $available = $this->bookModel->getAvailableStock($book_id);
                if ($available > 0) {
                    $this->pdo->beginTransaction();
                    try {
                        $this->loanModel->create($user_id, $book_id, $loan_date, $expected_return_date);
                        $this->pdo->commit();
                        header('Location: ?action=loans');
                        exit;
                    } catch (Exception $e) {
                        $this->pdo->rollBack();
                        $error = "İşlem sırasında bir hata oluştu.";
                    }
                } else {
                    $error = "Kitap stokta bulunmuyor.";
                }
            }
        }

        $users = $this->userModel->getAll();
        $books = $this->bookModel->getAllWithAvailableStock();
        require 'views/loans/add.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: ?action=loans');
            exit;
        }

        $id = (int) $_GET['id'];
        $loan = $this->loanModel->getById($id);

        if (!$loan) {
            header('Location: ?action=loans');
            exit;
        }

        if (!empty($loan['actual_return_date'])) {
            header('Location: ?action=loans');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCsrf();
            $actual_return_date = trim($_POST['actual_return_date']);

            if (!empty($actual_return_date)) {
                $this->pdo->beginTransaction();
                try {
                    if ($this->loanModel->updateReturn($id, $actual_return_date)) {
                        $this->pdo->commit();
                        header('Location: ?action=loans');
                        exit;
                    } else {
                        $this->pdo->rollBack();
                    }
                } catch (Exception $e) {
                    $this->pdo->rollBack();
                }
            }
        }

        require 'views/loans/edit.php';
    }
}