<?php
class Loan {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.name as user_name, b.title as book_title
            FROM loans l
            JOIN users u ON l.user_id = u.id
            JOIN books b ON l.book_id = b.id
            ORDER BY l.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($user_id, $book_id, $loan_date, $expected_return_date) {
        $stmt = $this->pdo->prepare("INSERT INTO loans (user_id, book_id, loan_date, expected_return_date) VALUES (?, ?, ?, ?)");
        return $stmt->execute([(int) $user_id, (int) $book_id, $loan_date, $expected_return_date]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.name as user_name, b.title as book_title
            FROM loans l
            JOIN users u ON l.user_id = u.id
            JOIN books b ON l.book_id = b.id
            WHERE l.id = ?
        ");
        $stmt->execute([(int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReturn($id, $actual_return_date) {
        $stmt = $this->pdo->prepare("UPDATE loans SET actual_return_date = ? WHERE id = ? AND actual_return_date IS NULL");
        $stmt->execute([$actual_return_date, (int) $id]);
        return $stmt->rowCount() > 0;
    }

    public function getActiveCountForBook($bookId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM loans WHERE book_id = ? AND actual_return_date IS NULL");
        $stmt->execute([(int) $bookId]);
        return (int) $stmt->fetchColumn();
    }

    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT l.*, b.title as book_title, b.author as book_author
            FROM loans l
            JOIN books b ON l.book_id = b.id
            WHERE l.user_id = ?
            ORDER BY l.id DESC
        ");
        $stmt->execute([(int) $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}