<?php

class BookingController
{

    private $db;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
    }

    public function showCheckout($loggedInUser)
    {
        $tripId       = (int) ($_POST["trip_id"] ?? 0);
        $bookingDate  = trim($_POST["booking_date"] ?? "");

        if ($tripId <= 0 || empty($bookingDate)) {
            header("Location: index.php?action=trips&error=" . urlencode("Invalid checkout request."));
            exit;
        }

        // Load the trip
        $tripStatement = $this->db->prepare("SELECT * FROM trips WHERE id = ? LIMIT 1");
        $tripStatement->execute([$tripId]);
        $tripRow = $tripStatement->fetch();

        if (!$tripRow) {
            header("Location: index.php?action=trips&error=" . urlencode("Trip not found."));
            exit;
        }

        // Prevent duplicate booking: same user + same trip + same date + not cancelled
        $duplicateCheck = $this->db->prepare(
            "SELECT id FROM bookings 
             WHERE user_id = ? AND trip_id = ? AND booking_date = ? AND status != 'cancelled' 
             LIMIT 1"
        );
        $duplicateCheck->execute([$loggedInUser->id, $tripId, $bookingDate]);
        if ($duplicateCheck->fetch()) {
            header("Location: index.php?action=trip_detail&id=$tripId&error=" . urlencode("You have already booked this trip on this date."));
            exit;
        }

        $trip = new Trip();
        $trip->id = $tripRow["id"];
        $trip->title = $tripRow["title"];
        $trip->location = $tripRow["location"];
        $trip->price = $tripRow["price"];

        $numPeople = 1;
        $totalPrice = $trip->price * $numPeople;

        // Ensure extras tables exist automatically in the payment panel
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS `extra_services` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `price` decimal(10,2) NOT NULL,
                `description` text,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            
            $this->db->exec("CREATE TABLE IF NOT EXISTS `booking_extras` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `booking_id` int(11) NOT NULL,
                `extra_id` int(11) NOT NULL,
                `quantity` int(11) DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $check = $this->db->query("SELECT COUNT(*) FROM extra_services")->fetchColumn();
            if ($check == 0) {
                $this->db->exec("INSERT INTO extra_services (name, price, description) VALUES 
                    ('Organic Lunch', 25.00, 'Locally sourced organic lunch box.'),
                    ('Professional Guide', 50.00, 'Dedicated 1-on-1 professional guide.'),
                    ('Gear Rental', 30.00, 'Full set of premium outdoor gear.')");
            }
            
            $extrasStatement = $this->db->prepare("SELECT * FROM extra_services");
            $extrasStatement->execute();
            $extraServices = $extrasStatement->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $extraServices = []; // Graceful fallback
        }

        require_once __DIR__ . "/../../views/payment/checkout.php";
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

    public function joinWaitlist($loggedInUser) {
        $tripId = (int) ($_POST["trip_id"] ?? 0);
        if ($tripId <= 0) {
            header("Location: index.php?action=trips&error=" . urlencode("Invalid waitlist request."));
            exit;
        }
        
        $insertWaitlist = $this->db->prepare("INSERT INTO waitlists (user_id, trip_id, priority_status) VALUES (?, ?, 'normal')");
        $insertWaitlist->execute([$loggedInUser->id, $tripId]);
        header("Location: index.php?action=trip_detail&id=$tripId&success=" . urlencode("You have successfully joined the waitlist! We will notify you if a spot opens up."));
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

        $bookingModel = new Booking($this->db);
        $refundPercentage = $bookingModel->calculateRefund($bookingId);
        $refundAmount = $bookingRow["total_price"] * ($refundPercentage / 100);

        try {
            $this->db->beginTransaction();

            $cancelStatement = $this->db->prepare(
                "UPDATE bookings SET status = 'cancelled' WHERE id = ?"
            );
            $cancelStatement->execute([$bookingId]);

            // Update payment status to refunded if there is a refund
            if ($refundPercentage > 0) {
                $paymentStmt = $this->db->prepare("UPDATE payments SET payment_status = 'refunded' WHERE booking_id = ?");
                $paymentStmt->execute([$bookingId]);
            }

            // Automatic Waitlist Promotion
            $bookingModel->promoteWaitlist($bookingRow['trip_id']);

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            header("Location: index.php?action=my_bookings&error=" . urlencode("Failed to cancel booking due to a system error."));
            exit;
        }

        header("Location: index.php?action=my_bookings&success=" . urlencode("Booking cancelled. Expected Refund: " . $refundPercentage . "% ($" . number_format($refundAmount, 2) . ")"));
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
        $bookingModel = new Booking($this->db);
        
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
            
            // Calculate refund for UI
            $booking->refund_percentage = $bookingModel->calculateRefund($booking->id);
            $booking->refund_amount = $booking->total_price * ($booking->refund_percentage / 100);
            
            $bookings[] = $booking;
        }

        $errorMessage   = $_GET["error"]   ?? "";
        $successMessage = $_GET["success"] ?? "";

        require_once __DIR__ . "/../../views/bookings/my_bookings.php";
    }
}
