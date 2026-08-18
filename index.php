<?php
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';
?>

<div class="hero-govt">
    <div class="eyebrow">Internal Security Group &middot; Prototype Portal</div>
    <h1>Digital Access Pass and Vehicle Sticker Application Portal</h1>
    <p class="lead">
        Apply for an access pass or vehicle sticker online, upload your supporting
        documents, and track your application status &mdash; without needing to
        queue in person for every step.
    </p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="app-choice-card">
            <div class="card-index">01 &nbsp;APPLICATION TYPE</div>
            <h3>Access Pass Application</h3>
            <p>For personnel &mdash; plantilla, non-plantilla, or consultant &mdash;
               applying for an internal access pass / ID.</p>
            <a href="access-pass.php" class="btn btn-govt-primary">Start Access Pass Application</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="app-choice-card">
            <div class="card-index">02 &nbsp;APPLICATION TYPE</div>
            <h3>Vehicle Sticker Application</h3>
            <p>For personnel applying for a vehicle sticker to bring a private
               vehicle onto the premises.</p>
            <a href="vehicle-sticker.php" class="btn btn-govt-primary">Start Vehicle Sticker Application</a>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="form-section-card h-100">
            <div class="section-label">Already Applied?</div>
            <h2 style="font-size:1.1rem;">Check Application Status</h2>
            <p class="text-muted small">
                Enter your application reference number to see where your
                application stands.
            </p>
            <a href="application-status.php" class="btn btn-govt-outline btn-sm">Check Status</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-section-card h-100">
            <div class="section-label">Before You Begin</div>
            <h2 style="font-size:1.1rem;">Instructions</h2>
            <ul class="small text-muted mb-0 ps-3">
                <li>Prepare clear scans or photos of your documents (JPG, PNG, or PDF).</li>
                <li>Have a recent 2x2 photo ready for the access pass application.</li>
                <li>Applications are reviewed by Internal Security Group personnel &mdash;
                    submitting does not mean automatic approval.</li>
                <li>[OFFICIAL PROCESSING TIME TO BE CONFIRMED]</li>
            </ul>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-section-card h-100">
            <div class="section-label">Required Documents</div>
            <h2 style="font-size:1.1rem;">Typical Requirements</h2>
            <ul class="small text-muted mb-0 ps-3">
                <li>Letter request addressed to the Sergeant-at-Arms</li>
                <li>Two (2) copies of a valid ID</li>
                <li>NBI Clearance (non-plantilla applicants)</li>
                <li>Contract of Consultancy (consultant applicants)</li>
                <li>OR/CR, and Deed of Sale if applicable (vehicle sticker)</li>
            </ul>
            <p class="small text-muted mt-2 mb-0">
                [OFFICIAL DOCUMENT REQUIREMENT LIST TO BE CONFIRMED]
            </p>
        </div>
    </div>
</div>

<div class="form-section-card mt-4">
    <div class="section-label">Need Help?</div>
    <h2 style="font-size:1.1rem;">Contact / Inquiries</h2>
    <p class="small text-muted mb-0">
        [OFFICIAL CONTACT INFORMATION TO BE PROVIDED BY THE INTERNAL SECURITY GROUP]
    </p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
