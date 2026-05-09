<?php
$pageTitle = 'Admin Dashboard — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";

$userCount = $userCount ?? 0;
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
        <div class="metric-card">
            <div class="metric-icon">📉</div>
            <div class="metric-info">
                <span class="metric-value">94%</span>
                <span class="metric-label">Growth Rate</span>
            </div>
        </div>
    </div>

    <div class="dashboard-content-grid">
        
        <!-- Management Sections -->
        <div class="dashboard-column">
            <div class="form-card animate-fade-in">
                <h3>🛠️ System Management</h3>
                <div class="quick-actions-list">
                    <a href="#" class="action-item">
                        <span class="icon">👤</span>
                        <div class="action-text">
                            <strong>User Management</strong>
                            <p>Verify guides and manage roles</p>
                        </div>
                    </a>
                    <a href="index.php?action=trips" class="action-item">
                        <span class="icon">🚲</span>
                        <div class="action-text">
                            <strong>Global Trip Catalog</strong>
                            <p>Monitor and review all active tours</p>
                        </div>
                    </a>
                    <a href="index.php?action=sustainability_report_global" class="action-item">
                        <span class="icon">📊</span>
                        <div class="action-text">
                            <strong>Impact Analytics</strong>
                            <p>Global sustainability reporting</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Logs -->
        <div class="dashboard-column">
            <div class="form-card animate-fade-in">
                <h3>🔔 Recent Platform Activity</h3>
                <div class="activity-log">
                    <div class="log-item">
                        <span class="time">2 mins ago</span>
                        <p><strong>New Guide Registered:</strong> Sarah Green (Certified Diver)</p>
                    </div>
                    <div class="log-item">
                        <span class="time">15 mins ago</span>
                        <p><strong>Booking Confirmed:</strong> Sinai Desert Safari (ID: #4502)</p>
                    </div>
                    <div class="log-item">
                        <span class="time">1 hour ago</span>
                        <p><strong>Certificate Uploaded:</strong> Guide #102 updated First Aid</p>
                    </div>
                </div>
                <a href="#" class="text-link" style="margin-top: 1rem; display: inline-block;">View All Logs →</a>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>
