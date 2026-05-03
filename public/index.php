<?php

// ── Load Models 
require_once __DIR__ . "/../app/models/User.php";
require_once __DIR__ . "/../app/models/Guide.php";
require_once __DIR__ . "/../app/models/Trip.php";
require_once __DIR__ . "/../app/models/Booking.php";
require_once __DIR__ . "/../app/models/Review.php";

session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/helpers/auth_guard.php";

// ── Global User Handling
$loggedInUser = null;

if (!empty($_SESSION["user_id"])) {
    $stmt = $databaseConnection->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$_SESSION["user_id"]]);
    $userRow = $stmt->fetch();

    if ($userRow) {
        $loggedInUser = new User();
        $loggedInUser->id         = $userRow["id"];
        $loggedInUser->name       = $userRow["name"];
        $loggedInUser->email      = $userRow["email"];
        $loggedInUser->role       = $userRow["role"];
        $loggedInUser->created_at = $userRow["created_at"];
    } else {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}

// ── Load Controllers
require_once __DIR__ . "/../app/controllers/AuthenticationController.php";
require_once __DIR__ . "/../app/controllers/TripController.php";
require_once __DIR__ . "/../app/controllers/BookingController.php";
require_once __DIR__ . "/../app/controllers/ReviewController.php";

require_once __DIR__ . "/../routes/web.php";


    // ── Error Handling 
    // Set to 'development' or 'production'
    define('APP_ENV', 'development'); 
        
        if (APP_ENV === 'development') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }
