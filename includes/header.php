<?php
require_once __DIR__ . '/../config/app.php';
if (!isset($pageTitle)) { $pageTitle = 'SMART Portal'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Internal Security Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-govt">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">
            House of Representatives &mdash; Internal Security Group
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/access-pass.php">Access Pass</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/vehicle-sticker.php">Vehicle Sticker</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/application-status.php">Check Status</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/login.php">Admin</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
