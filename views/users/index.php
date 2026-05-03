<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Üye Listesi</h2>
    <a href="?action=add_user" class="btn btn-success">Yeni Üye Ekle</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Rol</th>
                <th>Kayıt Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
            <tr>
                <td colspan="5" class="text-center">Henüz üye bulunmamaktadır.</td>
            </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php if ($user['role'] === User::ROLE_ADMIN): ?>
                            <span class="badge bg-danger">Yönetici</span>
                        <?php elseif ($user['role'] === User::ROLE_STAFF): ?>
                            <span class="badge bg-success">Çalışan</span>
                        <?php else: ?>
                            <span class="badge bg-primary">Üye</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                    <td>
                        <?php if ($_SESSION['user_role'] === User::ROLE_ADMIN || $user['role'] === User::ROLE_MEMBER): ?>
                        <a href="?action=edit_user&amp;id=<?= (int) $user['id'] ?>" class="btn btn-sm btn-primary">Düzenle</a>
                        <form method="POST" action="?action=delete_user" class="d-inline" onsubmit="return confirm('Bu üyeyi silmek istediğinize emin misiniz?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                        </form>
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