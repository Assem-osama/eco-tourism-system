<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Trip — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php require_once __DIR__ . "/../partials/nav.php"; ?>

    <main class="page-content">
        <h2>Edit Trip: <?= htmlspecialchars($trip['title'] ?? '') ?></h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=trip_edit_submit" class="form-card">
            <input type="hidden" name="id" value="<?= htmlspecialchars($trip['id'] ?? '') ?>">

            <h3 class="form-section-title">📋 Basic Information</h3>

            <label>Title *</label>
            <input type="text" name="title" value="<?= htmlspecialchars($trip['title'] ?? '') ?>" required>

            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Describe the experience..."><?= htmlspecialchars($trip['description'] ?? '') ?></textarea>

            <label>Location *</label>
            <input type="text" name="location" value="<?= htmlspecialchars($trip['location'] ?? '') ?>" required placeholder="e.g. Sinai, Egypt">

            <div class="form-row">
                <div>
                    <label>Price (USD) *</label>
                    <input type="number" name="price" value="<?= htmlspecialchars($trip['price'] ?? '') ?>" min="1" step="0.01" required>
                </div>
                <div>
                    <label>Capacity (people) *</label>
                    <input type="number" name="capacity" value="<?= htmlspecialchars($trip['capacity'] ?? '') ?>" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Available from *</label>
                    <input type="date" name="available_from" value="<?= htmlspecialchars($trip['available_from'] ?? '') ?>" required>
                </div>
                <div>
                    <label>Available to *</label>
                    <input type="date" name="available_to" value="<?= htmlspecialchars($trip['available_to'] ?? '') ?>" required>
                </div>
            </div>

            <h3 class="form-section-title">🎒 Equipment</h3>

            <div class="form-row">
                <div>
                    <label>Equipment type</label>
                    <input type="text" name="equipment_type" value="<?= htmlspecialchars($trip['equipment_type'] ?? '') ?>" placeholder="e.g. Snorkel gear, Tents">
                </div>
                <div>
                    <label>Equipment quantity</label>
                    <input type="number" name="equipment_total" value="<?= htmlspecialchars($trip['equipment_total'] ?? 0) ?>" min="0">
                </div>
            </div>
            
            <div class="alert alert-info" style="margin-top: 15px;">
                Note: Updating a trip will automatically return its status to "pending" to be re-approved by an Administrator.
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Trip</button>
                <a href="index.php?action=guide_trips" class="btn btn-outline">Cancel</a>
            </div>

        </form>
    </main>
</body>

</html>
