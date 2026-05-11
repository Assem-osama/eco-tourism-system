<?php


define('APP_ENV', 'development');


// Error Handling

if (APP_ENV === 'development') {

    ini_set('display_errors', 1);

    ini_set('display_startup_errors', 1);

    error_reporting(E_ALL);
} else {

    ini_set('display_errors', 0);

    error_reporting(0);
}


if (session_status() === PHP_SESSION_NONE) {

    session_start();
}


//Load Models

require_once __DIR__ . '/../app/models/User.php';

require_once __DIR__ . '/../app/models/Guide.php';

require_once __DIR__ . '/../app/models/Trip.php';

require_once __DIR__ . '/../app/models/Booking.php';

require_once __DIR__ . '/../app/models/Review.php';


// Database Connection


require_once __DIR__ . '/../config/database.php';


// Helpers


require_once __DIR__ . '/../app/helpers/auth_guard.php';


// Global Logged-In User


$loggedInUser = null;

if (!empty($_SESSION['user_id'])) {

    $userStatement = $databaseConnection->prepare(
        "
        SELECT *
        FROM users
        WHERE id = ?
        LIMIT 1
        "
    );

    $userStatement->execute([
        $_SESSION['user_id']
    ]);

    $userRow = $userStatement->fetch(PDO::FETCH_ASSOC);


    if ($userRow) {

        $loggedInUser = new User();

        $loggedInUser->id = $userRow['id'] ?? null;

        $loggedInUser->name = $userRow['name'] ?? '';

        $loggedInUser->email = $userRow['email'] ?? '';

        $loggedInUser->role = $userRow['role'] ?? 'traveler';

        $loggedInUser->created_at = $userRow['created_at'] ?? null;
    } else {


        $_SESSION = [];

        session_destroy();

        header('Location: index.php?action=login');

        exit;
    }
}

//Load Controllers

require_once __DIR__ . '/../app/controllers/AuthenticationController.php';

require_once __DIR__ . '/../app/controllers/TripController.php';

require_once __DIR__ . '/../app/controllers/BookingController.php';

require_once __DIR__ . '/../app/controllers/ReviewController.php';

require_once __DIR__ . '/../app/controllers/GuideController.php';

// Load Routes

require_once __DIR__ . '/../routes/web.php';
