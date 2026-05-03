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

<?php if (empty($books)): ?>
<div class="text-center py-5 w-100">
    <div style="font-size: 3rem; margin-bottom: 12px;">📭</div>
    <h4>Bu kategoride henüz kitap bulunmamaktadır</h4>
</div>
<?php else: ?>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mt-2 mb-4">
    <?php foreach ($books as $book): ?>
    <?php 
        $bookId = (int) $book['id'];
        $coverPath = !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : '';
        $bookTitle = htmlspecialchars(addslashes($book['title']));
        $bookAuthor = htmlspecialchars(addslashes($book['author']));
        $bookCategory = htmlspecialchars(addslashes($book['category']));
        $bookIsbn = htmlspecialchars(addslashes($book['isbn']));
        $bookDesc = htmlspecialchars($book['description'] ?? '', ENT_QUOTES);
        $avail = (int) ($book['available_stock'] ?? 0);
        $isReserved = (!empty($reservedBookIds) && in_array($bookId, $reservedBookIds, true)) ? 'true' : 'false';
    ?>
    <div class="col">
        <div class="card h-100 shadow-sm border-0" 
             style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; border-radius: 12px;" 
             data-id="<?= $bookId ?>"
             data-title="<?= htmlspecialchars($book['title'], ENT_QUOTES) ?>"
             data-author="<?= htmlspecialchars($book['author'], ENT_QUOTES) ?>"
             data-category="<?= htmlspecialchars($book['category'], ENT_QUOTES) ?>"
             data-isbn="<?= htmlspecialchars($book['isbn'], ENT_QUOTES) ?>"
             data-stock="<?= $avail ?>"
             data-cover="<?= $coverPath ?>"
             data-reserved="<?= $isReserved ?>"
             data-total-stock="<?= (int)$book['stock_count'] ?>"
             data-description="<?= htmlspecialchars($book['description'] ?? '', ENT_QUOTES) ?>"
             onclick="openBookModalFromEl(this)"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';"
        >
            
            <div style="height: 320px; display: flex; align-items: center; justify-content: center; background: #e9ecef; border-top-left-radius: 12px; border-top-right-radius: 12px; overflow: hidden; position: relative;">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Kapak" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                <?php else: ?>
                    <div style="font-size: 5rem;">📗</div>
                <?php endif; ?>
            </div>
            
            <div class="card-body d-flex flex-column" style="padding: 0.65rem 0.8rem;">
                <h5 class="card-title mb-1 text-truncate" title="<?= htmlspecialchars($book['title']) ?>" style="font-size: 1rem; font-weight: 600;"><?= htmlspecialchars($book['title']) ?></h5>
                <p class="card-text text-muted mb-1 text-truncate" title="<?= htmlspecialchars($book['author']) ?>" style="font-size: 0.85rem;"><?= htmlspecialchars($book['author']) ?></p>
                
                <div class="mt-auto d-flex justify-content-end align-items-center pt-1">
                    <?php if ((int)$book['stock_count'] > 0): ?>
                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 0.75rem;"><?= (int)$book['stock_count'] ?> Stok</span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 0.75rem;">Tükendi</span>
                    <?php endif; ?>
                </div>
            </div>
            
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php endif; ?>

<!-- Glassmorphism Modal -->
<style>
.glass-modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.75);
    z-index: 1050;
    display: none;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.glass-modal-overlay.show {
    display: flex;
    opacity: 1;
}
.glass-modal {
    background: rgba(30, 33, 48, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 10px 40px 0 rgba(0, 0, 0, 0.4);
    border-radius: 20px;
    padding: 30px;
    max-width: 800px;
    width: 95%;
    color: #fff;
    opacity: 0;
    transform: translateY(15px);
    transition: transform 0.25s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.25s ease-out;
    will-change: transform, opacity;
    position: relative;
}
.glass-modal-overlay.show .glass-modal {
    opacity: 1;
    transform: translateY(0);
}
[data-theme="light"] .glass-modal {
    background: rgba(255, 255, 255, 0.75);
    color: #333;
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
}
.glass-modal-close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 1.5rem;
    cursor: pointer;
    color: inherit;
    opacity: 0.7;
    border: none;
    background: transparent;
}
.glass-modal-close:hover {
    opacity: 1;
}
.glass-modal-cover {
    width: 100%;
    max-height: 280px;
    object-fit: contain;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
</style>

<div class="glass-modal-overlay" id="bookModalOverlay" onclick="closeBookModal(event)">
    <div class="glass-modal" onclick="event.stopPropagation()">
        <button class="glass-modal-close" onclick="closeBookModal()">&times;</button>
        <div class="row align-items-stretch">
            <div class="col-md-5 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.1); border-radius: 16px; padding: 15px;">
                <img id="modalCover" src="" alt="Kapak" style="display: none; width: 100%; height: auto; max-height: 450px; object-fit: contain; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                <div id="modalCoverPlaceholder" style="font-size: 8rem; display: none;">📗</div>
            </div>
            <div class="col-md-7 ps-md-4 mt-4 mt-md-0 d-flex flex-column">
                <h2 id="modalTitle" class="mb-1" style="font-weight: 700;"></h2>
                <p id="modalAuthor" class="mb-3 fs-5 text-warning" style="opacity: 0.9;"></p>
                
                <div class="description-section mb-3 flex-grow-1" style="max-height: 180px; overflow-y: auto; padding-right: 10px;">
                    <h6 class="text-uppercase small fw-bold opacity-50 mb-1" style="letter-spacing: 1px;">Kitap Özeti</h6>
                    <p id="modalDescription" style="line-height: 1.6; opacity: 0.85; font-size: 0.95rem; white-space: pre-wrap;"></p>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="d-block opacity-50 text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Kategori</small>
                            <strong id="modalCategory" class="d-block text-truncate" style="font-size: 0.9rem;"></strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="d-block opacity-50 text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">ISBN</small>
                            <strong id="modalIsbn" class="d-block text-truncate" style="font-size: 0.9rem;"></strong>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-3 text-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <small class="d-block opacity-50 text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Stok</small>
                            <strong id="modalStock" class="d-block text-truncate" style="font-size: 0.9rem;"></strong>
                        </div>
                    </div>
                </div>

                <div id="modalActions" class="mt-auto d-flex gap-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
function openBookModalFromEl(el) {
    const id = el.getAttribute('data-id');
    const title = el.getAttribute('data-title');
    const author = el.getAttribute('data-author');
    const category = el.getAttribute('data-category');
    const isbn = el.getAttribute('data-isbn');
    const stock = el.getAttribute('data-stock');
    const cover = el.getAttribute('data-cover');
    const isReserved = el.getAttribute('data-reserved') === 'true';
    const description = el.getAttribute('data-description');
    const totalStock = el.getAttribute('data-total-stock');
    
    openBookModal(id, title, author, category, isbn, stock, cover, isReserved, description, totalStock);
}

function openBookModal(id, title, author, category, isbn, stock, cover, isReserved, description, totalStock) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalAuthor').textContent = author;
    document.getElementById('modalCategory').textContent = category;
    document.getElementById('modalIsbn').textContent = isbn;
    document.getElementById('modalStock').textContent = totalStock + ' Adet';
    document.getElementById('modalDescription').textContent = description || 'Bu kitap için açıklama girilmemiş.';
    
    const coverImg = document.getElementById('modalCover');
    const coverPlaceholder = document.getElementById('modalCoverPlaceholder');
    const actionsDiv = document.getElementById('modalActions');
    const userRole = "<?= $_SESSION['user_role'] ?>";
    const csrfToken = "<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>";
    const catName = "<?= htmlspecialchars($categoryName ?? '') ?>";
    
    actionsDiv.innerHTML = '';
    
    if (userRole === 'admin' || userRole === 'staff') {
        actionsDiv.innerHTML = `
            <div class="d-flex gap-2 justify-content-center">
                <a href="?action=edit_book&id=${id}" class="btn btn-primary px-4">Düzenle</a>
                <form method="POST" action="?action=delete_book" onsubmit="return confirm('Bu kitabı silmek istediğinize emin misiniz?')">
                    <input type="hidden" name="csrf_token" value="${csrfToken}">
                    <input type="hidden" name="id" value="${id}">
                    <button type="submit" class="btn btn-danger px-4">Sil</button>
                </form>
            </div>
        `;
    } else if (userRole === 'member') {
        if (isReserved) {
            actionsDiv.innerHTML = `<span class="badge bg-success p-2 px-4 fs-6">✓ Rezerv Edildi</span>`;
        } else if (parseInt(stock) > 0) {
            actionsDiv.innerHTML = `
                <form method="POST" action="?action=reserve_book">
                    <input type="hidden" name="csrf_token" value="${csrfToken}">
                    <input type="hidden" name="book_id" value="${id}">
                    <input type="hidden" name="category" value="${catName}">
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Rezerve Et</button>
                </form>
            `;
        }
    }
    
    if (cover) {
        coverImg.src = cover;
        coverImg.style.display = 'block';
        coverPlaceholder.style.display = 'none';
    } else {
        coverImg.style.display = 'none';
        coverPlaceholder.style.display = 'block';
    }
    
    const overlay = document.getElementById('bookModalOverlay');
    overlay.style.display = 'flex';
    // Trigger reflow for transition
    void overlay.offsetWidth;
    overlay.classList.add('show');
}

function closeBookModal(e) {
    const overlay = document.getElementById('bookModalOverlay');
    overlay.classList.remove('show');
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 300);
}
</script>

<?php
$content = ob_get_clean();
require 'views/layout.php';
?>