<?php ob_start(); ?>
<h2>Aktif Rezervasyonlar</h2>

<?php if (empty($reservations)): ?>
<div class="text-center py-5">
    <div style="font-size: 3rem; margin-bottom: 12px;">📋</div>
    <p style="color: var(--text-secondary);">Aktif rezervasyon bulunmamaktadır.</p>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Üye</th>
                <th>Kitap</th>
                <th>Rezervasyon Tarihi</th>
                <th>Son Geçerlilik</th>
                <th>Kalan Süre</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $res): ?>
            <?php
                $now = new DateTime();
                $expire = new DateTime($res['expire_date']);
                $diff = $now->diff($expire);
                if ($now > $expire) {
                    $remaining = 'Süresi Doldu';
                } else {
                    $totalHours = ($diff->days * 24) + $diff->h;
                    $remaining = $totalHours . ' saat ' . $diff->i . ' dk';
                }
            ?>
            <tr>
                <td><?= (int) $res['id'] ?></td>
                <td><?= htmlspecialchars($res['user_name']) ?></td>
                <td><?= htmlspecialchars($res['book_title']) ?></td>
                <td><?= htmlspecialchars($res['reservation_date']) ?></td>
                <td><?= htmlspecialchars($res['expire_date']) ?></td>
                <td>
                    <?php if ($now > $expire): ?>
                        <span class="badge bg-danger">Süresi Doldu</span>
                    <?php else: ?>
                        <span class="badge bg-info"><?= $remaining ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" action="?action=complete_reservation" class="d-inline" onsubmit="return confirm('Rezervasyonu tamamlayıp ödünç kaydı açılsın mı?')">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                        <input type="hidden" name="reservation_id" value="<?= (int) $res['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Teslim Et</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (!empty($allReservations)): ?>
<hr style="border-color: var(--border-color);">
<h3 class="mt-4">Tüm Rezervasyonlar</h3>
<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Üye</th>
                <th>Kitap</th>
                <th>Tarih</th>
                <th>Son Geçerlilik</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($allReservations as $res): ?>
            <tr>
                <td><?= (int) $res['id'] ?></td>
                <td><?= htmlspecialchars($res['user_name']) ?></td>
                <td><?= htmlspecialchars($res['book_title']) ?></td>
                <td><?= htmlspecialchars($res['reservation_date']) ?></td>
                <td><?= htmlspecialchars($res['expire_date']) ?></td>
                <td>
                    <?php if ($res['status'] === Reservation::STATUS_ACTIVE): ?>
                        <span class="badge bg-info">Aktif</span>
                    <?php elseif ($res['status'] === Reservation::STATUS_COMPLETED): ?>
                        <span class="badge bg-success">Tamamlandı</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Süresi Doldu</span>
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
