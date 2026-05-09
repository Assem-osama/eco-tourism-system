<?php
$pageTitle = 'Carbon Offset — Eco Tourism';
require_once __DIR__ . '/../../views/partials/header.php';
require_once __DIR__ . '/../../views/partials/nav.php';

$result = $result ?? [];
?>

<main class="page-content">

    <div class="header-section">
        <h2>🌍 Carbon Offset</h2>
        <p class="form-hint">
            Calculating the environmental impact of your journey and providing sustainable alternatives.
        </p>
    </div>

    <div class="form-card animate-fade-in">

        <div class="impact-highlight">
            <div class="stat-box">
                <label>Estimated Carbon Footprint</label>
                <div class="stat-value big-stat">
                    <?= number_format($result["carbon_cost"] ?? 0, 2) ?> <span>kg CO₂</span>
                </div>
            </div>
        </div>

        <div class="projects-section">
            <h3>🌱 Recommended Offset Projects</h3>
            <p class="section-desc">Contribute to these local initiatives to neutralize your travel emissions.</p>

            <div class="project-grid">
                <?php foreach (($result["carbon_projects"] ?? []) as $p): ?>
                    <div class="project-card">
                        <div class="project-info">
                            <h4><?= htmlspecialchars($p["name"] ?? 'Unnamed Project') ?></h4>
                            <p class="location-tag">📍 <?= htmlspecialchars($p["location"] ?? 'Unknown Location') ?></p>
                        </div>
                        <div class="project-pricing">
                            <span class="price-label">Cost per kg:</span>
                            <span class="price-value">$<?= number_format($p["cost_per_kg"] ?? 0, 3) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <div class="actions">
        <a href="index.php?action=trips" class="btn btn-outline">Back to Trips</a>
    </div>

</main>

<?php require_once __DIR__ . '/../../views/partials/footer.php'; ?>