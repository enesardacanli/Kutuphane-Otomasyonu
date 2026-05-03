<?php
require_once 'models/Reservation.php';

class Book {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM books ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->execute(['id' => (int) $id]);
        return $stmt->fetch();
    }

    public function getCategories() {
        $stmt = $this->pdo->prepare("SELECT category, COUNT(*) as book_count FROM books GROUP BY category ORDER BY category ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBooksByCategory($category) {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE category = :category ORDER BY title ASC");
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll();
    }

    public function getAvailableStock($bookId) {
        $stmt = $this->pdo->prepare("
            SELECT b.stock_count
                - COALESCE((SELECT COUNT(*) FROM loans WHERE book_id = b.id AND actual_return_date IS NULL), 0)
                - COALESCE((SELECT COUNT(*) FROM reservations WHERE book_id = b.id AND status = '" . Reservation::STATUS_ACTIVE . "'), 0)
            AS available
            FROM books b WHERE b.id = :id
        ");
        $stmt->execute(['id' => (int) $bookId]);
        return (int) $stmt->fetchColumn();
    }

    public function getAllWithAvailableStock() {
        $stmt = $this->pdo->prepare("
            SELECT b.*,
                b.stock_count
                - COALESCE((SELECT COUNT(*) FROM loans WHERE book_id = b.id AND actual_return_date IS NULL), 0)
                - COALESCE((SELECT COUNT(*) FROM reservations WHERE book_id = b.id AND status = '" . Reservation::STATUS_ACTIVE . "'), 0)
            AS available_stock
            FROM books b
            ORDER BY b.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBooksByCategoryWithStock($category) {
        $stmt = $this->pdo->prepare("
            SELECT b.*,
                b.stock_count
                - COALESCE((SELECT COUNT(*) FROM loans WHERE book_id = b.id AND actual_return_date IS NULL), 0)
                - COALESCE((SELECT COUNT(*) FROM reservations WHERE book_id = b.id AND status = '" . Reservation::STATUS_ACTIVE . "'), 0)
            AS available_stock
            FROM books b
            WHERE b.category = :category
            ORDER BY b.title ASC
        ");
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll();
    }
    public function getDistinctAuthors() {
        $stmt = $this->pdo->prepare("SELECT DISTINCT author FROM books WHERE author IS NOT NULL AND author != '' ORDER BY author ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getDistinctPublishers() {
        $stmt = $this->pdo->prepare("SELECT DISTINCT publisher FROM books WHERE publisher IS NOT NULL AND publisher != '' ORDER BY publisher ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllFiltered($category = null, $author = null, $publisher = null) {
        $sql = "
            SELECT b.*,
                b.stock_count
                - COALESCE((SELECT COUNT(*) FROM loans WHERE book_id = b.id AND actual_return_date IS NULL), 0)
                - COALESCE((SELECT COUNT(*) FROM reservations WHERE book_id = b.id AND status = '" . Reservation::STATUS_ACTIVE . "'), 0)
            AS available_stock
            FROM books b
            WHERE 1=1
        ";
        $params = [];

        if (!empty($category)) {
            $sql .= " AND b.category = :category";
            $params['category'] = $category;
        }
        if (!empty($author)) {
            $sql .= " AND b.author = :author";
            $params['author'] = $author;
        }
        if (!empty($publisher)) {
            $sql .= " AND b.publisher = :publisher";
            $params['publisher'] = $publisher;
        }

        $sql .= " ORDER BY b.title ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($isbn, $title, $author, $category, $stock_count, $cover_image = null, $description = null, $publisher = null) {
        $stmt = $this->pdo->prepare("INSERT INTO books (isbn, title, author, category, cover_image, description, stock_count, publisher) VALUES (:isbn, :title, :author, :category, :cover_image, :description, :stock_count, :publisher)");
        return $stmt->execute([
            'isbn' => $isbn,
            'title' => $title,
            'author' => $author,
            'category' => $category,
            'cover_image' => $cover_image,
            'description' => $description,
            'stock_count' => (int) $stock_count,
            'publisher' => $publisher
        ]);
    }

    public function update($id, $isbn, $title, $author, $category, $stock_count, $cover_image = null, $description = null, $publisher = null) {
        if ($cover_image !== null) {
            $stmt = $this->pdo->prepare("UPDATE books SET isbn = :isbn, title = :title, author = :author, category = :category, cover_image = :cover_image, description = :description, stock_count = :stock_count, publisher = :publisher WHERE id = :id");
            return $stmt->execute([
                'id' => (int) $id,
                'isbn' => $isbn,
                'title' => $title,
                'author' => $author,
                'category' => $category,
                'cover_image' => $cover_image,
                'description' => $description,
                'stock_count' => (int) $stock_count,
                'publisher' => $publisher
            ]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE books SET isbn = :isbn, title = :title, author = :author, category = :category, description = :description, stock_count = :stock_count, publisher = :publisher WHERE id = :id");
            return $stmt->execute([
                'id' => (int) $id,
                'isbn' => $isbn,
                'title' => $title,
                'author' => $author,
                'category' => $category,
                'description' => $description,
                'stock_count' => (int) $stock_count,
                'publisher' => $publisher
            ]);
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = :id");
        return $stmt->execute(['id' => (int) $id]);
    }

    public function decrementStock($id) {
        $stmt = $this->pdo->prepare("UPDATE books SET stock_count = stock_count - 1 WHERE id = :id AND stock_count > 0");
        $stmt->execute(['id' => (int) $id]);
        return $stmt->rowCount() > 0;
    }

    public function incrementStock($id) {
        $stmt = $this->pdo->prepare("UPDATE books SET stock_count = stock_count + 1 WHERE id = :id");
        $stmt->execute(['id' => (int) $id]);
        return $stmt->rowCount() > 0;
    }
}