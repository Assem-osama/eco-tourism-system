<?php

class AuthenticationController {

    private $db;

    public function __construct($databaseConnection) {
        $this->db = $databaseConnection;
    }

    public function showLoginForm() {
        if (!empty($_SESSION["user_id"])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $errorMessage = $_GET["error"] ?? "";
        $successMessage = $_GET["success"] ?? "";
        require_once __DIR__ . "/../../views/authentication/login.php";
    }

    public function showRegisterForm() {
        if (!empty($_SESSION["user_id"])) {
            header("Location: index.php?action=dashboard");
            exit;
        }
        $errorMessage = $_GET["error"] ?? "";
        require_once __DIR__ . "/../../views/authentication/register.php";
    }

    public function showDashboard($loggedInUser) {
        if ($loggedInUser->role === "admin") {
            header("Location: index.php?action=admin_dashboard");
            exit;
        } elseif ($loggedInUser->role === "guide") {
            header("Location: index.php?action=guide_panel");
            exit;
        }
        require_once __DIR__ . "/../../views/dashboard/index.php";
    }

    public function showAdminDashboard($loggedInUser) {
        // Fetch some admin stats
        $userCount = $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $guideCount = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'guide'")->fetchColumn();
        $tripCount = $this->db->query("SELECT COUNT(*) FROM trips WHERE status = 'approved'")->fetchColumn();
        $bookingCount = $this->db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
        
        require_once __DIR__ . "/../../views/admin/dashboard.php";
    }

    public function showAdminLogs($loggedInUser) {
        $statement = $this->db->query(
            "SELECT audit_logs.*, users.name AS user_name 
             FROM audit_logs 
             LEFT JOIN users ON audit_logs.user_id = users.id 
             ORDER BY created_at DESC 
             LIMIT 100"
        );
        $logs = $statement->fetchAll();
        
        require_once __DIR__ . "/../../views/admin/logs.php";
    }

    public function handleLogin() {
        $email    = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";

        if (empty($email) || empty($password)) {
            $this->redirectWithError("login", "Please fill in all fields.");
            return;
        }

        $statement = $this->db->prepare(
            "SELECT * FROM users WHERE email = ? LIMIT 1"
        );
        $statement->execute([$email]);
        $row = $statement->fetch();

        if (!$row || !password_verify($password, $row["password"])) {
            $this->redirectWithError("login", "Invalid email or password.");
            return;
        }

        if ($row['account_status'] === 'blacklisted') {
            $this->redirectWithError("login", "Your account has been suspended due to repeated violations of our sustainability standards.");
            return;
        }

        session_regenerate_id(true); // prevent session fixation
        
        // Store ONLY the user ID in the session
        $_SESSION["user_id"] = $row["id"];

        // Redirect based on role
        if ($row["role"] === "admin") {
            header("Location: index.php?action=admin_dashboard");
        } elseif ($row["role"] === "guide") {
            header("Location: index.php?action=guide_panel");
        } else {
            header("Location: index.php?action=dashboard");
        }
        exit;
    }

    public function handleRegister() {
        $name            = trim($_POST["name"] ?? "");
        $email           = trim($_POST["email"] ?? "");
        $password        = $_POST["password"] ?? "";
        $confirmPassword = $_POST["confirm_password"] ?? "";
        $role            = $_POST["role"] ?? "tourist";

        // Validate role
        $allowedRoles = ["tourist", "guide", "admin"];
        if (!in_array($role, $allowedRoles)) {
            $role = "tourist";
        }

        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            $this->redirectWithError("register", "Please fill in all fields.");
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithError("register", "Please enter a valid email address.");
            return;
        }

        if (strlen($password) < 8) {
            $this->redirectWithError("register", "Password must be at least 8 characters.");
            return;
        }

        if ($password !== $confirmPassword) {
            $this->redirectWithError("register", "Passwords do not match.");
            return;
        }

        // Check if email is already taken
        $statement = $this->db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $statement->execute([$email]);
        if ($statement->fetch()) {
            $this->redirectWithError("register", "An account with that email already exists.");
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $statement = $this->db->prepare(
            "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
        );
        $statement->execute([$name, $email, $hashedPassword, $role]);

        // If guide, we might need to create a guide record as well
        if ($role === "guide") {
            $newUserId = $this->db->lastInsertId();
            $guideStmt = $this->db->prepare("INSERT INTO guides (user_id, bio, sustainability_score) VALUES (?, '', 0)");
            $guideStmt->execute([$newUserId]);
        }

        header("Location: index.php?action=login&success=" . urlencode("Account created! Please log in."));
        exit;
    }

    public function handleLogout() {
        // Clear all session data
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $cookieParams = session_get_cookie_params();
            setcookie(
                session_name(), "",
                time() - 42000,
                $cookieParams["path"],
                $cookieParams["domain"],
                $cookieParams["secure"],
                $cookieParams["httponly"]
            );
        }

        session_destroy();

        header("Location: index.php?action=login");
        exit;
    }

    // ── Private helpers ────────────────────────────────────

    private function redirectWithError($action, $message) {
        header("Location: index.php?action=$action&error=" . urlencode($message));
        exit;
    }
}