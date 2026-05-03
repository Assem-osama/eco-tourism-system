
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
    <h2>Create a new trip</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=trip_create_submit" class="form-card">

        <label>Title *</label>
        <input type="text" name="title" required>

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <label>Location *</label>
        <input type="text" name="location" required>

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

        <button type="submit" class="btn btn-primary">Create trip</button>
        <a href="index.php?action=trips" class="btn btn-outline">Cancel</a>
    </form>
</main>
</body>
</html>