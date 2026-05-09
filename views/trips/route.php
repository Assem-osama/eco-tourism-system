<?php
$pageTitle = 'Optimized Route — Eco Tourism';
require_once __DIR__ . '/../../views/partials/header.php';
require_once __DIR__ . '/../../views/partials/nav.php';

$route = $route ?? [];
?>

<main class="page-content">

    <div class="header-section">
        <h2>🗺️ Optimized Sustainable Route</h2>
        <p class="form-hint">
            The most efficient path designed to minimize travel emissions and environmental disruption.
        </p>
    </div>

    <div class="form-card animate-fade-in">

        <div class="route-container">
            <ol class="route-steps">
                <?php foreach ($route as $index => $loc): ?>
                    <li class="route-step">
                        <div class="step-number"><?= $index + 1 ?></div>
                        <div class="step-info">
                            <h4><?= htmlspecialchars($loc["name"] ?? 'Unknown Location') ?></h4>
                            <?php if (isset($loc["lat"], $loc["lng"])): ?>
                                <p class="coords"><?= $loc["lat"] ?>, <?= $loc["lng"] ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>

        <?php if (empty($route)): ?>
            <p class="empty-msg">No location data available for this trip.</p>
        <?php endif; ?>

    </div>

    <div class="actions">
        <a href="index.php?action=trips" class="btn btn-outline">Back to Trips</a>
    </div>

</main>

<?php require_once __DIR__ . '/../../views/partials/footer.php'; ?>