<?php
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
    
}
