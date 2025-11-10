<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Home', ENT_QUOTES, 'UTF-8') ?></title>
    <?php if (!empty($vite['styles'] ?? [])): ?>
        <?php foreach ($vite['styles'] as $style): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($style, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (!empty($stylesheets ?? null)): ?>
        <?= $stylesheets ?>
    <?php endif; ?>
</head>
<body>
<?= $body ?? '' ?>
<?php if (!empty($vite['client'] ?? null)): ?>
    <script type="module" src="<?= htmlspecialchars($vite['client'], ENT_QUOTES, 'UTF-8') ?>"></script>
<?php endif; ?>
<?php if (!empty($vite['script'] ?? null)): ?>
    <script type="module" src="<?= htmlspecialchars($vite['script'], ENT_QUOTES, 'UTF-8') ?>"></script>
<?php endif; ?>
<?php if (!empty($javascripts ?? null)): ?>
    <?= $javascripts ?>
<?php endif; ?>
</body>
</html>
