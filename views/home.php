<?php ob_start(); ?>
<div class="text-center mt-5">
    <h1 class="display-4">Kütüphane Otomasyonuna Hoş Geldiniz</h1>
    <p class="lead">Kitapları, üyeleri ve ödünç alma işlemlerini kolayca yönetin.</p>
    <a href="?action=books" class="btn btn-primary mt-3">Kitapları Görüntüle</a>
</div>
<?php 
$content = ob_get_clean();
require 'views/layout.php';
?>