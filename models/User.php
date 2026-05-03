<?php
class User {
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_MEMBER = 'member';

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->prepare("SELECT * FROM users ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMembers() {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = '" . self::ROLE_MEMBER . "' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => (int) $id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function create($name, $email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);
    }

    public function update($id, $name, $email, $role, $password = null) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password, role = :role WHERE id = :id");
            return $stmt->execute([
                'id' => (int) $id,
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => $role
            ]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
            return $stmt->execute([
                'id' => (int) $id,
                'name' => $name,
                'email' => $email,
                'role' => $role
            ]);
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => (int) $id]);
    }
}