<?php
require_once __DIR__ . '/../_conn.php';
$pageTitle = 'Application Status';
require_once INCLUDES_PATH . '/header.php';
?>

<div class="form-section-card">
    <div class="section-label">Coming in Phase 4</div>
    <h2>Application Status Checker</h2>
    <p class="text-muted">
        This page will let applicants enter their application reference number
        (e.g. <code>AP-2026-00001</code>) and view its current status. It depends
        on the submission logic being built in Phase 2, so it's stubbed out for now.
    </p>
    <a href="<?= BASE_URL ?>/index.php" class="btn btn-govt-outline btn-sm">Back to Home</a>
</div>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>