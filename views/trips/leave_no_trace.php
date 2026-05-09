<?php
$pageTitle = 'Leave No Trace — Eco Tourism';
require_once __DIR__ . '/../../views/partials/header.php';
require_once __DIR__ . '/../../views/partials/nav.php';
?>

<main class="page-content">

    <div class="header-section">
        <h2>📄 Leave No Trace</h2>
        <p class="form-hint">
            Your personal guide to environmental responsibility and ethical travel practices.
        </p>
    </div>

    <div class="form-card animate-fade-in">

        <div class="download-container">
            <div class="document-preview">
                <div class="doc-icon">📑</div>
                <div class="doc-details">
                    <h3>Traveler Guidelines.pdf</h3>
                    <p>Customized for your specific trip type and location.</p>
                </div>
            </div>

            <div class="download-action">
                <a class="btn btn-primary"
                    href="storage/<?= htmlspecialchars($fileName ?? '') ?>"
                    download>
                    <span class="icon">⬇️</span> Download Guidelines
                </a>
            </div>
        </div>

        <div class="policy-brief">
            <h4>Principles you'll find inside:</h4>
            <ul>
                <li>Plan ahead and prepare</li>
                <li>Travel and camp on durable surfaces</li>
                <li>Dispose of waste properly</li>
                <li>Leave what you find</li>
                <li>Respect wildlife</li>
            </ul>
        </div>

    </div>

    <div class="actions">
        <a href="index.php?action=my_bookings" class="btn btn-outline">Back to My Bookings</a>
    </div>

</main>

<?php require_once __DIR__ . '/../../views/partials/footer.php'; ?>