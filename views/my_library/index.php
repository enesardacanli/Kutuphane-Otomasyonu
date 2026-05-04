<div class="page-header">
    <div>
        <h2>Kütüphanem</h2>
        <p class="page-subtitle">Okuduğunuz veya okuyacağınız kitapları takip edin</p>
    </div>
</div>

<!-- Tab Navigation -->
<style>
    .library-tab-nav {
        --tab-gap: 10px;
        --active-index: 0;
        display: inline-grid;
        grid-template-columns: repeat(2, minmax(160px, 1fr));
        gap: var(--tab-gap);
        padding: 4px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-card);
        position: relative;
        overflow: hidden;
    }

    .library-tab-nav::before {
        content: '';
        position: absolute;
        top: 4px;
        bottom: 4px;
        left: 4px;
        width: calc((100% - var(--tab-gap) - 8px) / 2);
        border-radius: 10px;
        background: var(--accent-glow);
        transform: translateX(calc(var(--active-index) * (100% + var(--tab-gap))));
        transition: transform 240ms cubic-bezier(0.2, 0, 0, 1);
        z-index: 0;
    }

    .library-tab-nav .nav-item {
        position: relative;
        z-index: 1;
    }

    .library-tab-nav .nav-link {
        width: 100%;
        border-radius: 10px;
        font-weight: 500;
        background: transparent !important;
        border: 0 !important;
        color: var(--text-secondary) !important;
        transition: color 160ms ease;
        text-align: center;
        margin-right: 0 !important;
        justify-content: center;
        gap: 6px;
        display: flex;
        align-items: center;
        padding: 0.5rem 1.25rem;
    }

    .library-tab-nav .nav-link.active {
        color: var(--accent) !important;
    }

    .library-tab-nav .nav-link:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }
</style>

<ul class="nav nav-pills mb-4 library-tab-nav" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="read-tab" data-bs-toggle="tab" data-bs-target="#read" type="button" role="tab" aria-controls="read" aria-selected="true" onclick="document.querySelector('.library-tab-nav').style.setProperty('--active-index', '0')">
            📚 Okunanlar <span class="badge bg-secondary ms-2"><?= count($readBooks) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist" type="button" role="tab" aria-controls="wishlist" aria-selected="false" onclick="document.querySelector('.library-tab-nav').style.setProperty('--active-index', '1')">
            ⭐ İstek Listesi <span class="badge bg-secondary ms-2"><?= count($wishlistBooks) ?></span>
        </button>
    </li>
</ul>

<style>
    .book-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 8px;
    }

    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(24px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @media (max-width: 576px) {
        .book-gallery { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 4px; }
    }
</style>

<?php 
function renderBookCard($book, $index, $statusToSwitch, $btnText, $btnClass) { 
    $bookId = (int)$book['id'];
    $coverPath = !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : '';
    $avail = (int)($book['available_stock'] ?? 0); // Varsayılan varsa
    // Rezervasyon kontrolü için my_library sayfasında basitçe false veriyoruz, detayları modelden vs çekmemiz gerekirdi aslında
    // Stok bilgisini çekiyoruz
    $totalStock = (int)($book['stock_count'] ?? 0);
?>
    <div style="animation: cardEnter 0.5s cubic-bezier(0.4, 0, 0.2, 1) both; animation-delay: <?= $index * 0.04 ?>s;">
        <div class="card shadow-sm border-0 h-100" 
             style="cursor:pointer; transition: transform 0.2s, box-shadow 0.2s; border-radius: 12px; overflow: hidden;"
             data-id="<?= $bookId ?>"
             data-title="<?= htmlspecialchars($book['title'], ENT_QUOTES) ?>"
             data-author="<?= htmlspecialchars($book['author'], ENT_QUOTES) ?>"
             data-category="<?= htmlspecialchars($book['category'] ?? '', ENT_QUOTES) ?>"
             data-isbn="<?= htmlspecialchars($book['isbn'] ?? '', ENT_QUOTES) ?>"
             data-stock="<?= $avail ?>"
             data-cover="<?= $coverPath ?>"
             data-reserved="false"
             data-total-stock="<?= $totalStock ?>"
             data-description="<?= htmlspecialchars($book['description'] ?? '', ENT_QUOTES) ?>"
             data-publisher="<?= htmlspecialchars($book['publisher'] ?? '', ENT_QUOTES) ?>"
             onclick="openBookModalFromEl(this)"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';">
            
            <div style="width: 100%; aspect-ratio: 2/3; height: auto; display: flex; align-items: center; justify-content: center; background: #e9ecef; overflow: hidden; position: relative;">
                <?php if(!empty($book['cover_image'])): ?>
                    <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Kapak" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                <?php else: ?>
                    <div style="font-size: 4rem;">📗</div>
                <?php endif; ?>
            </div>
            
            <div class="card-body d-flex flex-column" style="padding: 0.75rem;">
                <h5 class="card-title mb-1 text-truncate" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></h5>
                <p class="card-text text-muted mb-1 text-truncate" title="<?= htmlspecialchars($book['author']) ?>"><?= htmlspecialchars($book['author']) ?></p>
                <div class="mt-auto d-flex justify-content-end align-items-center pt-1">
                    <?php if ($totalStock > 0): ?>
                        <span class="badge bg-success rounded-pill px-2 py-1"><?= $totalStock ?> Stok</span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill px-2 py-1">Tükendi</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="read" role="tabpanel" aria-labelledby="read-tab">
        <div class="book-gallery mt-2 mb-4">
            <?php foreach($readBooks as $index => $book): ?>
                <?php renderBookCard($book, $index, 'wishlist', 'İstek Listesine Taşı', 'outline-warning'); ?>
            <?php endforeach; ?>
            <?php if(empty($readBooks)): ?>
                <div class="col-12"><p class="text-muted">Henüz okuduğunuz bir kitap yok.</p></div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
         <div class="book-gallery mt-2 mb-4">
            <?php foreach($wishlistBooks as $index => $book): ?>
                <?php renderBookCard($book, $index, 'read', 'Okudum Olarak İşaretle', 'outline-success'); ?>
            <?php endforeach; ?>
            <?php if(empty($wishlistBooks)): ?>
                <div class="col-12"><p class="text-muted">İstek listeniz boş.</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal CSS and Markup (Copied from books/index.php) -->
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
    padding: 32px;
    max-width: 980px;
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
.description-section {
    background: var(--accent-glow);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 14px 14px;
}
.modal-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.modal-meta-item {
    display: inline-flex;
    align-items: baseline;
    gap: 8px;
    padding: 8px 10px;
    border-radius: 999px;
    border: 1px solid var(--border-color);
    background: rgba(255, 255, 255, 0.04);
}
[data-theme="light"] .modal-meta-item {
    background: rgba(0, 0, 0, 0.03);
}
.modal-meta-label {
    font-size: 0.72rem;
    color: var(--text-secondary);
    text-transform: uppercase;
}
.modal-meta-value {
    font-size: 0.95rem;
    font-weight: 600;
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
                <h2 id="modalTitle" class="mb-1"></h2>
                <p id="modalAuthor" class="mb-3 fs-5 text-warning" style="opacity: 0.9;"></p>
                
                <div class="description-section mb-3 flex-grow-1" style="max-height: 260px; overflow-y: auto; padding-right: 10px;">
                    <h6 class="text-uppercase small fw-bold opacity-50 mb-2" style="letter-spacing: 1px;">Kitap Özeti</h6>
                    <p id="modalDescription" style="line-height: 1.7; opacity: 0.9; font-size: 1rem; margin-bottom: 0; white-space: pre-wrap;"></p>
                </div>

                <div class="modal-meta mb-4">
                    <div class="modal-meta-item" title="Kategori">
                        <span class="modal-meta-label">Kategori</span>
                        <span id="modalCategory" class="modal-meta-value"></span>
                    </div>
                    <div class="modal-meta-item" title="ISBN">
                        <span class="modal-meta-label">ISBN</span>
                        <span id="modalIsbn" class="modal-meta-value"></span>
                    </div>
                    <div class="modal-meta-item" title="Stok">
                        <span class="modal-meta-label">Stok</span>
                        <span id="modalStock" class="modal-meta-value"></span>
                    </div>
                </div>

                <div id="modalActions" class="mt-auto d-flex gap-2 flex-wrap"></div>
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
    const cover = el.getAttribute('data-cover');
    const description = el.getAttribute('data-description');
    const totalStock = el.getAttribute('data-total-stock');
    const publisher = el.getAttribute('data-publisher');
    const isReserved = el.getAttribute('data-reserved') === 'true';
    
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalAuthor').textContent = author + (publisher ? ' — ' + publisher : '');
    document.getElementById('modalCategory').textContent = category || '—';
    document.getElementById('modalIsbn').textContent = isbn || '—';
    document.getElementById('modalStock').textContent = totalStock + ' Adet';
    document.getElementById('modalDescription').textContent = description || 'Açıklama yok.';
    
    const coverImg = document.getElementById('modalCover');
    const coverPlaceholder = document.getElementById('modalCoverPlaceholder');
    if (cover) {
        coverImg.src = cover;
        coverImg.style.display = 'block';
        coverPlaceholder.style.display = 'none';
    } else {
        coverImg.style.display = 'none';
        coverPlaceholder.style.display = 'block';
    }
    
    const actionsDiv = document.getElementById('modalActions');
    const csrfToken = "<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>";
    
    let html = '';
    
    if (isReserved) {
        html += `<span class="badge bg-success p-2 px-4 fs-6 me-2 mb-2 align-self-center">✓ Rezerv Edildi</span>`;
    } else if (parseInt(totalStock) > 0) {
        html += `
            <form method="POST" action="?action=reserve_book" class="me-2 mb-2">
                <input type="hidden" name="csrf_token" value="${csrfToken}">
                <input type="hidden" name="book_id" value="${id}">
                <button type="submit" class="btn btn-warning px-4 fw-bold">Rezerve Et</button>
            </form>
        `;
    }
    
    // Görüntülenen kitabın şu anki durumu (Kütüphanem sayfasında hangisiyse diğerine geçiş imkanı)
    // Okunanlar da isek wishlist butonunu, wishlist kısmındaysak okundu işaretle butonunu gösterelim.
    let switchStatus = document.getElementById('wishlist').contains(el) ? 'read' : 'wishlist';
    let btnText = switchStatus === 'read' ? 'Okudum İşaretle' : 'İstek Listesine Taşı';
    let btnClass = switchStatus === 'read' ? 'btn-outline-success' : 'btn-outline-warning';

    html += `
        <form method="POST" action="?action=update_library_status" class="mb-2">
            <input type="hidden" name="csrf_token" value="${csrfToken}">
            <input type="hidden" name="book_id" value="${id}">
            <input type="hidden" name="status" value="${switchStatus}">
            <button type="submit" class="btn ${btnClass} px-3 fw-bold">${btnText}</button>
        </form>
    `;

    html += `
        <form method="POST" action="?action=remove_from_library" class="mb-2" onsubmit="return confirm('Bu kitabı Kütüphanem’den kaldırmak istediğinize emin misiniz?')">
            <input type="hidden" name="csrf_token" value="${csrfToken}">
            <input type="hidden" name="book_id" value="${id}">
            <button type="submit" class="btn btn-outline-danger px-3 fw-bold">Kaldır</button>
        </form>
    `;
    
    actionsDiv.innerHTML = html;
    
    const overlay = document.getElementById('bookModalOverlay');
    overlay.style.display = 'flex';
    void overlay.offsetWidth;
    overlay.classList.add('show');
}

function closeBookModal() {
    const overlay = document.getElementById('bookModalOverlay');
    overlay.classList.remove('show');
    setTimeout(() => { overlay.style.display = 'none'; }, 300);
}
</script>
