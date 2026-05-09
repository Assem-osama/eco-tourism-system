<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Trip — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php require_once __DIR__ . "/../partials/nav.php"; ?>

    <main class="page-content">
        <h2>Create a New Trip</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=trip_create_submit" class="form-card">

            <h3 class="form-section-title">📋 Basic Information</h3>

            <label>Title *</label>
            <input type="text" name="title" required>

            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Describe the experience..."></textarea>

            <label>Location *</label>
            <input type="text" name="location" required placeholder="e.g. Sinai, Egypt">

            <div class="form-row">
                <div>
                    <label>Price (USD) *</label>
                    <input type="number" name="price" min="1" step="0.01" required>
                </div>
                <div>
                    <label>Capacity (people) *</label>
                    <input type="number" name="capacity" min="1" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label>Available from *</label>
                    <input type="date" name="available_from" required>
                </div>
                <div>
                    <label>Available to *</label>
                    <input type="date" name="available_to" required>
                </div>
            </div>

            <h3 class="form-section-title">🚗 Transport</h3>

            <label>Transport type</label>
            <select name="transport_type">
                <option value="">— Select —</option>
                <option value="walking">🚶 Walking</option>
                <option value="bike">🚴 Bike</option>
                <option value="bus">🚌 Bus</option>
                <option value="plane">✈️ Plane</option>
                <option value="other">Other</option>
            </select>

            <label>Trip type</label>
            <select name="type">
                <option value="general">General</option>
                <option value="desert">Desert</option>
                <option value="marine">Marine</option>
            </select>

            <h3 class="form-section-title">🎒 Equipment</h3>

            <div class="form-row">
                <div>
                    <label>Equipment type</label>
                    <input type="text" name="equipment_type" placeholder="e.g. Snorkel gear, Tents">
                </div>
                <div>
                    <label>Equipment quantity</label>
                    <input type="number" name="equipment_total" min="0" value="0">
                </div>
            </div>

            <h3 class="form-section-title">🌿 Eco Impact Tags</h3>
            <p class="form-hint">Check all that apply to this trip.</p>

            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="no_plastic" value="1">
                    ♻️ Plastic-Free Experience
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="local_food" value="1">
                    🍽️ Supports Local Food / Community
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="wildlife_support" value="1">
                    🐾 Supports Local Wildlife
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Trip</button>
                <a href="index.php?action=trips" class="btn btn-outline">Cancel</a>
            </div>

        </form>
    </main>
</body>

</html>