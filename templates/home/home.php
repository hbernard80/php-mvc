<?php
ob_start();
?>
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            <h1 class="display-4 mb-3"><?= htmlspecialchars($greeting, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="lead text-muted" data-js-hook="vite-message">Ce projet utilise désormais le framework CSS Bootstrap pour un style moderne et responsive.</p>
        </div>
    </div>
</main>
<?php
$body = ob_get_clean();

require dirname(__DIR__) . '/base.php';
