<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Bookings — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php require_once __DIR__ . "/../partials/nav.php"; ?>

    <main class="page-content">
        <h2>My Bookings</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <p>You have no bookings yet. <a href="index.php?action=trips">Browse trips →</a></p>
        <?php else: ?>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Trip</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td>
                                <a href="index.php?action=trip_detail&id=<?= $booking->trip_id ?>">
                                    <?= htmlspecialchars($booking->trip_title) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($booking->trip_location) ?></td>
                            <td><?= htmlspecialchars($booking->booking_date) ?></td>
                            <td>$<?= number_format($booking->total_price, 2) ?></td>
                            <td><span class="badge badge-<?= htmlspecialchars($booking->status) ?>"><?= htmlspecialchars($booking->status) ?></span></td>
                            <td>
                                <?php if ($booking->status !== "cancelled"): ?>
                                    <form method="POST" action="index.php?action=booking_cancel"
                                        onsubmit="return confirm('Cancel this booking?')">
                                        <input type="hidden" name="booking_id" value="<?= $booking->id ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>

</html>