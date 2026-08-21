<?php
require_once __DIR__ . '/config/connection_paths.php';
require_once CONFIG_PATH . '/database.php';
require_once INCLUDES_PATH . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$pdo = getDbConnection();
$formType = $_POST['form_type'] ?? '';

if (!in_array($formType, ['access_pass', 'vehicle_sticker'], true)) {
    http_response_code(400);
    die('Invalid form submission.');
}

$errors = [];

// ============================================================
// ACCESS PASS SUBMISSION
// ============================================================
if ($formType === 'access_pass') {

    $requiredFields = [
        'first_name'        => 'First Name',
        'last_name'         => 'Last Name',
        'date_of_birth'     => 'Date of Birth',
        'contact_number'    => 'Contact Number',
        'email'             => 'Email Address',
        'applicant_type'    => 'Applicant Type',
        'declaration_name'  => 'Printed Name (Declaration)',
        'declaration_date'  => 'Date Signed',
    ];
    $errors = array_merge($errors, validateRequiredFields($_POST, $requiredFields));

    $requiredFiles = [
        'doc_letter_request' => 'Letter Request',
        'doc_valid_id_1'     => 'Valid ID (Copy 1)',
        'doc_valid_id_2'     => 'Valid ID (Copy 2)',
        'applicant_photo'    => 'Applicant Photograph',
    ];
    $errors = array_merge($errors, validateRequiredFiles($_FILES, $requiredFiles));

    // Conditional document requirements based on applicant type.
    $applicantType = $_POST['applicant_type'] ?? '';
    if ($applicantType === 'Non-Plantilla') {
        $errors = array_merge($errors, validateRequiredFiles($_FILES, ['doc_nbi_clearance' => 'NBI Clearance']));
    }
    if ($applicantType === 'Consultant') {
        $errors = array_merge($errors, validateRequiredFiles($_FILES, ['doc_consultancy_contract' => 'Contract of Consultancy']));
    }

    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email Address is not a valid email format';
    }

    if (!empty($errors)) {
        renderErrors($errors, FEAT1_URL . '/access-pass.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        $refNumber = generateReferenceNumber($pdo, 'AP', 'access_pass_applications');

        // Insert applicant
        $stmt = $pdo->prepare(
            'INSERT INTO applicants
             (application_id, first_name, middle_name, last_name, suffix, date_of_birth,
              place_of_birth, sex, civil_status, address, contact_number, email, applicant_type)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $refNumber,
            $_POST['first_name'],
            $_POST['middle_name'] ?? null,
            $_POST['last_name'],
            $_POST['suffix'] ?? null,
            $_POST['date_of_birth'],
            $_POST['place_of_birth'] ?? null,
            $_POST['sex'] ?: null,
            $_POST['civil_status'] ?: null,
            $_POST['address'] ?? null,
            $_POST['contact_number'],
            $_POST['email'],
            $applicantType,
        ]);
        $applicantId = (int) $pdo->lastInsertId();

        // Insert access pass application record
        $stmt = $pdo->prepare(
            'INSERT INTO access_pass_applications
             (application_id, applicant_id, status, date_submitted, declaration_name, declaration_date)
             VALUES (?, ?, ?, NOW(), ?, ?)'
        );
        $stmt->execute([
            $refNumber,
            $applicantId,
            'Submitted',
            $_POST['declaration_name'],
            $_POST['declaration_date'],
        ]);

        // Family background (repeater)
        if (!empty($_POST['family']) && is_array($_POST['family'])) {
            $stmt = $pdo->prepare(
                'INSERT INTO family_background (applicant_id, relationship, name, occupation, other_information)
                 VALUES (?, ?, ?, ?, ?)'
            );
            foreach ($_POST['family'] as $entry) {
                if (empty($entry['name']) && empty($entry['relationship'])) {
                    continue; // skip blank rows
                }
                $stmt->execute([
                    $applicantId,
                    $entry['relationship'] ?: 'Other',
                    $entry['name'] ?? '',
                    $entry['occupation'] ?? null,
                    $entry['other_information'] ?? null,
                ]);
            }
        }

        // Educational background (repeater)
        if (!empty($_POST['education']) && is_array($_POST['education'])) {
            $stmt = $pdo->prepare(
                'INSERT INTO educational_background (applicant_id, school, degree, year_graduated, other_information)
                 VALUES (?, ?, ?, ?, ?)'
            );
            foreach ($_POST['education'] as $entry) {
                if (empty($entry['school'])) {
                    continue; // skip blank rows
                }
                $stmt->execute([
                    $applicantId,
                    $entry['school'],
                    $entry['degree'] ?? null,
                    $entry['year_graduated'] ?? null,
                    $entry['other_information'] ?? null,
                ]);
            }
        }

        // File uploads
        $uploadDir = UPLOADS_PATH . '/' . $refNumber;
        $documentFields = [
            'doc_letter_request'       => ['label' => 'Letter Request', 'exts' => ALLOWED_DOC_EXTENSIONS, 'mimes' => ALLOWED_DOC_MIME_TYPES, 'required' => true],
            'doc_valid_id_1'           => ['label' => 'Valid ID 1', 'exts' => ALLOWED_DOC_EXTENSIONS, 'mimes' => ALLOWED_DOC_MIME_TYPES, 'required' => true],
            'doc_valid_id_2'           => ['label' => 'Valid ID 2', 'exts' => ALLOWED_DOC_EXTENSIONS, 'mimes' => ALLOWED_DOC_MIME_TYPES, 'required' => true],
            'doc_nbi_clearance'        => ['label' => 'NBI Clearance', 'exts' => ALLOWED_DOC_EXTENSIONS, 'mimes' => ALLOWED_DOC_MIME_TYPES, 'required' => false],
            'doc_consultancy_contract' => ['label' => 'Contract of Consultancy', 'exts' => ALLOWED_DOC_EXTENSIONS, 'mimes' => ALLOWED_DOC_MIME_TYPES, 'required' => false],
            'doc_other'                => ['label' => 'Other Supporting Document', 'exts' => ALLOWED_DOC_EXTENSIONS, 'mimes' => ALLOWED_DOC_MIME_TYPES, 'required' => false],
            'applicant_photo'          => ['label' => 'Applicant Photograph', 'exts' => ALLOWED_PHOTO_EXTENSIONS, 'mimes' => ALLOWED_PHOTO_MIME_TYPES, 'required' => true],
        ];

        $photoPath = null;
        foreach ($documentFields as $fieldName => $meta) {
            if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
                continue; // optional and not provided
            }
            $fileName = processFileUpload($_FILES[$fieldName], $uploadDir, $refNumber, $meta['label'], $meta['exts'], $meta['mimes']);
            $relativePath = 'uploads/' . $refNumber . '/' . $fileName;
            logDocument($pdo, $refNumber, $meta['label'], $fileName, $relativePath);

            if ($fieldName === 'applicant_photo') {
                $photoPath = $relativePath;
            }
        }

        if ($photoPath) {
            $stmt = $pdo->prepare('UPDATE access_pass_applications SET photo_path = ? WHERE application_id = ?');
            $stmt->execute([$photoPath, $refNumber]);
        }

        logApplicationAction($pdo, $refNumber, null, 'Application Submitted', 'Access pass application submitted by applicant.');

        $pdo->commit();

        header('Location: ' . BASE_URL . '/confirmation.php?ref=' . urlencode($refNumber));
        exit;

    } catch (Throwable $e) {
        $pdo->rollBack();
        cleanupUploadDir($uploadDir ?? null);
        renderErrors(['Something went wrong while saving your application: ' . $e->getMessage()], FEAT1_URL . '/access-pass.php');
        exit;
    }
}

// ============================================================
// VEHICLE STICKER SUBMISSION
// ============================================================
if ($formType === 'vehicle_sticker') {

    $requiredFields = [
        'first_name'      => 'First Name',
        'last_name'       => 'Last Name',
        'contact_number'  => 'Contact Number',
        'email'           => 'Email Address',
        'plate_number'    => 'Plate Number',
        'make'            => 'Make',
        'model'           => 'Model',
        'ownership'       => 'Vehicle Ownership',
    ];
    $errors = array_merge($errors, validateRequiredFields($_POST, $requiredFields));

    $requiredFiles = [
        'doc_or_cr'   => 'OR/CR',
        'doc_hrep_id' => 'HRep ID',
    ];
    $errors = array_merge($errors, validateRequiredFiles($_FILES, $requiredFiles));

    $ownership = $_POST['ownership'] ?? '';
    if ($ownership === 'Not Registered to Applicant') {
        $errors = array_merge($errors, validateRequiredFiles($_FILES, ['doc_deed_of_sale' => 'Deed of Sale']));
    }

    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email Address is not a valid email format';
    }

    if (!empty($errors)) {
        renderErrors($errors, FEAT2_URL . '/vehicle-sticker.php');
        exit;
    }

    try {
        $pdo->beginTransaction();

        $refNumber = generateReferenceNumber($pdo, 'VS', 'vehicle_applications');

        // Insert applicant (shared table)
        $stmt = $pdo->prepare(
            'INSERT INTO applicants
             (application_id, first_name, middle_name, last_name, contact_number, email, applicant_type)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $refNumber,
            $_POST['first_name'],
            $_POST['middle_name'] ?? null,
            $_POST['last_name'],
            $_POST['contact_number'],
            $_POST['email'],
            'Other', // vehicle sticker form doesn't collect applicant_type; schema requires a value
        ]);
        $applicantId = (int) $pdo->lastInsertId();

        // Insert vehicle application record
        $stmt = $pdo->prepare(
            'INSERT INTO vehicle_applications
             (application_id, applicant_id, plate_number, vehicle_type, make, model, color, year,
              registration_information, ownership, status, date_submitted)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $refNumber,
            $applicantId,
            $_POST['plate_number'],
            $_POST['vehicle_type'] ?: null,
            $_POST['make'],
            $_POST['model'],
            $_POST['color'] ?? null,
            $_POST['year'] ?? null,
            $_POST['registration_information'] ?? null,
            $ownership,
            'Submitted',
        ]);

        // File uploads
        $uploadDir = UPLOADS_PATH . '/' . $refNumber;
        $documentFields = [
            'doc_or_cr'                => ['label' => 'OR/CR', 'required' => true],
            'doc_deed_of_sale'         => ['label' => 'Deed of Sale', 'required' => false],
            'doc_hrep_id'              => ['label' => 'HRep ID', 'required' => true],
            'doc_chattel_mortgage'     => ['label' => 'Chattel Mortgage', 'required' => false],
            'doc_company_certificate'  => ['label' => "Company/Secretary's Certificate", 'required' => false],
        ];

        foreach ($documentFields as $fieldName => $meta) {
            if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $fileName = processFileUpload($_FILES[$fieldName], $uploadDir, $refNumber, $meta['label'], ALLOWED_DOC_EXTENSIONS, ALLOWED_DOC_MIME_TYPES);
            $relativePath = 'uploads/' . $refNumber . '/' . $fileName;
            logDocument($pdo, $refNumber, $meta['label'], $fileName, $relativePath);
        }

        logApplicationAction($pdo, $refNumber, null, 'Application Submitted', 'Vehicle sticker application submitted by applicant.');

        $pdo->commit();

        header('Location: ' . BASE_URL . '/confirmation.php?ref=' . urlencode($refNumber));
        exit;

    } catch (Throwable $e) {
        $pdo->rollBack();
        cleanupUploadDir($uploadDir ?? null);
        renderErrors(['Something went wrong while saving your application: ' . $e->getMessage()], FEAT2_URL . '/vehicle-sticker.php');
        exit;
    }
}

// ============================================================
// Helper: render a simple error page and stop.
// ============================================================
function renderErrors(array $errors, string $backTo): void
{
    $pageTitle = 'Submission Error';
    require_once INCLUDES_PATH . '/header.php';
    ?>
    <div class="form-section-card" style="max-width:640px; margin:0 auto;">
        <div class="section-label" style="color:#A13D2E;">Could Not Submit</div>
        <h2>Please Fix the Following</h2>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="text-muted small">
            Use your browser's Back button to return to the form with your
            entries intact, fix the items above, and submit again.
        </p>
        <a href="<?= htmlspecialchars($backTo) ?>" class="btn btn-govt-outline btn-sm">Return to Form</a>
    </div>
    <?php
    require_once INCLUDES_PATH . '/footer.php';
}

// ============================================================
// Helper: remove a partially-created upload directory if the
// transaction failed after some files were already moved.
// ============================================================
function cleanupUploadDir(?string $dir): void
{
    if ($dir && is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($dir);
    }
}