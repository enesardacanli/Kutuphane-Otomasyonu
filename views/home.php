<?php ob_start(); ?>
<div class="page-header">
    <div>
        <h2>Hoş Geldiniz, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Misafir') ?>.</h2>
        <p class="page-subtitle">Kütüphane yönetim sistemine hoş geldiniz. İşlem yapmak istediğiniz modülü seçin.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Kitaplar -->
    <div class="col-md-6 col-lg-3">
        <a href="?action=books" class="text-decoration-none">
            <div class="card h-100 custom-card">
                <div class="card-body p-4 text-center">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3" style="color: var(--text-primary);"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <h6 class="card-title mb-1 fw-bold" style="color: var(--text-primary);">Katalog</h6>
                    <small class="text-muted">Tüm kitaplar</small>
                </div>
            </div>
        </a>
    </div>

    <?php if (in_array($_SESSION['user_role'] ?? '', [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
    <!-- Ödünç İşlemleri -->
    <div class="col-md-6 col-lg-3">
        <a href="?action=loans" class="text-decoration-none">
            <div class="card h-100 custom-card">
                <div class="card-body p-4 text-center">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3" style="color: var(--text-primary);"><path d="M17 3v4"></path><path d="M7 3v4"></path><path d="M17 11v4"></path><path d="M7 11v4"></path><path d="M17 19v4"></path><path d="M7 19v4"></path><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                    <h6 class="card-title mb-1 fw-bold" style="color: var(--text-primary);">Ödünç Alma</h6>
                    <small class="text-muted">Teslim ve iade</small>
                </div>
            </div>
        </a>
    </div>

    <!-- Üyeler -->
    <div class="col-md-6 col-lg-3">
        <a href="?action=users" class="text-decoration-none">
            <div class="card h-100 custom-card">
                <div class="card-body p-4 text-center">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3" style="color: var(--text-primary);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <h6 class="card-title mb-1 fw-bold" style="color: var(--text-primary);">Üyeler</h6>
                    <small class="text-muted">Kullanıcılar</small>
                </div>
            </div>
        </a>
    </div>

    <!-- Rezervasyonlar -->
    <div class="col-md-6 col-lg-3">
        <a href="?action=reservations" class="text-decoration-none">
            <div class="card h-100 custom-card">
                <div class="card-body p-4 text-center">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3" style="color: var(--text-primary);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <h6 class="card-title mb-1 fw-bold" style="color: var(--text-primary);">Rezervasyon</h6>
                    <small class="text-muted">Aktif talepler</small>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
    
    <?php if (($_SESSION['user_role'] ?? '') === User::ROLE_MEMBER): ?>
    <!-- Rezervasyonlarım -->
    <div class="col-md-6 col-lg-3">
        <a href="?action=my_reservations" class="text-decoration-none">
            <div class="card h-100 custom-card">
                <div class="card-body p-4 text-center">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-3" style="color: var(--text-primary);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <h6 class="card-title mb-1 fw-bold" style="color: var(--text-primary);">Rezervasyonlarım</h6>
                    <small class="text-muted">Taleplerim</small>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
</div>

<style>
.custom-card {
    transition: all 0.2s ease;
    border: 1px solid var(--border-color);
    background-color: transparent;
    border-radius: 8px;
    box-shadow: none;
}
.custom-card:hover {
    transform: translateY(-2px);
    background-color: var(--bg-card);
    border-color: var(--text-secondary);
}
</style>
<?php 
$content = ob_get_clean();
require 'views/layout.php';
?>