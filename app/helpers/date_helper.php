<?php

/**
 * Calculates the number of days between today and a target date.
 * If the target date is in the past, it returns a negative number or zero depending on logic.
 * For refunds, we want the number of days UNTIL the trip.
 * 
 * @param string $targetDate (YYYY-MM-DD)
 * @return int Number of days until the target date.
 */
function calculate_days_difference($targetDate) {
    if (empty($targetDate)) {
        return 0;
    }
    
    $today = new DateTime('now', new DateTimeZone('UTC'));
    $today->setTime(0, 0, 0);
    
    try {
        $target = new DateTime($targetDate, new DateTimeZone('UTC'));
        $target->setTime(0, 0, 0);
        
        $interval = $today->diff($target);
        
        $days = (int) $interval->format('%r%a'); // %r gives '-' if negative, %a gives total days
        
        return $days;
    } catch (Exception $e) {
        return 0;
    }
}
