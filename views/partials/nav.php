<nav class="navbar">
    <a href="index.php?action=dashboard" class="nav-brand">🌿 Eco Tourism</a>
    <div class="nav-links">
        <a href="index.php?action=trips">Trips</a>
        <a href="index.php?action=my_bookings">My bookings</a>
        <span class="nav-user"><?= htmlspecialchars($loggedInUser->name) ?></span>
        <span class="badge badge-<?= htmlspecialchars($loggedInUser->role) ?>"><?= htmlspecialchars($loggedInUser->role) ?></span>
        <a href="index.php?action=logout" class="btn btn-outline btn-sm">Log out</a>
    </div>
</nav>