<?php

class BookingController
{

    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }

    public function handleCreate($loggedInUser)
    {
        $tripId       = (int) ($_POST["trip_id"] ?? 0);
        $bookingDate  = trim($_POST["booking_date"] ?? "");

        if ($tripId <= 0 || empty($bookingDate)) {
            header("Location: index.php?action=trips&error=" . urlencode("Invalid booking request."));
            exit;
        }

        // Load the trip to get the price and check capacity
        $tripStatement = $this->db->prepare(
            "SELECT * FROM trips WHERE id = ? LIMIT 1"
        );
        $tripStatement->execute([$tripId]);
        $tripRow = $tripStatement->fetch();

        if (!$tripRow) {
            header("Location: index.php?action=trips&error=" . urlencode("Trip not found."));
            exit;
        }

        // Check the booking date is within the trip's available window
        if ($bookingDate < $tripRow["available_from"] || $bookingDate > $tripRow["available_to"]) {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("That date is outside the trip's available window."));
            exit;
        }

        // Prevent double booking: same user + same trip + not cancelled
        $duplicateStatement = $this->db->prepare(
            "SELECT id FROM bookings
             WHERE user_id = ? AND trip_id = ? AND status != 'cancelled'
             LIMIT 1"
        );
        $duplicateStatement->execute([$loggedInUser->id, $tripId]);
        if ($duplicateStatement->fetch()) {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("You have already booked this trip."));
            exit;
        }

        // Check capacity: count non-cancelled bookings for this trip
        $capacityStatement = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM bookings
             WHERE trip_id = ? AND status != 'cancelled'"
        );
        $capacityStatement->execute([$tripId]);
        $capacityRow = $capacityStatement->fetch();

        if ($capacityRow["total"] >= $tripRow["capacity"]) {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("Sorry, this trip is fully booked."));
            exit;
        }

        // All checks passed — create the booking
        $totalPrice = $tripRow["price"];

        $insertStatement = $this->db->prepare(
            "INSERT INTO bookings (user_id, trip_id, booking_date, status, total_price)
             VALUES (?, ?, ?, 'pending', ?)"
        );
        $insertStatement->execute([$loggedInUser->id, $tripId, $bookingDate, $totalPrice]);

        header("Location: index.php?action=my_bookings&success=" . urlencode("Booking confirmed!"));
        exit;
    }

    public function handleCancel($loggedInUser)
    {
        $bookingId    = (int) ($_POST["booking_id"] ?? 0);

        if ($bookingId <= 0) {
            header("Location: index.php?action=my_bookings");
            exit;
        }

        // Make sure this booking belongs to the logged-in user
        $statement = $this->db->prepare(
            "SELECT * FROM bookings WHERE id = ? AND user_id = ? LIMIT 1"
        );
        $statement->execute([$bookingId, $loggedInUser->id]);
        $bookingRow = $statement->fetch();

        if (!$bookingRow) {
            header("Location: index.php?action=my_bookings&error=" . urlencode("Booking not found."));
            exit;
        }

        if ($bookingRow["status"] == "cancelled") {
            header("Location: index.php?action=my_bookings&error=" . urlencode("This booking is already cancelled."));
            exit;
        }

        $cancelStatement = $this->db->prepare(
            "UPDATE bookings SET status = 'cancelled' WHERE id = ?"
        );
        $cancelStatement->execute([$bookingId]);

        header("Location: index.php?action=my_bookings&success=" . urlencode("Booking cancelled."));
        exit;
    }


    public function showMyBookings($loggedInUser)
    {

        $statement = $this->db->prepare(
            "SELECT bookings.*, trips.title AS trip_title, trips.location AS trip_location,
                    trips.price AS trip_price
             FROM bookings
             JOIN trips ON bookings.trip_id = trips.id
             WHERE bookings.user_id = ?
             ORDER BY bookings.created_at DESC"
        );
        $statement->execute([$loggedInUser->id]);
        $bookingRows = $statement->fetchAll();

        $bookings = [];
        foreach ($bookingRows as $row) {
            $booking               = new Booking();
            $booking->id           = $row["id"];
            $booking->trip_id      = $row["trip_id"];
            $booking->booking_date = $row["booking_date"];
            $booking->status       = $row["status"];
            $booking->total_price  = $row["total_price"];
            $booking->created_at   = $row["created_at"];
            $booking->trip_title    = $row["trip_title"];
            $booking->trip_location = $row["trip_location"];
            $bookings[] = $booking;
        }

        $errorMessage   = $_GET["error"]   ?? "";
        $successMessage = $_GET["success"] ?? "";

        require_once __DIR__ . "/../../views/bookings/my_bookings.php";
    }
}
