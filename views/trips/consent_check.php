<?php
$pageTitle = 'Indigenous Consent — Eco Tourism';
require_once __DIR__ . '/../../views/partials/header.php';
require_once __DIR__ . '/../../views/partials/nav.php';
?>

<main class="page-content">

    <div class="header-section">
        <h2>⚠️ Indigenous Consent</h2>
        <p class="form-hint">
            Respecting local cultures and ensuring ethical access to protected community lands.
        </p>
    </div>

    <div class="form-card animate-fade-in">

        <div class="status-box success">
            <div class="status-icon">✔</div>
            <div class="status-content">
                <h3>Access Approved</h3>
                <p>We have verified that this trip has received the necessary permissions from local indigenous communities.</p>
            </div>
        </div>

        <div class="info-section">
            <p>Eco Tourism works closely with local leaders to ensure all visits are respectful, sustainable, and provide direct benefit to the community.</p>
        </div>

    </div>

    <div class="actions">
        <a href="index.php?action=trips" class="btn btn-outline">Back to Trips</a>
    </div>

</main>

<?php require_once __DIR__ . '/../../views/partials/footer.php'; ?>