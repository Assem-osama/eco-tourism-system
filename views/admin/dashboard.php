<?php
$pageTitle = 'Admin Dashboard — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";

$userCount = $userCount ?? 0;
$guideCount = $guideCount ?? 0;
$tripCount = $tripCount ?? 0;
$bookingCount = $bookingCount ?? 0;
?>

<main class="page-content">
    <div class="header-section">
        <h2>👑 Administrator Command Center</h2>
        <p class="form-hint">Oversee the entire eco-tourism platform, monitor growth, and ensure operational excellence.</p>
    </div>

    <!-- Admin Metrics -->
    <div class="metrics-grid animate-fade-in">
        <div class="metric-card">
            <div class="metric-icon">👥</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($userCount) ?></span>
                <span class="metric-label">Total Users</span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon">🧭</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($guideCount) ?></span>
                <span class="metric-label">Total Guides</span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon">🗺️</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($tripCount) ?></span>
                <span class="metric-label">Active Trips</span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon">💰</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($bookingCount) ?></span>
                <span class="metric-label">Total Bookings</span>
            </div>
        </div>
    </div>

    <div class="dashboard-content-grid">
        
        <!-- Management Sections -->
        <div class="dashboard-column">
            <div class="form-card animate-fade-in">
                <h3>🛠️ System Management</h3>
                <div class="quick-actions-list">
                    <a href="index.php?action=admin_trips" class="action-item">
                        <span class="icon">✅</span>
                        <div class="action-text">
                            <strong>Trip Vetting Queue</strong>
                            <p>Review and approve new trips</p>
                        </div>
                    </a>
                    <a href="index.php?action=admin_guides_vetting" class="action-item">
                        <span class="icon">🧑‍🌾</span>
                        <div class="action-text">
                            <strong>Guide Vetting Queue</strong>
                            <p>Approve new guide applications</p>
                        </div>
                    </a>
                    <a href="index.php?action=sustainability_report_global" class="action-item">
                        <span class="icon">📊</span>
                        <div class="action-text">
                            <strong>Impact Analytics</strong>
                            <p>Global sustainability reporting</p>
                        </div>
                    </a>
                    <a href="index.php?action=admin_logs" class="action-item">
                        <span class="icon">📝</span>
                        <div class="action-text">
                            <strong>System Audit Logs</strong>
                            <p>Review critical system actions</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>
