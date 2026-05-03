<?php
require_once 'models/Reservation.php';
require_once 'models/Book.php';
require_once 'models/Loan.php';

class ReservationController {
    private $reservationModel;
    private $bookModel;
    private $loanModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->reservationModel = new Reservation($pdo);
        $this->bookModel = new Book($pdo);
        $this->loanModel = new Loan($pdo);
    }

    public function index() {
        $this->reservationModel->expireOld();
        $reservations = $this->reservationModel->getActive();
        $allReservations = $this->reservationModel->getAll();
        require 'views/reservations/index.php';
    }

    public function reserve() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=books');
            exit;
        }
        verifyCsrf();

        $book_id = (int) ($_POST['book_id'] ?? 0);
        $category = $_POST['category'] ?? '';

        if ($book_id <= 0) {
            header('Location: ?action=books');
            exit;
        }

        $this->reservationModel->expireOld();

        if ($this->reservationModel->hasActiveReservation($_SESSION['user_id'], $book_id)) {
            header('Location: ?action=books&category=' . urlencode($category));
            exit;
        }

        $available = $this->bookModel->getAvailableStock($book_id);
        if ($available > 0) {
            $this->reservationModel->create($_SESSION['user_id'], $book_id);
        }

        header('Location: ?action=my_reservations');
        exit;
    }

    public function complete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=reservations');
            exit;
        }
        verifyCsrf();

        $id = (int) ($_POST['reservation_id'] ?? 0);
        if ($id <= 0) {
            header('Location: ?action=reservations');
            exit;
        }

        $reservation = $this->reservationModel->getById($id);
        if (!$reservation || $reservation['status'] !== Reservation::STATUS_ACTIVE) {
            header('Location: ?action=reservations');
            exit;
        }

        $this->pdo->beginTransaction();
        try {
            $this->reservationModel->complete($id);
            $this->loanModel->create(
                $reservation['user_id'],
                $reservation['book_id'],
                date('Y-m-d'),
                date('Y-m-d', strtotime('+14 days'))
            );
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
        }

        header('Location: ?action=reservations');
        exit;
    }

    public function myReservations() {
        $this->reservationModel->expireOld();
        $reservations = $this->reservationModel->getByUserId($_SESSION['user_id']);
        require 'views/reservations/my.php';
    }

    public function cancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?action=my_reservations');
            exit;
        }
        verifyCsrf();

        $id = (int) ($_POST['reservation_id'] ?? 0);
        if ($id > 0) {
            $this->reservationModel->cancel($id, $_SESSION['user_id']);
        }

        header('Location: ?action=my_reservations');
        exit;
    }
}
