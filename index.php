<?php
require_once __DIR__ . '/config/connection_paths.php';
$pageTitle = 'Home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Internal Security Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <link rel="stylesheet" href="<?= CSS_URL ?>/theme-smart.css">
</head>
<body>

    <?php require_once __DIR__ . '/includes/header.php'; ?>

    <main id="mainContent" class="container py-4">
        <?php require_once __DIR__ . '/includes/main-body.php'; ?>
    </main>

    <?php require_once __DIR__ . '/includes/footer.php'; ?>

</body>
</html>