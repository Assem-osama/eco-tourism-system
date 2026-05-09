<nav class="navbar">

    <a href="index.php?action=dashboard" class="nav-brand">
        🌿 Eco Tourism
    </a>

    <div class="nav-links">

        <?php if (!empty($loggedInUser)): ?>



            <a href="index.php?action=trips">Trips</a>

            <a href="index.php?action=my_bookings">My Bookings</a>

            <a href="index.php?action=sustainability_report_global">
                Sustainability
            </a>

            <?php if ($loggedInUser->role === 'admin'): ?>
                <a href="index.php?action=admin_dashboard">
                    Admin Dashboard
                </a>

                <a href="index.php?action=trip_create">
                    Create Trip
                </a>
            <?php endif; ?>

            <?php if (
                $loggedInUser->role === 'guide'
            ): ?>

                <a href="index.php?action=guide_panel">
                    Guide Panel
                </a>

                <a href="index.php?action=guide_trips">
                    My Managed Trips
                </a>

                <a href="index.php?action=guide_profile">
                    Guide Profile
                </a>

                <a href="index.php?action=field_report">
                    Field Reports
                </a>

                <a href="index.php?action=trip_create">
                    Create Trip
                </a>

            <?php endif; ?>

            <span class="nav-user">
                <?= htmlspecialchars($loggedInUser->name ?? '') ?>
            </span>

            <span class="badge badge-<?= htmlspecialchars($loggedInUser->role ?? 'traveler') ?>">
                <?= htmlspecialchars($loggedInUser->role ?? 'traveler') ?>
            </span>

            <a href="index.php?action=logout" class="btn btn-outline btn-sm">
                Logout
            </a>

        <?php else: ?>

            <a href="index.php?action=login">Login</a>

            <a href="index.php?action=register">Register</a>

        <?php endif; ?>

    </div>

</nav>