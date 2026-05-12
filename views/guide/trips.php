<?php
$pageTitle = 'My Trips — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";
?>

<main class="page-content">
    <div class="page-header">
        <h2>📂 My Managed Trips</h2>
        <a href="index.php?action=trip_create" class="btn btn-primary">+ Create New Trip</a>
    </div>

    <p class="form-hint">View and manage the trips you have created. Monitor bookings and update trip details.</p>

    <?php if (!empty($_GET["success"])): ?>
        <div class="alert alert-success animate-fade-in"><?= htmlspecialchars($_GET["success"]) ?></div>
    <?php endif; ?>

    <?php if (empty($trips)): ?>
        <div class="empty-state animate-fade-in">
            <div class="empty-icon">🎒</div>
            <p>You haven't created any trips yet. Start your journey by creating your first eco-adventure!</p>
            <a href="index.php?action=trip_create" class="btn btn-primary">Create Trip</a>
        </div>
    <?php else: ?>
        <div class="trip-management-list animate-fade-in">
            <?php foreach ($trips as $trip): ?>
                <div class="trip-manage-card">
                    <div class="trip-main-info">
                        <h3><?= htmlspecialchars($trip->title) ?></h3>
                        <p class="location">📍 <?= htmlspecialchars($trip->location) ?></p>
                        <div class="trip-status-pills">
                            <span class="pill pill-price">$<?= number_format($trip->price, 2) ?></span>
                            <span class="pill pill-eco">🌱 <?= number_format($trip->sustainability_score, 1) ?> Eco</span>
                        </div>
                    </div>
                    
                    <div class="trip-actions">
                        <a href="index.php?action=trip_detail&id=<?= $trip->id ?>" class="btn btn-sm btn-outline">View Details</a>
                        <a href="index.php?action=trip_edit&id=<?= $trip->id ?>" class="btn btn-sm btn-outline">Edit Trip</a>
                        <a href="index.php?action=field_report&trip_id=<?= $trip->id ?>" class="btn btn-sm btn-outline">Field Report</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="actions" style="margin-top: 2rem;">
        <a href="index.php?action=guide_panel" class="btn btn-outline">Back to Guide Panel</a>
    </div>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>
