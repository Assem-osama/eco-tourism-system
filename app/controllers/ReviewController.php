<?php

class ReviewController
{

    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }

    public function handleSubmit($loggedInUser)
    {
        $tripId       = (int) ($_POST["trip_id"] ?? 0);
        $rating       = (int) ($_POST["rating"] ?? 0);
        $ecoRating    = (int) ($_POST["eco_rating"] ?? 0);
        $comment      = trim($_POST["comment"] ?? "");

        if ($tripId <= 0 || $rating < 1 || $rating > 5 || $ecoRating < 1 || $ecoRating > 5) {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("Invalid review data."));
            exit;
        }

        // Only allow a review if the user has a confirmed or pending booking for this trip
        $bookingStatement = $this->db->prepare(
            "SELECT id FROM bookings
             WHERE user_id = ? AND trip_id = ? AND status != 'cancelled'
             LIMIT 1"
        );
        $bookingStatement->execute([$loggedInUser->id, $tripId]);
        if (!$bookingStatement->fetch()) {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("You can only review trips you have booked."));
            exit;
        }

        // Prevent duplicate reviews
        $duplicateStatement = $this->db->prepare(
            "SELECT id FROM reviews WHERE user_id = ? AND trip_id = ? LIMIT 1"
        );
        $duplicateStatement->execute([$loggedInUser->id, $tripId]);
        if ($duplicateStatement->fetch()) {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("You have already reviewed this trip."));
            exit;
        }

        $insertStatement = $this->db->prepare(
            "INSERT INTO reviews (user_id, trip_id, rating, eco_rating, comment)
             VALUES (?, ?, ?, ?, ?)"
        );
        $insertStatement->execute([$loggedInUser->id, $tripId, $rating, $ecoRating, $comment]);

        // Update the trip's sustainability_score as the average eco_rating
        $avgStatement = $this->db->prepare(
            "UPDATE trips
             SET sustainability_score = (
                 SELECT AVG(eco_rating) FROM reviews WHERE trip_id = ?
             )
             WHERE id = ?"
        );
        $avgStatement->execute([$tripId, $tripId]);

        header("Location: index.php?action=trip_detail&id=$tripId&success=" . urlencode("Review submitted. Thank you!"));
        exit;
    }
}
