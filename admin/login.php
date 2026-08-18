<?php
$pageTitle = 'Admin Login';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="form-section-card" style="max-width:480px; margin:0 auto;">
    <div class="section-label">Coming in Phase 3</div>
    <h2>Administrator Login</h2>
    <p class="text-muted">
        Session-based authentication with password hashing (<code>password_hash()</code> /
        <code>password_verify()</code>) will be wired up here in Phase 3, using the
        <code>users</code> table already defined in the schema.
    </p>
    <a href="../index.php" class="btn btn-govt-outline btn-sm">Back to Home</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
