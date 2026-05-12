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
$paymentController = null;

$authActions = [
    'login',
    'login_submit',
    'register',
    'register_submit',
    'logout',
    'dashboard',
    'admin_dashboard',
    'admin_logs'
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
    'optimized_route',
    'admin_trips',
    'admin_trip_approve',
    'admin_trip_reject',
    'trip_edit',
    'trip_edit_submit'
];

$bookingActions = [
    'booking_create',
    'booking_cancel',
    'my_bookings',
    'checkout',
    'waitlist_join'
];

$paymentActions = [
    'process_checkout'
];

$guideActions = [
    'guide_panel',
    'certificate_submit',
    'language_verification_submit',
    'trainee_shadow_approve',
    'guide_profile',
    'field_report',
    'field_report_submit',
    'admin_issue_strike',
    'admin_reset_strikes',
    'admin_guides_vetting',
    'admin_guide_approve'
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
} elseif (in_array($action, $paymentActions)) {
    $paymentController = new PaymentController($databaseConnection);
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
        
    case 'admin_logs':
        
        require_admin();
        
        $authenticationController->showAdminLogs($loggedInUser);
        
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

    case 'trip_edit':

        require_guide_or_admin();

        $tripController->showEditForm($loggedInUser);

        break;

    case 'trip_edit_submit':

        require_guide_or_admin();

        $tripController->handleEdit($loggedInUser);

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

    // Admin Vetting

    case 'admin_trips':
        require_admin();
        $tripController->showAdminTrips($loggedInUser);
        break;

    case 'admin_trip_approve':
        require_admin();
        $tripController->approveTrip($loggedInUser);
        break;

    case 'admin_trip_reject':
        require_admin();
        $tripController->rejectTrip($loggedInUser);
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

    case 'checkout':

        require_login();

        $bookingController->showCheckout($loggedInUser);

        break;

    case 'process_checkout':

        require_login();

        $paymentController->processCheckout($loggedInUser);

        break;

    case 'waitlist_join':

        require_login();

        $bookingController->joinWaitlist($loggedInUser);

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

    case 'admin_issue_strike':
        require_admin();
        $guideController->issueStrike($loggedInUser);
        break;

    case 'admin_reset_strikes':
        require_admin();
        $guideController->resetStrikes($loggedInUser);
        break;

    case 'admin_guides_vetting':
        require_admin();
        $guideController->showAdminGuidesVetting($loggedInUser);
        break;

    case 'admin_guide_approve':
        require_admin();
        $guideController->approveGuide($loggedInUser);
        break;

    // Default Route    

    default:

        header('Location: index.php?action=login');

        exit;
}
