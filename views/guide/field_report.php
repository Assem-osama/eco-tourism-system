<?php
$pageTitle = 'Field Report — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";

$activeTrips = $activeTrips ?? [];
$selectedTripId = (int) ($_GET['trip_id'] ?? 0);
?>

<main class="page-content">
    <div class="header-section">
        <h2>📋 Submit a Field Report</h2>
        <p class="form-hint">Share live updates, observations, or photos from your active trip to maintain transparency and community trust.</p>
    </div>

    <?php if (!empty($_GET["error"])): ?>
        <div class="alert alert-error animate-fade-in"><?= htmlspecialchars($_GET["error"]) ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET["success"])): ?>
        <div class="alert alert-success animate-fade-in"><?= htmlspecialchars($_GET["success"]) ?></div>
    <?php endif; ?>

    <div class="form-container animate-fade-in">
        <form method="POST" action="index.php?action=field_report_submit" enctype="multipart/form-data" class="form-card">
            
            <div class="form-group">
                <label>Select Active Trip *</label>
                <select name="trip_id" required>
                    <option value="">— Select your active trip —</option>
                    <?php if (!empty($activeTrips)): ?>
                        <?php foreach ($activeTrips as $activeTrip): ?>
                            <option value="<?= (int) $activeTrip["id"] ?>" <?= $selectedTripId === (int)$activeTrip["id"] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($activeTrip["title"]) ?> (<?= htmlspecialchars($activeTrip["location"]) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Observation Details</label>
                <textarea name="report_text" rows="6" placeholder="Describe trail conditions, wildlife sightings, or any eco-concerns..."></textarea>
            </div>

            <div class="form-group">
                <label>Field Photo (Optional)</label>
                <div class="file-upload-wrapper">
                    <input type="file" name="field_photo" accept="image/*" class="file-input">
                    <div class="file-dummy">
                        <span class="icon">📸</span>
                        <span class="text">Click to upload or drag and drop</span>
                    </div>
                </div>
                <p class="form-hint">JPG or PNG. Max 5MB.</p>
            </div>

            <div class="alert-info" style="margin-top: 1rem;">
                ℹ️ At least a report text <strong>or</strong> a photo is required to submit.
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit Live Report</button>
                <a href="index.php?action=guide_panel" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>