<?php
require_once __DIR__ . '/config/connection_paths.php';
require_once CONFIG_PATH . '/database.php';

$pageTitle = 'Application Submitted';
$pdo = getDbConnection();

$ref = $_GET['ref'] ?? '';
$isAccessPass = str_starts_with($ref, 'AP-');
$isVehicle = str_starts_with($ref, 'VS-');

if (!$ref || (!$isAccessPass && !$isVehicle)) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

if ($isAccessPass) {
    $stmt = $pdo->prepare(
        'SELECT aa.status, aa.date_submitted, a.first_name, a.last_name
         FROM access_pass_applications aa
         JOIN applicants a ON a.id = aa.applicant_id
         WHERE aa.application_id = ?'
    );
    $applicationType = 'Access Pass';
} else {
    $stmt = $pdo->prepare(
        'SELECT va.status, va.date_submitted, a.first_name, a.last_name
         FROM vehicle_applications va
         JOIN applicants a ON a.id = va.applicant_id
         WHERE va.application_id = ?'
    );
    $applicationType = 'Vehicle Sticker';
}
$stmt->execute([$ref]);
$application = $stmt->fetch();

if (!$application) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

// Pull the list of documents actually saved for this application.
$docStmt = $pdo->prepare('SELECT document_type, file_name FROM documents WHERE application_id = ? ORDER BY id');
$docStmt->execute([$ref]);
$documents = $docStmt->fetchAll();

require_once INCLUDES_PATH . '/header.php';
?>

<div class="form-section-card" style="max-width:640px; margin:0 auto;">
    <div class="section-label" style="color:#276749;">Submission Successful</div>
    <h2>Your application has been submitted successfully.</h2>

    <div class="d-flex align-items-center gap-3 my-3 p-3" style="background:#F7F7F5; border-radius:6px;">
        <div>
            <div class="text-muted small">Application Reference Number</div>
            <div style="font-family:'Source Serif 4',serif; font-size:1.4rem; font-weight:700; color:var(--navy-deep);">
                <?= htmlspecialchars($ref) ?>
            </div>
        </div>
    </div>

    <table class="table table-borderless small mb-4">
        <tr>
            <td class="text-muted" style="width:180px;">Applicant</td>
            <td><?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?></td>
        </tr>
        <tr>
            <td class="text-muted">Application Type</td>
            <td><?= htmlspecialchars($applicationType) ?></td>
        </tr>
        <tr>
            <td class="text-muted">Status</td>
            <td><span class="badge-status badge-pending"><?= htmlspecialchars($application['status']) ?></span></td>
        </tr>
        <tr>
            <td class="text-muted">Date Submitted</td>
            <td><?= htmlspecialchars(date('F j, Y g:i A', strtotime($application['date_submitted']))) ?></td>
        </tr>
    </table>

    <h3 style="font-size:1.05rem;">Documents Received</h3>
    <ul class="small">
        <?php foreach ($documents as $doc): ?>
            <li><?= htmlspecialchars($doc['document_type']) ?> &mdash; <?= htmlspecialchars($doc['file_name']) ?></li>
        <?php endforeach; ?>
    </ul>

    <p class="small text-muted mt-3">
        Keep your reference number to check your application status later.
        Your application will now be reviewed by Internal Security Group personnel.
    </p>

    <div class="d-flex gap-2 mt-3">
        <a href="<?= FEAT3_URL ?>/application-status.php?ref=<?= urlencode($ref) ?>" class="btn btn-govt-primary btn-sm">Check Status</a>
        <a href="<?= BASE_URL ?>/index.php" class="btn btn-govt-outline btn-sm">Back to Home</a>
    </div>
</div>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>