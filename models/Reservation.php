<?php
class Reservation {
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_COMPLETED = 'completed';

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function expireOld() {
        $stmt = $this->pdo->prepare("UPDATE reservations SET status = '" . self::STATUS_EXPIRED . "' WHERE status = '" . self::STATUS_ACTIVE . "' AND expire_date < NOW()");
        $stmt->execute();
    }

    public function create($userId, $bookId) {
        $stmt = $this->pdo->prepare("INSERT INTO reservations (user_id, book_id, reservation_date, expire_date, status) VALUES (:user_id, :book_id, NOW(), DATE_ADD(NOW(), INTERVAL 24 HOUR), '" . self::STATUS_ACTIVE . "')");
        return $stmt->execute([
            'user_id' => (int) $userId,
            'book_id' => (int) $bookId
        ]);
    }

    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.name as user_name, b.title as book_title
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN books b ON r.book_id = b.id
            ORDER BY r.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive() {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.name as user_name, b.title as book_title
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN books b ON r.book_id = b.id
            WHERE r.status = '" . self::STATUS_ACTIVE . "'
            ORDER BY r.expire_date ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, b.title as book_title, b.author as book_author
            FROM reservations r
            JOIN books b ON r.book_id = b.id
            WHERE r.user_id = :user_id
            ORDER BY r.id DESC
        ");
        $stmt->execute(['user_id' => (int) $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.name as user_name, b.title as book_title, b.id as book_id
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN books b ON r.book_id = b.id
            WHERE r.id = :id
        ");
        $stmt->execute(['id' => (int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function complete($id) {
        $stmt = $this->pdo->prepare("UPDATE reservations SET status = '" . self::STATUS_COMPLETED . "' WHERE id = :id AND status = '" . self::STATUS_ACTIVE . "'");
        $stmt->execute(['id' => (int) $id]);
        return $stmt->rowCount() > 0;
    }

    public function getActiveCountForBook($bookId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE book_id = :book_id AND status = '" . self::STATUS_ACTIVE . "'");
        $stmt->execute(['book_id' => (int) $bookId]);
        return (int) $stmt->fetchColumn();
    }

    public function hasActiveReservation($userId, $bookId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = :user_id AND book_id = :book_id AND status = '" . self::STATUS_ACTIVE . "'");
        $stmt->execute([
            'user_id' => (int) $userId,
            'book_id' => (int) $bookId
        ]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function cancel($id, $userId) {
        $stmt = $this->pdo->prepare("UPDATE reservations SET status = '" . self::STATUS_EXPIRED . "' WHERE id = :id AND user_id = :user_id AND status = '" . self::STATUS_ACTIVE . "'");
        $stmt->execute([
            'id' => (int) $id,
            'user_id' => (int) $userId
        ]);
        return $stmt->rowCount() > 0;
    }
}
