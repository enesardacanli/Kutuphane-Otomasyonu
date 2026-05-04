<?php

class UserBook {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserBooks($userId) {
        $stmt = $this->pdo->prepare("
            SELECT ub.id as user_book_id, ub.status, b.* 
            FROM user_books ub 
            JOIN books b ON ub.book_id = b.id 
            WHERE ub.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addBookToLibrary($userId, $bookId, $status = 'wishlist') {
        $stmt = $this->pdo->prepare("
            INSERT INTO user_books (user_id, book_id, status) 
            VALUES (:user_id, :book_id, :status) 
            ON DUPLICATE KEY UPDATE status = VALUES(status)
        ");
        return $stmt->execute([
            'user_id' => $userId,
            'book_id' => $bookId,
            'status' => $status
        ]);
    }

    public function updateStatus($userId, $bookId, $status) {
        $stmt = $this->pdo->prepare("
            UPDATE user_books 
            SET status = :status 
            WHERE user_id = :user_id AND book_id = :book_id
        ");
        return $stmt->execute([
            'status' => $status,
            'user_id' => $userId,
            'book_id' => $bookId
        ]);
    }

    public function removeBookFromLibrary($userId, $bookId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM user_books
            WHERE user_id = :user_id AND book_id = :book_id
        ");

        return $stmt->execute([
            'user_id' => (int) $userId,
            'book_id' => (int) $bookId
        ]);
    }
}
