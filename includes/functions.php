<?php
/**
 * Shared functions for Phase 2: submission handling.
 */

define('MAX_FILE_SIZE_BYTES', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_DOC_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf']);
define('ALLOWED_PHOTO_EXTENSIONS', ['jpg', 'jpeg', 'png']);
define('ALLOWED_DOC_MIME_TYPES', ['image/jpeg', 'image/png', 'application/pdf']);
define('ALLOWED_PHOTO_MIME_TYPES', ['image/jpeg', 'image/png']);

/**
 * Generate the next reference number for a given prefix/table,
 * e.g. AP-2026-00001. Scoped per calendar year.
 */
function generateReferenceNumber(PDO $pdo, string $prefix, string $table): string
{
    $year = date('Y');
    $likePattern = $prefix . '-' . $year . '-%';

    $stmt = $pdo->prepare("SELECT application_id FROM {$table} WHERE application_id LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$likePattern]);
    $last = $stmt->fetchColumn();

    $nextSeq = 1;
    if ($last) {
        $nextSeq = (int) substr($last, -5) + 1;
    }

    return sprintf('%s-%s-%05d', $prefix, $year, $nextSeq);
}

/**
 * Validate that required text fields are present and non-empty.
 * $fields is [ 'form_field_name' => 'Human readable label' ].
 * Returns an array of missing labels (empty array = all present).
 */
function validateRequiredFields(array $data, array $fields): array
{
    $missing = [];
    foreach ($fields as $key => $label) {
        if (!isset($data[$key]) || trim((string) $data[$key]) === '') {
            $missing[] = $label;
        }
    }
    return $missing;
}

/**
 * Validate that required file uploads are present.
 * $files is [ 'form_field_name' => 'Human readable label' ].
 */
function validateRequiredFiles(array $filesArray, array $fields): array
{
    $missing = [];
    foreach ($fields as $key => $label) {
        if (!isset($filesArray[$key]) || $filesArray[$key]['error'] === UPLOAD_ERR_NO_FILE) {
            $missing[] = $label;
        }
    }
    return $missing;
}

/**
 * Handle a single file upload: validates extension, MIME type, and size,
 * moves it into the destination directory with a safe unique filename,
 * and returns the new filename on success.
 *
 * Throws RuntimeException with a human-readable message on failure.
 */
function processFileUpload(
    array $file,
    string $destDir,
    string $applicationId,
    string $documentType,
    array $allowedExtensions,
    array $allowedMimeTypes
): string {
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        throw new RuntimeException("No file was uploaded for {$documentType}.");
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException("Upload error for {$documentType} (code {$file['error']}).");
    }
    if ($file['size'] > MAX_FILE_SIZE_BYTES) {
        $maxMb = MAX_FILE_SIZE_BYTES / (1024 * 1024);
        throw new RuntimeException("{$documentType} exceeds the {$maxMb}MB size limit.");
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions, true)) {
        throw new RuntimeException("{$documentType} must be one of: " . implode(', ', $allowedExtensions));
    }

    // Verify actual file content matches an allowed MIME type (not just the extension).
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowedMimeTypes, true)) {
        throw new RuntimeException("{$documentType} does not appear to be a valid file of an allowed type.");
    }

    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $safeDocType = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', $documentType)));
    $uniqueName = $applicationId . '_' . $safeDocType . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destPath = $destDir . DIRECTORY_SEPARATOR . $uniqueName;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException("Failed to save {$documentType}. Please try again.");
    }

    return $uniqueName;
}

/**
 * Record an uploaded document in the documents table.
 */
function logDocument(PDO $pdo, string $applicationId, string $documentType, string $fileName, string $filePath): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO documents (application_id, document_type, file_name, file_path)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$applicationId, $documentType, $fileName, $filePath]);
}

/**
 * Add an entry to the audit log.
 */
function logApplicationAction(PDO $pdo, string $applicationId, ?int $userId, string $action, string $remarks = ''): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO application_logs (application_id, user_id, action, remarks)
         VALUES (?, ?, ?, ?)'
    );
    $stmt->execute([$applicationId, $userId, $action, $remarks]);
}
