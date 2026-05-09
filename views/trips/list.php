<?php
$pageTitle = 'Trips — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";
?>

<main class="page-content">
    <div class="page-header">
        <h2>Available Trips</h2>
        <?php if ($loggedInUser->role === "guide" || $loggedInUser->role === "admin"): ?>
            <a href="index.php?action=trip_create" class="btn btn-primary">+ Add trip</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($_GET["success"])): ?>
        <div class="alert alert-success animate-fade-in"><?= htmlspecialchars($_GET["success"]) ?></div>
    <?php endif; ?>

    <?php if (empty($trips)): ?>
        <div class="empty-state animate-fade-in">
            <div class="empty-icon">🏜️</div>
            <p>No trips available yet. Check back soon for new eco-friendly adventures!</p>
        </div>
    <?php else: ?>
        <div class="trip-grid animate-fade-in">
            <?php foreach ($trips as $trip): ?>
                <div class="trip-card">
                    <div class="trip-image-placeholder">🏕️</div>
                    <div class="trip-content">
                        <h3><?= htmlspecialchars($trip->title) ?></h3>
                        <p class="trip-location">📍 <?= htmlspecialchars($trip->location) ?></p>
                        <p class="trip-desc"><?= htmlspecialchars(mb_substr($trip->description, 0, 100)) ?>...</p>
                        <div class="trip-meta">
                            <span class="price">$<?= number_format($trip->price, 2) ?></span>
                            <span class="eco-score-badge" title="Sustainability score">
                                🌱 <?= number_format($trip->sustainability_score, 1) ?>/100
                            </span>
                        </div>
                        <div class="trip-footer">
                            <p class="trip-guide">By <?= htmlspecialchars($trip->guide_name) ?></p>
                            <a href="index.php?action=trip_detail&id=<?= $trip->id ?>" class="btn btn-sm btn-outline">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>