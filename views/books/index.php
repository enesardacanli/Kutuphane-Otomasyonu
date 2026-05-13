<?php ob_start(); ?>

<?php
$iconMap = [
    'Bilim'               => 'ph-duotone ph-atom',
    'Bilim Kurgu'         => 'ph-duotone ph-flying-saucer',
    'Biyografi'           => 'ph-duotone ph-pen-nib',
    'Dünya Klasikleri'    => 'ph-duotone ph-globe-hemisphere-west',
    'Felsefe'             => 'ph-duotone ph-lightbulb',
    'Psikoloji'           => 'ph-duotone ph-brain',
    'Tarih'               => 'ph-duotone ph-hourglass-high',
    'Türk Klasikleri'     => 'ph-duotone ph-star-half',
    'Yazılım Mühendisliği'=> 'ph-duotone ph-code'
];
$defaultIcon = 'ph ph-books';
?>

<?php
$currentTab = $_GET['tab'] ?? 'books';
?>

<!-- Tab Navigation -->
<style>
    .books-tab-nav {
        --tab-gap: 10px;
        --active-index: 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--tab-gap);
        padding: 4px;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-card);
        position: relative;
        overflow: hidden;
    }

    .books-tab-nav::before {
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

    .books-tab-nav .nav-item {
        position: relative;
        z-index: 1;
    }

    .books-tab-nav .nav-link {
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
    }

    .books-tab-nav .nav-link.active {
        color: var(--accent) !important;
    }

    .books-tab-nav .nav-link:focus-visible {
        outline: 2px solid var(--accent);
        outline-offset: 2px;
    }
</style>

<ul class="nav nav-pills mb-4 books-tab-nav" aria-label="Kitap görünüm sekmeleri">
    <li class="nav-item">
        <a class="nav-link <?= $currentTab === 'books' && empty($selectedCategory) ? 'active' : '' ?>" href="?action=books&tab=books">
            <i class="ph ph-books fs-5"></i> Tüm Kitaplar
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $currentTab === 'categories' || !empty($selectedCategory) ? 'active' : '' ?>" href="?action=books&tab=categories">
            <i class="ph ph-folders fs-5"></i> Kategoriler
        </a>
    </li>
</ul>

<script>
    (function () {
        const tabNav = document.querySelector('.books-tab-nav');
        if (!tabNav) {
            return;
        }

        const tabLinks = Array.from(tabNav.querySelectorAll('.nav-link'));
        if (tabLinks.length < 2) {
            return;
        }

        function setActiveIndexFromActiveLink() {
            const activeIndex = tabLinks.findIndex(link => link.classList.contains('active'));
            tabNav.style.setProperty('--active-index', String(Math.max(0, activeIndex)));
        }

        setActiveIndexFromActiveLink();

        tabLinks.forEach((link, index) => {
            link.addEventListener('click', (event) => {
                if (event.button !== 0) {
                    return;
                }

                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }

                if (link.classList.contains('active')) {
                    return;
                }

                event.preventDefault();
                tabNav.style.setProperty('--active-index', String(index));

                window.setTimeout(() => {
                    window.location.href = link.href;
                }, 220);
            });
        });
    })();
</script>

<?php if ($currentTab === 'categories' && empty($selectedCategory) && empty($authorFilter) && empty($publisherFilter)): ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="ph ph-books me-2"></i>Kitap Kategorileri</h2>
        <p class="text-muted mb-0" style="color: var(--text-secondary) !important;">Bir kategori seçerek kitapları görüntüleyin</p>
    </div>
    <?php if (in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
    <a href="?action=add_book" class="btn btn-primary d-flex align-items-center px-3" style="border-radius: 8px; font-weight: 500;">
        <i class="ph ph-plus me-2 fs-5"></i> Yeni Kitap Ekle
    </a>
    <?php endif; ?>
</div>

<?php if (empty($categories)): ?>
<div class="text-center py-5">
    <i class="ph ph-mailbox text-muted" style="font-size: 4rem; margin-bottom: 16px; display: inline-block;"></i>
    <h4>Henüz kategori bulunmamaktadır</h4>
    <p class="text-muted" style="color: var(--text-secondary) !important;">Kitap ekleyerek kategorileri oluşturabilirsiniz.</p>
</div>
<?php else: ?>

<style>
    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 30px 24px;
        padding: 1rem 0;
    }
    
    .custom-cat-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        text-decoration: none !important;
        animation: cardEnter 0.5s cubic-bezier(0.4, 0, 0.2, 1) both;
        aspect-ratio: 1;
        position: relative;
        overflow: hidden;
        background-color: var(--bg-card);
        color: var(--text-primary);
    }

    .custom-cat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        border-color: var(--text-secondary);
        color: var(--text-primary);
    }

    .cat-icon-wrap {
        font-size: 3rem;
        margin-bottom: 12px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cat-text-wrap {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .cat-count {
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 4px;
    }

    .cat-title {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.2;
        margin: 0;
        text-align: center;
    }

    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(24px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes filterDropdownAnim {
        0% { opacity: 0; transform: translateX(30px); }
        100% { opacity: 1; transform: translateX(0); }
    }

.filter-dropdown .dropdown-menu {
    border: 1px solid var(--border-color);
    background: var(--bg-card);
    border-radius: 12px;
    box-shadow: 0 10px 30px var(--shadow-color);
    padding: 6px;
    min-width: 180px;
    z-index: 1100;
    /* Animasyon için varsayılan gizlilik */
    display: none;
}

/* Bootstrap .show class eklendiğinde animasyonu tetikle */
.filter-dropdown .dropdown-menu.show {
    display: block;
    animation: filterDropdownAnim 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.filter-dropdown .dropdown-item {
    border-radius: 8px;
    padding: 8px 12px;
    color: var(--text-primary);
    transition: background-color 0.2s ease, color 0.2s ease;
    cursor: pointer !important;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.8rem;
}

.filter-dropdown .dropdown-item:hover {
    background: var(--accent-glow) !important;
    color: var(--accent) !important;
}

.filter-dropdown .search-container {
    padding: 8px 12px;
    position: sticky;
    top: 0;
    background: var(--bg-card);
    z-index: 10;
    margin-bottom: 4px;
}

.filter-dropdown .search-container input,
.filter-dropdown .search-input,
.filter-dropdown .search-container input[type="text"],
.filter-dropdown .search-container input:hover,
.filter-dropdown .search-input:hover,
.filter-dropdown .search-container input:focus,
.filter-dropdown .search-container input:active,
.filter-dropdown .search-container input:focus-visible,
.filter-dropdown .search-input:focus,
.filter-dropdown .search-input:active,
.filter-dropdown .search-input:focus-visible {
    background: transparent !important;
    background-color: transparent !important;
    border: 0 !important;
    border: none !important;
    border-color: transparent !important;
    outline: 0 !important;
    outline: none !important;
    box-shadow: none !important;
    -webkit-box-shadow: none !important;
    -webkit-appearance: none !important;
    appearance: none !important;
}

.filter-dropdown .search-container input,
.filter-dropdown .search-input {
    color: var(--text-primary);
    padding: 8px 4px;
    border-radius: 0;
    width: 100%;
    transition: all 0.3s ease;
}

.filter-dropdown .dropdown-list {
    max-height: 180px;
    overflow-y: auto;
    padding: 4px;
}

.filter-dropdown .dropdown-item.active {
    background: var(--accent-glow) !important;
    color: var(--accent) !important;
}

.filter-dropdown .dropdown-toggle::after {
    border-top-color: var(--text-secondary);
    transition: transform 0.3s ease;
    margin-left: 8px;
}

.filter-dropdown .dropdown-toggle {
    transition: background-color 0.3s ease, color 0.3s ease !important;
}

#filterForm .filter-dropdown .dropdown-toggle:hover {
    background-color: var(--accent-glow) !important;
}

.filter-dropdown .dropdown-toggle.show::after {
    transform: rotate(180deg);
}

#filterForm .btn:hover,
#filterForm .btn:focus,
#filterForm .btn:active,
#filterForm .btn.show {
    transform: none !important;
}
</style>

<div class="category-grid">
    <?php foreach ($categories as $i => $cat):
        $name = $cat['category'] ?: Book::DEFAULT_CATEGORY;
        $iconClass = $iconMap[$name] ?? $defaultIcon;
    ?>
    <a href="?action=books&category=<?= urlencode($name) ?>"
       class="custom-cat-card"
       style="animation-delay: <?= $i * 0.04 ?>s;">
        <div class="cat-icon-wrap">
            <i class="<?= htmlspecialchars($iconClass) ?>"></i>
        </div>
        <div class="cat-text-wrap">
            <h5 class="cat-title"><?= htmlspecialchars($name) ?></h5>
            <span class="cat-count"><?= (int) $cat['book_count'] ?> Kitap</span>
        </div>
    </a>
    <?php endforeach; ?>
</div>

<?php endif; ?>

<?php else: ?>

<style>
#filterForm .btn:hover,
#filterForm .btn:focus,
#filterForm .btn:active,
#filterForm .btn.show {
    transform: none !important;
}

@keyframes filterDropdownDown {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
}

#filterForm .filter-dropdown {
    position: relative;
    --filter-options-max-height: 176px;
}

#filterForm .filter-dropdown .dropdown-menu {
    background-color: var(--bg-card) !important;
    border: 1px solid var(--border-color) !important;
    color: var(--text-primary);
    border-radius: 12px;
    box-shadow: 0 10px 30px var(--shadow-color);
    padding: 6px;
    min-width: 100%;
    width: 100%;
    transform-origin: top center;
    margin-top: -2px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-top: 0 !important;
}

#filterForm .filter-dropdown .dropdown-menu.show {
    animation: filterDropdownDown 0.28s cubic-bezier(0.4, 0, 0.2, 1) both;
}

#filterForm .filter-dropdown .dropdown-toggle.show {
    border-bottom-left-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}

#filterForm .filter-dropdown .dropdown-item {
    color: var(--text-primary) !important;
    padding: 8px 12px;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.8rem;
}

#filterForm .filter-dropdown .dropdown-list {
    max-height: var(--filter-options-max-height);
    overflow-y: auto;
    padding: 4px;
}

#filterForm .filter-dropdown .dropdown-item:hover {
    background: var(--accent-glow) !important;
    color: var(--accent) !important;
}

#filterForm .filter-dropdown .dropdown-item.active,
#filterForm .filter-dropdown .dropdown-item:active {
    background: var(--accent-glow) !important;
    color: var(--accent) !important;
}

#filterForm .filter-dropdown .search-container {
    background-color: var(--bg-card) !important;
}

#filterForm .filter-dropdown .search-input {
    background: transparent !important;
    border: 0 !important;
    outline: 0 !important;
    box-shadow: none !important;
    -webkit-box-shadow: none !important;
    -webkit-appearance: none !important;
    appearance: none !important;
    color: var(--text-primary) !important;
    font-size: 0.8rem;
}

#filterForm .filter-dropdown .search-input:focus,
#filterForm .filter-dropdown .search-input:active,
#filterForm .filter-dropdown .search-input:focus-visible {
    border: 0 !important;
    outline: 0 !important;
    box-shadow: none !important;
}

#filterForm .filter-dropdown .search-input::placeholder {
    color: var(--text-secondary) !important;
    opacity: 1;
}

#filterForm .filter-dropdown .dropdown-toggle {
    transition: background-color 0.2s ease, color 0.2s ease;
}

#filterForm .filter-dropdown .dropdown-toggle:hover {
    background-color: var(--accent-glow) !important;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <?php if (!empty($selectedCategory)): ?>
        <a href="?action=books&tab=categories" class="btn btn-outline-light btn-sm" style="border-color: var(--border-color); color: var(--text-primary); border-radius: 8px;">
            ← Kategoriler
        </a>
        <?php endif; ?>
        <div>
            <h2 class="mb-0"><?= htmlspecialchars($categoryName ?? 'Tüm Kitaplar') ?></h2>
            <p class="page-subtitle"><?= count($books) ?> kitap bulundu</p>
        </div>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <!-- Modern Filter Toolbar -->
        <form method="GET" action="" id="filterForm" class="d-flex align-items-center p-1 rounded-pill border shadow-sm" style="background: var(--bg-card); border-color: var(--border-color) !important;">
            <input type="hidden" name="action" value="books">
            <input type="hidden" name="tab" value="<?= htmlspecialchars($currentTab) ?>">
            <input type="hidden" name="author" id="filterAuthor" value="<?= htmlspecialchars($authorFilter ?? '') ?>">
            <input type="hidden" name="publisher" id="filterPublisher" value="<?= htmlspecialchars($publisherFilter ?? '') ?>">
            <?php if (!empty($selectedCategory)): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">
            <?php endif; ?>
            
            <div class="dropdown filter-dropdown ms-1">
                <button class="btn btn-sm border-0 dropdown-toggle py-2 px-3 d-flex align-items-center justify-content-between rounded-pill fw-medium" type="button" data-bs-toggle="dropdown" style="color: var(--text-primary); min-width: 180px; background: <?= !empty($authorFilter) ? 'var(--accent-glow)' : 'transparent' ?>; color: <?= !empty($authorFilter) ? 'var(--accent)' : 'inherit' ?>;">
                    <span class="text-truncate" style="max-width: 150px;"><?= !empty($authorFilter) ? htmlspecialchars($authorFilter) : 'Tüm Yazarlar' ?></span>
                </button>
                <ul class="dropdown-menu shadow-lg border-0" style="border-radius: 12px;">
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Yazar ara..." onkeyup="filterDropdown(this)">
                    </div>
                    <li><button type="button" class="dropdown-item <?= empty($authorFilter) ? 'active fw-bold' : '' ?>" onclick="submitFilter('author', '')">Tümü</button></li>
                    <div class="dropdown-list">
                        <?php if(!empty($distinctAuthors)): foreach ($distinctAuthors as $a): ?>
                        <li><button type="button" class="dropdown-item <?= $authorFilter === $a ? 'active fw-bold' : '' ?>" onclick="submitFilter('author', '<?= htmlspecialchars(addslashes($a)) ?>')"><?= htmlspecialchars($a) ?></button></li>
                        <?php endforeach; endif; ?>
                    </div>
                </ul>
            </div>
            
            <div class="vr mx-2 opacity-25" style="height: 24px;"></div>
            
            <div class="dropdown filter-dropdown me-1">
                <button class="btn btn-sm border-0 dropdown-toggle py-2 px-3 d-flex align-items-center justify-content-between rounded-pill fw-medium" type="button" data-bs-toggle="dropdown" style="color: var(--text-primary); min-width: 190px; background: <?= !empty($publisherFilter) ? 'var(--accent-glow)' : 'transparent' ?>; color: <?= !empty($publisherFilter) ? 'var(--accent)' : 'inherit' ?>;">
                    <span class="text-truncate" style="max-width: 160px;"><?= !empty($publisherFilter) ? htmlspecialchars($publisherFilter) : 'Tüm Yayınevleri' ?></span>
                </button>
                <ul class="dropdown-menu shadow-lg border-0" style="border-radius: 12px;">
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Yayınevi ara..." onkeyup="filterDropdown(this)">
                    </div>
                    <li><button type="button" class="dropdown-item <?= empty($publisherFilter) ? 'active fw-bold' : '' ?>" onclick="submitFilter('publisher', '')">Tümü</button></li>
                    <div class="dropdown-list">
                        <?php if(!empty($distinctPublishers)): foreach ($distinctPublishers as $p): ?>
                        <li><button type="button" class="dropdown-item <?= $publisherFilter === $p ? 'active fw-bold' : '' ?>" onclick="submitFilter('publisher', '<?= htmlspecialchars(addslashes($p)) ?>')"><?= htmlspecialchars($p) ?></button></li>
                        <?php endforeach; endif; ?>
                    </div>
                </ul>
            </div>
            
            <?php if (!empty($authorFilter) || !empty($publisherFilter)): ?>
            <a href="?action=books&tab=<?= htmlspecialchars($currentTab) ?><?= !empty($selectedCategory) ? '&category='.urlencode($selectedCategory) : '' ?>" class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle me-1" style="width: 28px; height: 28px; background: rgba(220, 53, 69, 0.1); color: #dc3545;" title="Filtreleri Temizle">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </a>
            <?php endif; ?>
        </form>

        <script>
        function submitFilter(name, value) {
            const inputId = name === 'author' ? 'filterAuthor' : 'filterPublisher';
            const input = document.getElementById(inputId);
            if(input) {
                input.value = value;
                document.getElementById('filterForm').submit();
            }
        }

        function filterDropdown(input) {
            const filter = input.value.toLocaleLowerCase('tr-TR');
            const dropdown = input.closest('.dropdown-menu');
            const items = dropdown.querySelectorAll('.dropdown-list .dropdown-item');
            
            items.forEach(item => {
                const text = item.textContent || item.innerText;
                if (text.toLocaleLowerCase('tr-TR').indexOf(filter) > -1) {
                    item.style.display = "";
                    item.parentElement.style.display = "";
                } else {
                    item.style.display = "none";
                    item.parentElement.style.display = "none";
                }
            });
        }
        </script>

        <?php if (in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
        <a href="?action=add_book" class="btn btn-primary d-flex align-items-center px-3" style="border-radius: 8px; font-weight: 500;">
            <i class="ph ph-plus me-2 fs-5"></i> Yeni Kitap Ekle
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($books)): ?>
<div class="text-center py-5 w-100">
    <i class="ph ph-warning-circle text-muted" style="font-size: 3rem; margin-bottom: 12px; display: inline-block;"></i>
    <h4>Bu kategoride henüz kitap bulunmamaktadır</h4>
</div>
<?php else: ?>
<style>
    .book-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 8px;
    }

    .book-card-wrap {
        position: relative;
        padding-top: 18px;
    }

    .book-category-text {
        position: absolute;
        top: 0;
        left: 2px;
        z-index: 2;
        max-width: calc(100% - 6px);
        font-size: 0.72rem;
        line-height: 1.1;
        font-weight: 500;
        color: var(--text-secondary);
        pointer-events: none;
        user-select: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    @keyframes cardEnter {
        from { opacity: 0; transform: translateY(24px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @media (max-width: 576px) {
        .book-gallery { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 4px; }
    
    }
</style>
<div class="book-gallery mt-2 mb-4">
    <?php foreach ($books as $index => $book): ?>
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
    <div class="book-card-wrap" style="animation: cardEnter 0.5s cubic-bezier(0.4, 0, 0.2, 1) both; animation-delay: <?= $index * 0.04 ?>s;">
        <?php if ($currentTab === 'books' && empty($selectedCategory)): ?>
            <div class="book-category-text" title="<?= htmlspecialchars(!empty($book['category']) ? $book['category'] : Book::DEFAULT_CATEGORY) ?>"><?= htmlspecialchars(!empty($book['category']) ? $book['category'] : Book::DEFAULT_CATEGORY) ?></div>
        <?php endif; ?>
        <div class="card shadow-sm border-0 h-100" 
             style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; border-radius: 12px; overflow: hidden;" 
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
             data-publisher="<?= htmlspecialchars($book['publisher'] ?? '', ENT_QUOTES) ?>"
             onclick="openBookModalFromEl(this)"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" 
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.05)';"
        >
            
            <div style="width: 100%; aspect-ratio: 2/3; height: auto; display: flex; align-items: center; justify-content: center; background: #e9ecef; overflow: hidden; position: relative;">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Kapak" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                <?php else: ?>
                    <i class="ph ph-book-open text-muted" style="font-size: 4rem;"></i>
                <?php endif; ?>
            </div>
            
            <div class="card-body d-flex flex-column" style="padding: 0.75rem;">
                <h5 class="card-title mb-1 text-truncate" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></h5>
                <p class="card-text text-muted mb-1 text-truncate" title="<?= htmlspecialchars($book['author']) ?>"><?= htmlspecialchars($book['author']) ?></p>
                
                <div class="mt-auto d-flex justify-content-end align-items-center pt-1">
                    <?php if ((int)$book['stock_count'] > 0): ?>
                        <span class="badge bg-success rounded-pill px-2 py-1"><?= (int)$book['stock_count'] ?> Stok</span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill px-2 py-1">Tükendi</span>
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
.glass-modal-cover {
    width: 100%;
    max-height: 280px;
    object-fit: contain;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
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
    max-width: 100%;
}

[data-theme="light"] .modal-meta-item {
    background: rgba(0, 0, 0, 0.03);
}

.modal-meta-label {
    font-size: 0.72rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    white-space: nowrap;
}

.modal-meta-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: inherit;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>

<div class="glass-modal-overlay" id="bookModalOverlay" onclick="closeBookModal(event)">
    <div class="glass-modal" onclick="event.stopPropagation()">
        <button class="glass-modal-close" onclick="closeBookModal()">&times;</button>
        <div class="row align-items-stretch">
            <div class="col-md-5 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.1); border-radius: 16px; padding: 15px;">
                <img id="modalCover" src="" alt="Kapak" style="display: none; width: 100%; height: auto; max-height: 450px; object-fit: contain; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                <i id="modalCoverPlaceholder" class="ph ph-book-open text-muted" style="font-size: 8rem; display: none;"></i>
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
    const publisher = el.getAttribute('data-publisher');
    
    openBookModal(id, title, author, category, isbn, stock, cover, isReserved, description, totalStock, publisher);
}

function openBookModal(id, title, author, category, isbn, stock, cover, isReserved, description, totalStock, publisher) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalAuthor').textContent = author + (publisher ? ' — ' + publisher : '');
    document.getElementById('modalCategory').textContent = category || '—';
    document.getElementById('modalIsbn').textContent = isbn || '—';
    document.getElementById('modalStock').textContent = totalStock + ' Adet';
    document.getElementById('modalDescription').textContent = description || 'Bu kitap için açıklama girilmemiş.';
    
    const coverImg = document.getElementById('modalCover');
    const coverPlaceholder = document.getElementById('modalCoverPlaceholder');
    const actionsDiv = document.getElementById('modalActions');
    const userRole = "<?= $_SESSION['user_role'] ?>";
    const csrfToken = "<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>";
    const catName = "<?= htmlspecialchars($categoryName ?? '') ?>";
    const returnTo = window.location.search || '?action=books';
    const returnParam = encodeURIComponent(returnTo);
    
    actionsDiv.innerHTML = '';
    
    if (userRole === 'admin' || userRole === 'staff') {
        actionsDiv.innerHTML = `
            <div class="d-flex gap-2 justify-content-center">
                <a href="?action=edit_book&id=${id}&return=${returnParam}" class="btn btn-primary px-4">Düzenle</a>
                <form method="POST" action="?action=delete_book" onsubmit="return confirm('Bu kitabı silmek istediğinize emin misiniz?')">
                    <input type="hidden" name="csrf_token" value="${csrfToken}">
                    <input type="hidden" name="id" value="${id}">
                    <input type="hidden" name="return" value="${returnTo}">
                    <button type="submit" class="btn btn-danger px-4">Sil</button>
                </form>
            </div>
        `;
    } else if (userRole === 'member') {
        let memberActions = '<div class="d-flex gx-2 flex-wrap">';
        if (isReserved) {
            memberActions += `<span class="badge bg-success p-2 px-4 fs-6 me-2 mb-2 align-self-center">✓ Rezerv Edildi</span>`;
        } else if (parseInt(stock) > 0) {
            memberActions += `
                <form method="POST" action="?action=reserve_book" class="me-2 mb-2">
                    <input type="hidden" name="csrf_token" value="${csrfToken}">
                    <input type="hidden" name="book_id" value="${id}">
                    <input type="hidden" name="category" value="${catName}">
                    <button type="submit" class="btn btn-warning px-4 fw-bold">Rezerve Et</button>
                </form>
            `;
        }
        memberActions += `
            <form method="POST" action="?action=add_to_library" class="me-2 mb-2">
                <input type="hidden" name="csrf_token" value="${csrfToken}">
                <input type="hidden" name="book_id" value="${id}">
                <input type="hidden" name="status" value="wishlist">
                <button type="submit" class="btn btn-outline-warning px-3 fw-bold">İstek Listesine Ekle</button>
            </form>
            <form method="POST" action="?action=add_to_library" class="mb-2">
                <input type="hidden" name="csrf_token" value="${csrfToken}">
                <input type="hidden" name="book_id" value="${id}">
                <input type="hidden" name="status" value="read">
                <button type="submit" class="btn btn-outline-success px-3 fw-bold">Okudum Olarak İşaretle</button>
            </form>
        </div>`;
        actionsDiv.innerHTML = memberActions;
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