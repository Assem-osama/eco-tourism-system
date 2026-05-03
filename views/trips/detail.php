
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($trip->title) ?> — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require_once __DIR__ . "/../partials/nav.php"; ?>

<main class="page-content">

    <?php if (!empty($_GET["error"])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET["error"]) ?></div>
    <?php endif; ?>
    <?php if (!empty($_GET["success"])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET["success"]) ?></div>
    <?php endif; ?>

    <div class="detail-card">
        <h2><?= htmlspecialchars($trip->title) ?></h2>
        <p class="trip-location">📍 <?= htmlspecialchars($trip->location) ?></p>
        <p><?= nl2br(htmlspecialchars($trip->description)) ?></p>

        <div class="detail-meta">
            <div><strong>Price</strong><br>$<?= number_format($trip->price, 2) ?></div>
            <div><strong>Capacity</strong><br><?= (int) $trip->capacity ?> people</div>
            <div><strong>Dates</strong><br><?= htmlspecialchars($trip->available_from) ?> → <?= htmlspecialchars($trip->available_to) ?></div>
            <div><strong>Eco score</strong><br>🌱 <?= number_format($trip->sustainability_score, 1) ?>/5</div>
        </div>

        <div class="guide-box">
            <strong>Your guide: <?= htmlspecialchars($trip->guide_name) ?></strong>
            <?php if (!empty($trip->guide_bio)): ?>
                <p><?= htmlspecialchars($trip->guide_bio) ?></p>
            <?php endif; ?>
        </div>

        <!-- Booking form -->
        <form method="POST" action="index.php?action=booking_create" class="booking-form">
            <input type="hidden" name="trip_id" value="<?= $trip->id ?>">
            <label>Choose your date</label>
            <input type="date" name="booking_date"
                   min="<?= $trip->available_from ?>"
                   max="<?= $trip->available_to ?>" required>
            <button type="submit" class="btn btn-primary">Book this trip</button>
        </form>
    </div>

    <!-- Reviews section -->
    <section class="reviews-section">
        <h3>Reviews (<?= count($reviews) ?>)</h3>

        <?php if (empty($reviews)): ?>
            <p>No reviews yet. Be the first!</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <strong><?= htmlspecialchars($review["reviewer_name"]) ?></strong>
                        <span>⭐ <?= (int)$review["rating"] ?>/5</span>
                        <span>🌱 Eco: <?= (int)$review["eco_rating"] ?>/5</span>
                        <span class="review-date"><?= htmlspecialchars($review["created_at"]) ?></span>
                    </div>
                    <?php if (!empty($review["comment"])): ?>
                        <p><?= htmlspecialchars($review["comment"]) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Submit a review -->
        <form method="POST" action="index.php?action=review_submit" class="review-form">
            <input type="hidden" name="trip_id" value="<?= $trip->id ?>">
            <h4>Leave a review</h4>

            <label>Rating (1–5)</label>
            <select name="rating" required>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= $i ?> star<?= $i > 1 ? 's' : '' ?></option>
                <?php endfor; ?>
            </select>

            <label>Eco rating (1–5)</label>
            <select name="eco_rating" required>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>

            <label>Comment (optional)</label>
            <textarea name="comment" rows="3"></textarea>

            <button type="submit" class="btn btn-primary">Submit review</button>
        </form>
    </section>

</main>
</body>
</html>