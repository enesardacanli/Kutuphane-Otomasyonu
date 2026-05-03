<?php ob_start(); ?>
<div class="page-header">
    <h2>Yeni Ödünç İşlemi</h2>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="?action=add_loan" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <div class="mb-3">
        <label for="user_id" class="form-label">Üye</label>
        <select class="form-select" id="user_id" name="user_id" required>
            <option value="">Seçiniz...</option>
            <?php foreach ($users as $user): ?>
            <option value="<?= (int) $user['id'] ?>"><?= htmlspecialchars($user['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="book_id" class="form-label">Kitap</label>
        <select class="form-select" id="book_id" name="book_id" required>
            <option value="">Seçiniz...</option>
            <?php foreach ($books as $book): ?>
            <?php $avail = (int) ($book['available_stock'] ?? 0); ?>
            <?php if ($avail > 0): ?>
            <option value="<?= (int) $book['id'] ?>"><?= htmlspecialchars($book['title']) ?> (Mevcut: <?= $avail ?>)</option>
            <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="loan_date" class="form-label">Veriliş Tarihi</label>
        <input type="date" class="form-control" id="loan_date" name="loan_date" required value="<?= date('Y-m-d') ?>">
    </div>
    <div class="mb-3">
        <label for="expected_return_date" class="form-label">Beklenen İade Tarihi</label>
        <input type="date" class="form-control" id="expected_return_date" name="expected_return_date" required value="<?= date('Y-m-d', strtotime('+14 days')) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Kaydet</button>
    <a href="?action=loans" class="btn btn-secondary">İptal</a>
</form>
<?php
$content = ob_get_clean();
require 'views/layout.php';
?>