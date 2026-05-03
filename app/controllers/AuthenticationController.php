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
        require_once __DIR__ . "/../../views/dashboard/index.php";
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

        session_regenerate_id(true); // prevent session fixation
        
        // Store ONLY the user ID in the session
        $_SESSION["user_id"] = $row["id"];

        header("Location: index.php?action=dashboard");
        exit;
    }

    public function handleRegister() {
        $name            = trim($_POST["name"] ?? "");
        $email           = trim($_POST["email"] ?? "");
        $password        = $_POST["password"] ?? "";
        $confirmPassword = $_POST["confirm_password"] ?? "";

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
            "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'tourist')"
        );
        $statement->execute([$name, $email, $hashedPassword]);

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