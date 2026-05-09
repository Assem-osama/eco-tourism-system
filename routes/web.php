<?php

/*
|--------------------------------------------------------------------------
| Create Controller Instances
|--------------------------------------------------------------------------
*/


$authenticationController = new AuthenticationController($databaseConnection);
$tripController = new TripController($databaseConnection);


$bookingController = new BookingController($databaseConnection);

$reviewController = new ReviewController($databaseConnection);

$guideController = new GuideController($databaseConnection);

/*
|--------------------------------------------------------------------------
| Current Action
|--------------------------------------------------------------------------
*/

$action = $_GET['action'] ?? 'login';

/*
|--------------------------------------------------------------------------
| Route Dispatcher
|--------------------------------------------------------------------------
*/

switch ($action) {

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    case 'dashboard':

        require_login();

        $authenticationController->showDashboard($loggedInUser);

        break;

    case 'admin_dashboard':

        require_admin();

        $authenticationController->showAdminDashboard($loggedInUser);

        break;

    /*
    |--------------------------------------------------------------------------
    | Trips
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Bookings
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */

    case 'review_submit':

        require_login();

        $reviewController->handleSubmit($loggedInUser);

        break;

    /*
    |--------------------------------------------------------------------------
    | Guide
    |--------------------------------------------------------------------------
    */

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



    /*
    |--------------------------------------------------------------------------
    | Default Route
    |--------------------------------------------------------------------------
    */

    default:

        header('Location: index.php?action=login');

        exit;
}
