<?php

class PaymentController
{
    private $db;
    private $paymentModel;

    public function __construct($databaseConnection)
    {
        $this->db = $databaseConnection;
        require_once __DIR__ . '/../models/Payment.php';
        $this->paymentModel = new Payment($this->db);
    }

    public function processCheckout($loggedInUser)
    {
        $bookingId = $_POST['booking_id'] ?? null;
        $numPeople = (int) ($_POST['num_people'] ?? 1);
        if ($numPeople < 1) $numPeople = 1;
        $currency = $_POST['currency'] ?? 'USD';

        $tripId = (int) ($_POST['trip_id'] ?? 0);

        // Fetch trip price to securely calculate the final price
        $tripStatement = $this->db->prepare("SELECT price FROM trips WHERE id = ? LIMIT 1");
        $tripStatement->execute([$tripId]);
        $tripRow = $tripStatement->fetch();

        if (!$tripRow) {
            header("Location: index.php?action=trips&error=" . urlencode("Trip not found."));
            exit;
        }

        // Apply Discount Logic
        $basePricePerPerson = $tripRow['price'];
        $discountPercent = 0;
        if ($numPeople >= 10) $discountPercent = 40;
        elseif ($numPeople >= 5) $discountPercent = 30;
        elseif ($numPeople >= 3) $discountPercent = 20;

        $subtotal = $basePricePerPerson * $numPeople;
        $discountAmount = $subtotal * ($discountPercent / 100);
        $finalBasePrice = $subtotal - $discountAmount;

        // Calculate Extras securely
        $extras = $_POST['extras'] ?? [];
        $extrasTotal = 0;
        $selectedExtrasData = [];
        if (!empty($extras) && is_array($extras)) {
            try {
                $placeholders = implode(',', array_fill(0, count($extras), '?'));
                $extrasStmt = $this->db->prepare("SELECT id, price FROM extra_services WHERE id IN ($placeholders)");
                $extrasStmt->execute($extras);
                $extrasRows = $extrasStmt->fetchAll();
                
                foreach ($extrasRows as $row) {
                    $extrasTotal += $row['price'];
                    $selectedExtrasData[] = $row['id'];
                }
            } catch (PDOException $e) {
                // Ignore if table doesn't exist or other error
            }
        }

        $grandTotal = $finalBasePrice + $extrasTotal;

        // Apply currency exchange rate
        $exchangeRates = [
            'USD' => 1,
            'EUR' => 0.85,
            'GBP' => 0.74,
            'EGP' => 52.72
        ];
        $rate = $exchangeRates[$currency] ?? 1;
        $secureAmount = round($grandTotal * $rate, 2);

        // Securely override any client-side amount
        $amount = $secureAmount;

        // Support seamless integration where form passes trip_id and booking_date directly.
        // We will create the booking first.
        if (empty($bookingId) && !empty($_POST['trip_id'])) {
            $tripId = (int) $_POST['trip_id'];
            $bookingDate = $_POST['booking_date'];

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
            
            $insertBooking = $this->db->prepare(
                "INSERT INTO bookings (user_id, trip_id, booking_date, status, total_price) 
                 VALUES (?, ?, ?, 'confirmed', ?)"
            );
            $insertBooking->execute([$loggedInUser->id, $tripId, $bookingDate, $amount]);
            $bookingId = $this->db->lastInsertId();

            // Save selected extras
            if (!empty($selectedExtrasData)) {
                try {
                    $insertExtra = $this->db->prepare("INSERT INTO booking_extras (booking_id, extra_id, quantity) VALUES (?, ?, 1)");
                    foreach ($selectedExtrasData as $extraId) {
                        $insertExtra->execute([$bookingId, $extraId]);
                    }
                } catch (PDOException $e) {
                    // Ignore if booking_extras table doesn't exist
                }
            }
        }

        if (!$bookingId || $amount <= 0) {
            header("Location: index.php?action=trips&error=" . urlencode("Invalid payment details."));
            exit;
        }

        // Generate a mock transaction ID for simulation
        $transactionId = 'TXN_' . strtoupper(uniqid());

        $data = [
            'booking_id' => $bookingId,
            'user_id' => $loggedInUser->id,
            'amount' => $amount,
            'currency' => $currency,
            'transaction_id' => $transactionId,
            'payment_method' => 'visa',
            'payment_status' => 'completed'
        ];

        // Save the payment record via the Model
        $this->paymentModel->createPayment($data);

        // Redirect to success page
        header("Location: index.php?action=my_bookings&success=" . urlencode("Payment successful! Your booking is confirmed."));
        exit;
    }
}
