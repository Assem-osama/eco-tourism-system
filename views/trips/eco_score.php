<?php
$pageTitle = 'Eco-Leaf Score — Eco Tourism';
require_once __DIR__ . '/../../views/partials/header.php';
require_once __DIR__ . '/../../views/partials/nav.php';

$score = $score ?? 0;
?>

<main class="page-content">

    <div class="header-section">
        <h2>🍃 Eco-Leaf Score</h2>
        <p class="form-hint">
            A comprehensive metric evaluating carbon footprint, waste management, and community impact.
        </p>
    </div>

    <div class="form-card animate-fade-in">

        <div class="score-display">
            <div class="score-circle">
                <span class="score-value"><?= number_format($score, 1) ?></span>
                <span class="score-max">/ 100</span>
            </div>
            
            <div class="score-rating">
                <?php
                if ($score >= 80) echo "🌟 Outstanding Sustainability";
                elseif ($score >= 60) echo "🌿 Good Eco-Balance";
                elseif ($score >= 40) echo "🌱 Moderate Impact";
                else echo "⚠️ Potential for Improvement";
                ?>
            </div>
        </div>

        <div class="score-progress-container">
            <label>Overall Sustainability Index</label>
            <div class="eco-leaf-bar-wrap">
                <div class="eco-leaf-bar" style="width: <?= min(100, $score) ?>%"></div>
            </div>
        </div>

        <div class="score-breakdown">
            <p>Our scores are calculated based on verified data from transport methods, waste management practices, and guide credentials.</p>
        </div>

    </div>

    <div class="actions">
        <a href="index.php?action=trips" class="btn btn-outline">Back to Trips</a>
    </div>

</main>

<?php require_once __DIR__ . '/../../views/partials/footer.php'; ?>