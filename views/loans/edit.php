<?php ob_start(); ?>
<h2>Kitap İade İşlemi</h2>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Ödünç Bilgileri</h5>
        <p class="card-text"><strong>Üye:</strong> <?= htmlspecialchars($loan['user_name']) ?></p>
        <p class="card-text"><strong>Kitap:</strong> <?= htmlspecialchars($loan['book_title']) ?></p>
        <p class="card-text"><strong>Veriliş Tarihi:</strong> <?= htmlspecialchars($loan['loan_date']) ?></p>
        <p class="card-text"><strong>Beklenen İade:</strong> <?= htmlspecialchars($loan['expected_return_date']) ?></p>
    </div>
</div>

<form action="?action=edit_loan&amp;id=<?= (int) $loan['id'] ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <div class="mb-3">
        <label for="actual_return_date" class="form-label">Gerçekleşen İade Tarihi</label>
        <input type="date" class="form-control" id="actual_return_date" name="actual_return_date" required value="<?= date('Y-m-d') ?>">
    </div>
    <button type="submit" class="btn btn-success">İadeyi Onayla</button>
    <a href="?action=loans" class="btn btn-secondary">İptal</a>
</form>
<?php
$content = ob_get_clean();
require 'views/layout.php';
?>