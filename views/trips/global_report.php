<?php require_once __DIR__ . '/../partials/nav.php'; ?>

<?php
$report = $report ?? [];
?>

<main class="page-content">

    <h2>🌍 Global Sustainability Report</h2>

    <p class="form-hint">
        Platform-wide environmental impact metrics.
    </p>

    <div class="report-grid">

        <div class="stat-card">
            <div class="stat-icon">💨</div>
            <div class="stat-value">
                <?= number_format($report["total_co2_offset_kg"] ?? 0, 1) ?>
            </div>
            <div class="stat-label">CO₂ Offset (kg)</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👷</div>
            <div class="stat-value">
                <?= number_format($report["total_local_jobs"] ?? 0) ?>
            </div>
            <div class="stat-label">Local Jobs</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🍃</div>
            <div class="stat-value">
                <?= number_format($report["avg_eco_leaf_score"] ?? 0, 1) ?>
            </div>
            <div class="stat-label">Avg Eco Score</div>
        </div>

    </div>

</main>