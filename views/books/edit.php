<?php ob_start(); ?>
<h2>Kitap Düzenle</h2>
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Başlık</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($book['title'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Yazar</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= htmlspecialchars($book['author'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="publisher" class="form-label">Yayınevi</label>
                <input type="text" class="form-control" id="publisher" name="publisher" value="<?= htmlspecialchars($book['publisher'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="category" name="category" list="category-list" value="<?= htmlspecialchars($book['category'] ?? 'Genel') ?>" required>
                <datalist id="category-list">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category']) ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </datalist>
                <small class="form-text" style="color: var(--text-secondary);">Mevcut bir kategori seçebilir veya yeni bir kategori yazabilirsiniz.</small>
            </div>
            <div class="mb-3">
                <label for="stock_count" class="form-label">Stok Adedi</label>
                <input type="number" class="form-control" id="stock_count" name="stock_count" value="<?= (int) ($book['stock_count'] ?? 0) ?>" min="0" required>
            </div>
            <div class="mb-3">
                <label for="cover_image" class="form-label">Kapak Görseli (Değiştirmek için yükleyin, Maks 2MB)</label>
                <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/jpeg, image/png, image/webp">
                <?php if (!empty($book['cover_image'])): ?>
                    <div class="mt-2">
                        <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Kapak" style="max-height: 100px; border-radius: 4px;">
                        <small class="text-muted d-block">Mevcut Görsel</small>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Kitap Açıklaması</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Kitap hakkında kısa bilgi girin..."><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Güncelle</button>
            <a href="?action=books" class="btn btn-secondary">İptal</a>
        </form>
    </div>
</div>
<?php
$content = ob_get_clean();
require 'views/layout.php';
?>