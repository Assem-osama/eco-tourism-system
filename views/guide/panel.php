<?php
$pageTitle = 'Guide Panel — Eco Tourism';
require_once __DIR__ . '/../../views/partials/header.php';
require_once __DIR__ . '/../../views/partials/nav.php';

$guideData = $guideData ?? [];
$totalTrips = $totalTrips ?? 0;
$totalBookings = $totalBookings ?? 0;
$pendingShadowRequests = $pendingShadowRequests ?? [];
?>

<main class="page-content">

    <div class="header-section">
        <h2>🛠️ Guide Command Center</h2>
        <p class="form-hint">Manage your sustainable adventures, monitor your impact, and mentor the next generation of guides.</p>
    </div>

    <?php if (($guideData['status'] ?? 'pending') !== 'approved'): ?>
        <div class="empty-state animate-fade-in" style="margin-top: 40px;">
            <div class="empty-icon">⏳</div>
            <h3>Account Pending Approval</h3>
            <p>Welcome! Before you can start creating trips and managing bookings, you must complete your Guide Profile and have it approved by an Administrator.</p>
            <p>Please click below to upload your certificates and fill in your details.</p>
            <br>
            <a href="index.php?action=guide_profile" class="btn btn-primary">Go to My Guide Profile</a>
        </div>
    <?php else: ?>

    <!-- Metrics Grid -->
    <div class="metrics-grid animate-fade-in">
        <div class="metric-card">
            <div class="metric-icon">🗺️</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($totalTrips) ?></span>
                <span class="metric-label">Active Trips</span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon">👥</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($totalBookings) ?></span>
                <span class="metric-label">Total Travelers</span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon">⭐</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($guideData['local_cred_score'] ?? 0, 1) ?></span>
                <span class="metric-label">Credibility Score</span>
            </div>
        </div>
        <div class="metric-card">
            <div class="metric-icon">🍃</div>
            <div class="metric-info">
                <span class="metric-value"><?= number_format($guideData['sustainability_score'] ?? 0, 1) ?></span>
                <span class="metric-label">Eco Score</span>
            </div>
        </div>
    </div>

    <div class="dashboard-content-grid">
        
        <!-- Left Column: Actions & Quick Links -->
        <div class="dashboard-column">
            <div class="form-card animate-fade-in">
                <h3>⚡ Quick Actions</h3>
                <div class="quick-actions-list">
                    <a href="index.php?action=trip_create" class="action-item">
                        <span class="icon">➕</span>
                        <div class="action-text">
                            <strong>Create New Trip</strong>
                            <p>Launch a new eco-tour</p>
                        </div>
                    </a>
                    <a href="index.php?action=guide_trips" class="action-item">
                        <span class="icon">📂</span>
                        <div class="action-text">
                            <strong>Manage My Trips</strong>
                            <p>Edit or update existing tours</p>
                        </div>
                    </a>
                    <a href="index.php?action=field_report" class="action-item">
                        <span class="icon">📋</span>
                        <div class="action-text">
                            <strong>Submit Field Report</strong>
                            <p>Send live trip updates</p>
                        </div>
                    </a>
                    <a href="index.php?action=guide_profile" class="action-item">
                        <span class="icon">👤</span>
                        <div class="action-text">
                            <strong>Update Profile</strong>
                            <p>Certifications & Languages</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column: Pending Tasks / Alerts -->
        <div class="dashboard-column">
            
            <?php if (!empty($pendingShadowRequests)): ?>
                <div class="form-card alert-card animate-fade-in">
                    <h3>👥 Pending Trainee Requests</h3>
                    <p class="form-hint">Mentor requests waiting for your approval.</p>
                    
                    <div class="shadow-requests-list">
                        <?php foreach ($pendingShadowRequests as $request): ?>
                            <div class="shadow-request-item">
                                <div class="request-info">
                                    <strong><?= htmlspecialchars($request["trainee_name"]) ?></strong>
                                    <p>Wants to shadow: <em><?= htmlspecialchars($request["trip_title"]) ?></em></p>
                                </div>
                                <form method="POST" action="index.php?action=trainee_shadow_approve">
                                    <input type="hidden" name="trip_id" value="<?= (int) $request["trip_id"] ?>">
                                    <input type="hidden" name="trainee_guide_id" value="<?= (int) $request["trainee_guide_id"] ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-card animate-fade-in">
                <h3>📣 Community Update</h3>
                <div class="announcement">
                    <span class="date">May 15, 2024</span>
                    <h4>New Indigenous Land Access Policy</h4>
                    <p>Please review the updated guidelines for Desert Safaris in the Sinai region. New permits required starting June 1st.</p>
                    <a href="#" class="text-link">Read More →</a>
                </div>
            </div>

        </div>

    </div>
    
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../../views/partials/footer.php'; ?>