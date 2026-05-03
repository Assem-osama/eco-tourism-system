<?php

$action = $_GET["action"] ?? "login";

$authController    = new AuthenticationController($databaseConnection);
$tripController    = new TripController($databaseConnection);
$bookingController = new BookingController($databaseConnection);
$reviewController  = new ReviewController($databaseConnection);

switch ($action) {

    // ── Authentication ─────────────────────────────────────
    case "login":
        $authController->showLoginForm();
        break;

    case "login_submit":
        $authController->handleLogin();
        break;

    case "register":
        $authController->showRegisterForm();
        break;

    case "register_submit":
        $authController->handleRegister();
        break;

    case "logout":
        $authController->handleLogout();
        break;

    // ── Dashboard ──────────────────────────────────────────
    case "dashboard":
        require_login();
        $authController->showDashboard($loggedInUser);
        break;

    // ── Trips ──────────────────────────────────────────────
    case "trips":
        require_login();
        $tripController->showTripList($loggedInUser);
        break;

    case "trip_detail":
        require_login();
        $tripController->showTripDetail($loggedInUser);
        break;

    case "trip_create":
        require_guide_or_admin();
        $tripController->showCreateForm($loggedInUser);
        break;

    case "trip_create_submit":
        require_guide_or_admin();
        $tripController->handleCreate($loggedInUser);
        break;

    // ── Bookings ───────────────────────────────────────────
    case "booking_create":
        require_login();
        $bookingController->handleCreate($loggedInUser);
        break;

    case "booking_cancel":
        require_login();
        $bookingController->handleCancel($loggedInUser);
        break;

    case "my_bookings":
        require_login();
        $bookingController->showMyBookings($loggedInUser);
        break;

    // ── Reviews ────────────────────────────────────────────
    case "review_submit":
        require_login();
        $reviewController->handleSubmit($loggedInUser);
        break;

    // ── Catch-all ──────────────────────────────────────────
    default:
        header("Location: index.php?action=login");
        exit;
}
