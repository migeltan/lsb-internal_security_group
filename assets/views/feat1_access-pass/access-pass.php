<?php
require_once __DIR__ . '/../_conn.php';
$pageTitle = 'Access Pass Application';
$pageScript = JS_URL . '/access-pass.js';
require_once INCLUDES_PATH . '/header.php';
?>

<div class="form-section-card section-blue corner-accent-blue mb-4">
    <div class="section-label">Access Pass Application</div>
    <h1>Access Pass / ID Application</h1>
    <p class="text-muted mb-0">
        Fields marked <span class="text-danger">*</span> are required.
        This form does not submit yet &mdash; document upload and database
        storage are added in Phase 2.
    </p>
</div>

<form id="accessPassForm" action="<?= BASE_URL ?>/submit.php" method="POST" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="form_type" value="access_pass">

    <!-- SECTION A: PERSONAL INFORMATION -->
    <div class="form-section-card section-blue corner-accent-blue">
        <div class="section-label">Section A</div>
        <h2>Personal Information</h2>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="col-md-1">
                <label class="form-label">Suffix</label>
                <input type="text" name="suffix" class="form-control" placeholder="Jr.">
            </div>

            <div class="col-md-4">
                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" name="date_of_birth" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Place of Birth</label>
                <input type="text" name="place_of_birth" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sex</label>
                <select name="sex" class="form-select">
                    <option value="">Select&hellip;</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Prefer not to say</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Civil Status</label>
                <select name="civil_status" class="form-select">
                    <option value="">Select&hellip;</option>
                    <option>Single</option>
                    <option>Married</option>
                    <option>Widowed</option>
                    <option>Separated</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Home Address</label>
                <input type="text" name="address" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                <input type="text" name="contact_number" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Applicant Type <span class="text-danger">*</span></label>
                <select name="applicant_type" id="applicantType" class="form-select" required>
                    <option value="">Select&hellip;</option>
                    <option value="Plantilla">Plantilla</option>
                    <option value="Non-Plantilla">Non-Plantilla</option>
                    <option value="Consultant">Consultant</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
    </div>

    <!-- SECTION B: FAMILY BACKGROUND -->
    <div class="form-section-card section-yellow corner-accent-yellow">
        <div class="section-label">Section B</div>
        <h2>Family Background</h2>
        <div id="familyEntries"></div>
        <button type="button" id="addFamilyMember" class="btn btn-govt-outline btn-sm mt-2">
            + Add Family Member
        </button>
    </div>

    <!-- SECTION C: EDUCATIONAL BACKGROUND -->
    <div class="form-section-card section-red corner-accent-red">
        <div class="section-label">Section C</div>
        <h2>Educational Background</h2>
        <div id="educationEntries"></div>
        <button type="button" id="addEducation" class="btn btn-govt-outline btn-sm mt-2">
            + Add Educational Record
        </button>
    </div>

    <!-- SECTION D: SUPPORTING DOCUMENTS -->
    <div class="form-section-card section-blue corner-accent-blue">
        <div class="section-label">Section D</div>
        <h2>Supporting Documents</h2>
        <p class="text-muted small">Required documents depend on the applicant type selected above.</p>

        <div class="mb-3">
            <label class="form-label">Letter Request addressed to the Sergeant-at-Arms <span class="text-danger">*</span></label>
            <input type="file" name="doc_letter_request" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>
        <div class="mb-3">
            <label class="form-label">Valid ID (Copy 1) <span class="text-danger">*</span></label>
            <input type="file" name="doc_valid_id_1" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>
        <div class="mb-3">
            <label class="form-label">Valid ID (Copy 2) <span class="text-danger">*</span></label>
            <input type="file" name="doc_valid_id_2" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <div class="doc-conditional" id="docNbi">
            <div class="mb-3">
                <label class="form-label">NBI Clearance <span class="text-muted">(required for Non-Plantilla)</span></label>
                <input type="file" name="doc_nbi_clearance" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>

        <div class="doc-conditional" id="docConsultancy">
            <div class="mb-3">
                <label class="form-label">Contract of Consultancy <span class="text-muted">(required for Consultants)</span></label>
                <input type="file" name="doc_consultancy_contract" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>

        <div class="mb-0">
            <label class="form-label">Other Supporting Document <span class="text-muted">(optional)</span></label>
            <input type="file" name="doc_other" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>
    </div>

    <!-- SECTION E: PHOTO -->
    <div class="form-section-card section-yellow corner-accent-yellow">
        <div class="section-label">Section E</div>
        <h2>Applicant Photograph</h2>
        <label class="form-label">Upload Photo <span class="text-danger">*</span></label>
        <input type="file" name="applicant_photo" class="form-control" accept=".jpg,.jpeg,.png" required>
        <div class="form-text">Accepted formats: JPG, JPEG, PNG. This photo will be used on the generated access pass.</div>
    </div>

    <!-- SECTION F: DECLARATION -->
    <div class="form-section-card section-declaration corner-accent-red">
        <div class="section-label">Section F</div>
        <h2>Declaration</h2>
        <p class="small">
            I certify that the information provided in this application is true and correct
            to the best of my knowledge. I understand that any false statement may be grounds
            for denial or revocation of my access pass.
        </p>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Printed Name <span class="text-danger">*</span></label>
                <input type="text" name="declaration_name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Signature</label>
                <input type="text" name="declaration_signature" class="form-control" placeholder="[SIGNATURE CAPTURE METHOD TO BE CONFIRMED]">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date Signed <span class="text-danger">*</span></label>
                <input type="date" name="declaration_date" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="form-actions-govt d-flex justify-content-between">
        <a href="<?= BASE_URL ?>/index.php" class="btn btn-govt-outline">Cancel</a>
        <button type="submit" class="btn btn-govt-primary px-4">Continue to Review</button>
    </div>
</form>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>