<?php ob_start(); ?>

<?php
$categoryIcons = [
    'Roman'           => ['📖', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'],
    'Bilim'           => ['🔬', 'linear-gradient(135deg, #00c9ff 0%, #92fe9d 100%)'],
    'Tarih'           => ['🏛️', 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'],
    'Felsefe'         => ['💭', 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'],
    'Şiir'            => ['✒️', 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'],
    'Teknoloji'       => ['💻', 'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)'],
    'Sanat'           => ['🎨', 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)'],
    'Çocuk'           => ['🧸', 'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)'],
    'Edebiyat'        => ['📚', 'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)'],
    'Psikoloji'       => ['🧠', 'linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%)'],
    'Hukuk'           => ['⚖️', 'linear-gradient(135deg, #cfd9df 0%, #e2ebf0 100%)'],
    'Ekonomi'         => ['📊', 'linear-gradient(135deg, #f5af19 0%, #f12711 100%)'],
    'Din'             => ['🕌', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'],
    'Spor'            => ['⚽', 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)'],
    'Müzik'           => ['🎵', 'linear-gradient(135deg, #ee9ca7 0%, #ffdde1 100%)'],
    'Genel'           => ['📕', 'linear-gradient(135deg, #6a11cb 0%, #2575fc 100%)'],
];

$defaultIcon = '📗';
$defaultGradients = [
    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
    'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
    'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
    'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
    'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
    'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
    'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
];
?>

<?php if (empty($selectedCategory) && empty($categoryName)): ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">📚 Kitap Kategorileri</h2>
        <p class="text-muted mb-0" style="color: var(--text-secondary) !important;">Bir kategori seçerek kitapları görüntüleyin</p>
    </div>
    <?php if (in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
    <a href="?action=add_book" class="btn btn-success">
        <span style="margin-right: 6px;">＋</span>Yeni Kitap Ekle
    </a>
    <?php endif; ?>
</div>

<?php if (empty($categories)): ?>
<div class="text-center py-5">
    <div style="font-size: 4rem; margin-bottom: 16px;">📭</div>
    <h4>Henüz kategori bulunmamaktadır</h4>
    <p class="text-muted" style="color: var(--text-secondary) !important;">Kitap ekleyerek kategorileri oluşturabilirsiniz.</p>
</div>
<?php else: ?>

<style>
    .category-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
    }
    @media (max-width: 1200px) {
        .category-grid { grid-template-columns: repeat(4, 1fr); }
    }
    @media (max-width: 992px) {
        .category-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 576px) {
        .category-grid { grid-template-columns: repeat(2, 1fr); }
    }
    .category-card {
        position: relative;
        border-radius: 16px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        text-decoration: none !important;
        color: #fff !important;
        overflow: hidden;
        aspect-ratio: 1 / 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    .category-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.05);
        opacity: 0;
        transition: opacity 0.35s ease;
        border-radius: 16px;
    }
    .category-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
    }
    .category-card:hover::before { opacity: 1; }
    .category-card__icon {
        font-size: 3rem;
        margin-bottom: 12px;
        filter: drop-shadow(0 2px 8px rgba(0,0,0,0.15));
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .category-card:hover .category-card__icon {
        transform: scale(1.15) rotate(-5deg);
    }
    .category-card__name {
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        text-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }
    .category-card__count {
        font-size: 0.82rem;
        opacity: 0.85;
        font-weight: 500;
        background: rgba(255, 255, 255, 0.2);
        padding: 3px 12px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }
    .category-card {
        animation: cardEnter 0.5s cubic-bezier(0.4, 0, 0.2, 1) both;
    }
    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(24px) scale(0.92); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>

<div class="category-grid">
    <?php foreach ($categories as $i => $cat):
        $name = $cat['category'] ?: 'Genel';
        $icon = $categoryIcons[$name][0] ?? $defaultIcon;
        $gradient = $categoryIcons[$name][1] ?? $defaultGradients[$i % count($defaultGradients)];
    ?>
    <a href="?action=books&category=<?= urlencode($name) ?>"
       class="category-card"
       style="background: <?= $gradient ?>; animation-delay: <?= $i * 0.06 ?>s;">
        <div class="category-card__icon"><?= $icon ?></div>
        <div class="category-card__name"><?= htmlspecialchars($name) ?></div>
        <div class="category-card__count"><?= (int) $cat['book_count'] ?> kitap</div>
    </a>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<?php else: ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="?action=books" class="btn btn-outline-light" style="border-color: var(--border-color); color: var(--text-primary);">
            ← Geri
        </a>
        <div>
            <h2 class="mb-0"><?= htmlspecialchars($categoryName) ?></h2>
            <small style="color: var(--text-secondary);"><?= count($books) ?> kitap bulundu</small>
        </div>
    </div>
    <?php if (in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
    <a href="?action=add_book" class="btn btn-success">
        <span style="margin-right: 6px;">＋</span>Yeni Kitap Ekle
    </a>
    <?php endif; ?>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ISBN</th>
                <th>Başlık</th>
                <th>Yazar</th>
                <th>Toplam Stok</th>
                <th>Mevcut Stok</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($books)): ?>
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div style="font-size: 2.5rem; margin-bottom: 8px;">📭</div>
                    Bu kategoride henüz kitap bulunmamaktadır.
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= htmlspecialchars($book['isbn']) ?></td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= (int) $book['stock_count'] ?></td>
                    <td>
                        <?php $avail = (int) ($book['available_stock'] ?? 0); ?>
                        <?php if ($avail > 0): ?>
                            <span class="badge bg-success"><?= $avail ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger">0</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
                        <a href="?action=edit_book&amp;id=<?= (int) $book['id'] ?>" class="btn btn-sm btn-primary">Düzenle</a>
                        <form method="POST" action="?action=delete_book" class="d-inline" onsubmit="return confirm('Bu kitabı silmek istediğinize emin misiniz?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                        </form>
                        <?php endif; ?>
                        <?php if ($_SESSION['user_role'] === User::ROLE_MEMBER): ?>
                            <?php if (!empty($reservedBookIds) && in_array((int) $book['id'], $reservedBookIds, true)): ?>
                                <span class="badge bg-success">✓ Rezerv Edildi</span>
                            <?php elseif ($avail > 0): ?>
                            <form method="POST" action="?action=reserve_book" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="book_id" value="<?= (int) $book['id'] ?>">
                                <input type="hidden" name="category" value="<?= htmlspecialchars($categoryName) ?>">
                                <button type="submit" class="btn btn-sm btn-warning">Rezerve Et</button>
                            </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
require 'views/layout.php';
?>