<?php

function require_login()
{
    // Check for user_id instead of full object
    if (empty($_SESSION["user_id"])) {
        header("Location: index.php?action=login");
        exit;
    }
}


function require_guide_or_admin()
{
    require_login();
    global $loggedInUser;

    $role = $loggedInUser->role ?? "";
    if ($role !== "guide" && $role !== "admin") {
        header("Location: index.php?action=dashboard&error=" . urlencode("Access denied."));
        exit;
    }
}


function require_admin()
{
    require_login();
    global $loggedInUser;

    if (($loggedInUser->role ?? "") !== "admin") {
        header("Location: index.php?action=dashboard&error=" . urlencode("Access denied."));
        exit;
    }
}
