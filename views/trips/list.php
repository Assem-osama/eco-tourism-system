<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Trips — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php require_once __DIR__ . "/../partials/nav.php"; ?>

    <main class="page-content">
        <div class="page-header">
            <h2>Available Trips</h2>
            <?php if ($loggedInUser->role === "guide" || $loggedInUser->role === "admin"): ?>
                <a href="index.php?action=trip_create" class="btn btn-primary">+ Add trip</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($_GET["success"])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET["success"]) ?></div>
        <?php endif; ?>

        <?php if (empty($trips)): ?>
            <p>No trips available yet.</p>
        <?php else: ?>
            <div class="trip-grid">
                <?php foreach ($trips as $trip): ?>
                    <div class="trip-card">
                        <h3><?= htmlspecialchars($trip->title) ?></h3>
                        <p class="trip-location">📍 <?= htmlspecialchars($trip->location) ?></p>
                        <p class="trip-desc"><?= htmlspecialchars(mb_substr($trip->description, 0, 120)) ?>...</p>
                        <div class="trip-meta">
                            <span class="price">$<?= number_format($trip->price, 2) ?></span>
                            <span class="eco-score" title="Sustainability score">
                                🌱 <?= number_format($trip->sustainability_score, 1) ?>/5
                            </span>
                        </div>
                        <p class="trip-guide">Guide: <?= htmlspecialchars($trip->guide_name) ?></p>
                        <a href="index.php?action=trip_detail&id=<?= $trip->id ?>" class="btn btn-outline">View details</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>