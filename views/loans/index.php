<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Ödünç İşlemleri</h2>
    <a href="?action=add_loan" class="btn btn-primary">Yeni Ödünç Ver</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Üye Adı</th>
                <th>Kitap Adı</th>
                <th>Veriliş Tarihi</th>
                <th>Beklenen İade</th>
                <th>Gerçekleşen İade</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($loans)): ?>
            <tr>
                <td colspan="7" class="text-center">Henüz ödünç işlemi bulunmamaktadır.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?= (int) $loan['id'] ?></td>
                    <td><?= htmlspecialchars($loan['user_name']) ?></td>
                    <td><?= htmlspecialchars($loan['book_title']) ?></td>
                    <td><?= htmlspecialchars($loan['loan_date']) ?></td>
                    <td><?= htmlspecialchars($loan['expected_return_date']) ?></td>
                    <td><?= $loan['actual_return_date'] ? htmlspecialchars($loan['actual_return_date']) : '<span class="badge bg-warning text-dark">İade Edilmedi</span>' ?></td>
                    <td>
                        <?php if (!$loan['actual_return_date']): ?>
                        <a href="?action=edit_loan&amp;id=<?= (int) $loan['id'] ?>" class="btn btn-sm btn-success">İade Al</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
require 'views/layout.php';
?>