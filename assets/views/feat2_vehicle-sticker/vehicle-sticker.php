<?php
require_once __DIR__ . '/../_conn.php';
$pageTitle = 'Vehicle Sticker Application';
$pageScript = JS_URL . '/vehicle-sticker.js';
require_once INCLUDES_PATH . '/header.php';
?>

<div class="mb-4">
    <div class="eyebrow text-uppercase small fw-bold" style="color:#A6802A; letter-spacing:.1em;">
        Vehicle Sticker Application
    </div>
    <h1 class="mb-1">Vehicle Sticker Application</h1>
    <p class="text-muted">
        Fields marked <span class="text-danger">*</span> are required.
        This form does not submit yet &mdash; document upload and database
        storage are added in Phase 2. The "For Clearance/Processing Use" section
        is administrative-only and is not shown here.
    </p>
</div>

<form id="vehicleForm" action="<?= BASE_URL ?>/submit.php" method="POST" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="form_type" value="vehicle_sticker">

    <!-- PERSONAL INFORMATION -->
    <div class="form-section-card">
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
            <div class="col-md-4">
                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">HRep ID Number</label>
                <input type="text" name="hrep_id_number" class="form-control" placeholder="[FIELD TO BE CONFIRMED]">
            </div>
            <div class="col-md-4">
                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                <input type="text" name="contact_number" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>
    </div>

    <!-- VEHICLE INFORMATION -->
    <div class="form-section-card">
        <div class="section-label">Section B</div>
        <h2>Vehicle Information</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Plate Number <span class="text-danger">*</span></label>
                <input type="text" name="plate_number" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Vehicle Type</label>
                <select name="vehicle_type" class="form-select">
                    <option value="">Select&hellip;</option>
                    <option>Sedan</option>
                    <option>SUV</option>
                    <option>Van</option>
                    <option>Motorcycle</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Color</label>
                <input type="text" name="color" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Make <span class="text-danger">*</span></label>
                <input type="text" name="make" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Model <span class="text-danger">*</span></label>
                <input type="text" name="model" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Year</label>
                <input type="text" name="year" class="form-control" placeholder="YYYY">
            </div>

            <div class="col-md-6">
                <label class="form-label">Registration Information</label>
                <input type="text" name="registration_information" class="form-control" placeholder="[FIELD TO BE CONFIRMED]">
            </div>
            <div class="col-md-6">
                <label class="form-label">Vehicle Ownership <span class="text-danger">*</span></label>
                <select name="ownership" id="ownershipSelect" class="form-select" required>
                    <option value="">Select&hellip;</option>
                    <option value="Registered to Applicant">Registered to Applicant</option>
                    <option value="Not Registered to Applicant">Not Registered to Applicant</option>
                </select>
            </div>
        </div>
    </div>

    <!-- SUPPORTING DOCUMENTS -->
    <div class="form-section-card">
        <div class="section-label">Section C</div>
        <h2>Supporting Documents</h2>

        <div class="mb-3">
            <label class="form-label">OR/CR (Official Receipt / Certificate of Registration) <span class="text-danger">*</span></label>
            <input type="file" name="doc_or_cr" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <div class="doc-conditional" id="docDeedOfSale">
            <div class="mb-3">
                <label class="form-label">Deed of Sale <span class="text-muted">(required if vehicle is not registered to applicant)</span></label>
                <input type="file" name="doc_deed_of_sale" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">HRep ID <span class="text-danger">*</span></label>
            <input type="file" name="doc_hrep_id" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <div class="mb-3">
            <label class="form-label">Chattel Mortgage <span class="text-muted">(if applicable)</span></label>
            <input type="file" name="doc_chattel_mortgage" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <div class="mb-0">
            <label class="form-label">Company / Secretary&rsquo;s Certificate <span class="text-muted">(if applicable)</span></label>
            <input type="file" name="doc_company_certificate" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="<?= BASE_URL ?>/index.php" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-govt-primary px-4">Continue to Review</button>
    </div>
</form>

<?php require_once INCLUDES_PATH . '/footer.php'; ?>