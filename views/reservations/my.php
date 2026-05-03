<?php ob_start(); ?>
<h2>Rezervasyonlarım</h2>

<?php if (empty($reservations)): ?>
<div class="text-center py-5">
    <div style="font-size: 3rem; margin-bottom: 12px;">📋</div>
    <p style="color: var(--text-secondary);">Henüz rezervasyonunuz bulunmamaktadır.</p>
    <a href="?action=books" class="btn btn-primary">Kitaplara Göz At</a>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>Kitap</th>
                <th>Yazar</th>
                <th>Rezervasyon Tarihi</th>
                <th>Son Geçerlilik</th>
                <th>Durum</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $res): ?>
            <?php
                $now = new DateTime();
                $expire = new DateTime($res['expire_date']);
            ?>
            <tr>
                <td><?= htmlspecialchars($res['book_title']) ?></td>
                <td><?= htmlspecialchars($res['book_author']) ?></td>
                <td><?= htmlspecialchars($res['reservation_date']) ?></td>
                <td><?= htmlspecialchars($res['expire_date']) ?></td>
                <td>
                    <?php if ($res['status'] === Reservation::STATUS_ACTIVE): ?>
                        <?php if ($now > $expire): ?>
                            <span class="badge bg-danger">Süresi Doldu</span>
                        <?php else: ?>
                            <?php
                                $diff = $now->diff($expire);
                                $totalHours = ($diff->days * 24) + $diff->h;
                                $remaining = $totalHours . ' saat ' . $diff->i . ' dk';
                            ?>
                            <span class="badge bg-info">Aktif - <?= $remaining ?> kaldı</span>
                        <?php endif; ?>
                    <?php elseif ($res['status'] === Reservation::STATUS_COMPLETED): ?>
                        <span class="badge bg-success">Tamamlandı</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Süresi Doldu</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($res['status'] === Reservation::STATUS_ACTIVE && $now < $expire): ?>
                    <form method="POST" action="?action=cancel_reservation" class="d-inline" onsubmit="return confirm('Rezervasyonu iptal etmek istediğinize emin misiniz?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <input type="hidden" name="reservation_id" value="<?= (int) $res['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">İptal Et</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require 'views/layout.php';
?>
