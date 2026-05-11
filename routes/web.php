<?php

//Eager Loading memory_overhead Lazy Loading new new new 
//DRY (Don't Repeat Yourself) 
//Route Grouping

//Current Action

$action = $_GET['action'] ?? 'login';



$authenticationController = null;
$tripController = null;
$bookingController = null;
$reviewController = null;
$guideController = null;

$authActions = [
    'login',
    'login_submit',
    'register',
    'register_submit',
    'logout',
    'dashboard',
    'admin_dashboard'
];

$tripActions = [
    'trips',
    'trip_detail',
    'trip_create',
    'trip_create_submit',
    'carbon_offset',
    'eco_score',
    'indigenous_consent_check',
    'leave_no_trace',
    'sustainability_report',
    'sustainability_report_global',
    'guide_trips',
    'optimized_route'
];

$bookingActions = [
    'booking_create',
    'booking_cancel',
    'my_bookings'
];

$guideActions = [
    'guide_panel',
    'certificate_submit',
    'language_verification_submit',
    'trainee_shadow_approve',
    'guide_profile',
    'field_report',
    'field_report_submit'
];

$reviewActions = [
    'review_submit'
];


if (in_array($action, $authActions)) {
    $authenticationController = new AuthenticationController($databaseConnection);
} elseif (in_array($action, $tripActions)) {
    $tripController = new TripController($databaseConnection);
} elseif (in_array($action, $bookingActions)) {
    $bookingController = new BookingController($databaseConnection);
} elseif (in_array($action, $guideActions)) {
    $guideController = new GuideController($databaseConnection);
} elseif (in_array($action, $reviewActions)) {
    $reviewController = new ReviewController($databaseConnection);
}


switch ($action) {

    // Authentication

    case 'login':

        $authenticationController->showLoginForm();

        break;

    case 'login_submit':

        $authenticationController->handleLogin();

        break;

    case 'register':

        $authenticationController->showRegisterForm();

        break;

    case 'register_submit':

        $authenticationController->handleRegister();

        break;

    case 'logout':

        $authenticationController->handleLogout();

        break;


    //Dashboard

    case 'dashboard':

        require_login();

        $authenticationController->showDashboard($loggedInUser);

        break;

    case 'admin_dashboard':

        require_admin();

        $authenticationController->showAdminDashboard($loggedInUser);

        break;

    // Trips

    case 'trips':

        require_login();

        $tripController->showTripList($loggedInUser);

        break;

    case 'trip_detail':

        require_login();

        $tripController->showTripDetail($loggedInUser);

        break;

    case 'trip_create':

        require_guide_or_admin();

        $tripController->showCreateForm($loggedInUser);

        break;

    case 'trip_create_submit':

        require_guide_or_admin();

        $tripController->handleCreate($loggedInUser);

        break;

    case 'carbon_offset':

        require_login();

        $tripController->showCarbonOffset($loggedInUser);

        break;

    case 'eco_score':

        require_login();

        $tripController->showEcoScore($loggedInUser);

        break;

    case 'indigenous_consent_check':

        require_login();

        $tripController->checkConsent($loggedInUser);

        break;

    case 'leave_no_trace':

        require_login();

        $tripController->downloadLeaveNoTrace($loggedInUser);

        break;

    case 'sustainability_report':
    case 'sustainability_report_global':

        require_login();

        $tripController->showGlobalSustainabilityReport($loggedInUser);

        break;

    case 'guide_trips':

        require_guide_or_admin();

        $tripController->showGuideTrips($loggedInUser);

        break;

    case 'optimized_route':

        require_login();

        $tripController->showOptimizedRoute($loggedInUser);

        break;

    // Bookings

    case 'booking_create':

        require_login();

        $bookingController->handleCreate($loggedInUser);

        break;

    case 'booking_cancel':

        require_login();

        $bookingController->handleCancel($loggedInUser);

        break;

    case 'my_bookings':

        require_login();

        $bookingController->showMyBookings($loggedInUser);

        break;

    // Reviews


    case 'review_submit':

        require_login();

        $reviewController->handleSubmit($loggedInUser);

        break;


    // Guide

    case 'guide_panel':

        require_guide_or_admin();

        $guideController->showGuidePanel($loggedInUser);

        break;


    case 'certificate_submit':

        require_guide_or_admin();

        $guideController->upload_or_renew_certificate($loggedInUser);

        break;

    case 'language_verification_submit':

        require_guide_or_admin();

        $guideController->handleLanguageVerification($loggedInUser);

        break;

    case 'trainee_shadow_approve':

        require_guide_or_admin();

        $guideController->handleTraineeShadowing($loggedInUser);

        break;

    case "guide_profile":
        require_guide_or_admin();
        $guideController->showGuideProfile($loggedInUser);
        break;

    case "field_report":
        require_guide_or_admin();
        $guideController->showFieldReportForm($loggedInUser);
        break;

    case 'field_report_submit':

        require_guide_or_admin();

        $guideController->handleFieldReport($loggedInUser);

        break;

    // Default Route    

    default:

        header('Location: index.php?action=login');

        exit;
}
