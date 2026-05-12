<?php
require_once __DIR__ . '/../helpers/date_helper.php';

class Booking
{
    public $id;
    public $user_id;
    public $trip_id;
    public $booking_date;
    public $status;         // 'pending' | 'confirmed' | 'cancelled'
    public $total_price;
    public $created_at;
    public $trip_title;    // For booking list view
    public $trip_location; // For booking list view
    
    // UI fields
    public $refund_percentage;
    public $refund_amount;

    private $db;

    public function __construct($db = null) {
        $this->db = $db;
    }

    public function calculateRefund($booking_id) {
        if (!$this->db) return 0;
        
        $stmt = $this->db->prepare("SELECT booking_date FROM bookings WHERE id = ?");
        $stmt->execute([$booking_id]);
        $row = $stmt->fetch();
        if (!$row) return 0;

        $daysDiff = calculate_days_difference($row['booking_date']);

        if ($daysDiff >= 7) {
            return 100;
        } elseif ($daysDiff >= 2) {
            return 50;
        } else {
            return 0; // Less than 48 hours
        }
    }

    public function promoteWaitlist($trip_id) {
        if (!$this->db) return false;
        
        // Find next person on waitlist (could order by priority_status DESC, then created_at ASC)
        $stmt = $this->db->prepare("SELECT id, user_id FROM waitlists WHERE trip_id = ? AND priority_status != 'promoted' ORDER BY created_at ASC LIMIT 1");
        $stmt->execute([$trip_id]);
        $waitlistEntry = $stmt->fetch();
        
        if ($waitlistEntry) {
            $update = $this->db->prepare("UPDATE waitlists SET priority_status = 'promoted' WHERE id = ?");
            $update->execute([$waitlistEntry['id']]);
            
            $this->sendWaitlistNotification($waitlistEntry['user_id']);
            return true;
        }
        return false;
    }

    public function sendWaitlistNotification($user_id) {
        // Placeholder for sending SMS or Email
        error_log("Notification to User ID $user_id: A spot has opened up! You have 24 hours to confirm your booking.");
    }
}
