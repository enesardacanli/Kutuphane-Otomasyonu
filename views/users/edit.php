<?php ob_start(); ?>
<div class="page-header">
    <h2>Üye Düzenle</h2>
</div>
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Ad Soyad</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-posta Adresi</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Şifre (Değiştirmek istemiyorsanız boş bırakın)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="member" <?= (isset($user['role']) && $user['role'] === User::ROLE_MEMBER) ? 'selected' : '' ?>>Üye</option>
                    <?php if ($_SESSION['user_role'] === User::ROLE_ADMIN): ?>
                    <option value="staff" <?= (isset($user['role']) && $user['role'] === User::ROLE_STAFF) ? 'selected' : '' ?>>Çalışan</option>
                    <option value="admin" <?= (isset($user['role']) && $user['role'] === User::ROLE_ADMIN) ? 'selected' : '' ?>>Yönetici (Admin)</option>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="?action=users" class="btn btn-secondary">İptal</a>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require 'views/layout.php';
?>