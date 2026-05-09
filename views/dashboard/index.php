<?php
$pageTitle = 'Dashboard — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
require_once __DIR__ . "/../../views/partials/nav.php";
?>

<main class="page-content">
    <div class="welcome-banner animate-fade-in">
        <h2>Welcome back, <?= htmlspecialchars($loggedInUser->name) ?>! 👋</h2>
        <p>Your role: <span class="badge badge-<?= htmlspecialchars($loggedInUser->role) ?>"><?= htmlspecialchars($loggedInUser->role) ?></span></p>
    </div>

    <div class="card-grid">
        <a href="index.php?action=trips" class="action-card">
            <div class="card-icon">🏕️</div>
            <h3>Browse Trips</h3>
            <p>Find eco-friendly experiences</p>
        </a>
        <a href="index.php?action=my_bookings" class="action-card">
            <div class="card-icon">📋</div>
            <h3>My Bookings</h3>
            <p>Manage your upcoming trips</p>
        </a>
        <?php if ($loggedInUser->role === "guide" || $loggedInUser->role === "admin"): ?>
            <a href="index.php?action=trip_create" class="action-card">
                <div class="card-icon">➕</div>
                <h3>Create a Trip</h3>
                <p>Add a new experience</p>
            </a>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>