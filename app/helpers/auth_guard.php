<?php

function require_login()
{
    if (empty($_SESSION["user_id"])) {
        header("Location: index.php?action=login");
        exit;
    }
}


function require_guide_or_admin()
{
    require_login();
    global $loggedInUser;

    // Verify that $loggedInUser is a valid object before checking role
    if (!is_object($loggedInUser) || empty($loggedInUser->role)) {
        header("Location: index.php?action=dashboard&error=" . urlencode("Access denied."));
        exit;
    }

    $role = $loggedInUser->role;
    if ($role !== "guide" && $role !== "admin") {
        header("Location: index.php?action=dashboard&error=" . urlencode("Access denied."));
        exit;
    }
}


function require_admin()
{
    require_login();
    global $loggedInUser;

    // Ensure the user is an admin
    if (!is_object($loggedInUser) || $loggedInUser->role !== "admin") {
        header("Location: index.php?action=dashboard&error=" . urlencode("Access denied."));
        exit;
    }
}
