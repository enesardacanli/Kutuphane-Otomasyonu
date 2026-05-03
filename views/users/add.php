<?php ob_start(); ?>
<h2>Yeni Üye Ekle</h2>
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Ad Soyad</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-posta Adresi</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="member">Üye</option>
                    <?php if ($_SESSION['user_role'] === User::ROLE_ADMIN): ?>
                    <option value="staff">Çalışan</option>
                    <option value="admin">Yönetici (Admin)</option>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Kaydet</button>
            <a href="?action=users" class="btn btn-secondary">İptal</a>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require 'views/layout.php';
?>