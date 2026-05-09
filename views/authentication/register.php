<?php
$pageTitle = 'Create Account — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
?>

<div class="auth-page">
    <div class="auth-card animate-slide-up">
        <div class="auth-header">
            <h1>🌿 Eco Tourism</h1>
            <h2>Join the Movement</h2>
            <p class="auth-subtitle">Create an account to explore or lead sustainable travels.</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register_submit" class="auth-form">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required autofocus placeholder="John Doe">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@example.com">
            </div>

            <div class="form-group">
                <label>I want to be a...</label>
                <select name="role" required class="form-select">
                    <option value="tourist">Tourist (I want to book trips)</option>
                    <option value="guide">Guide (I want to lead trips)</option>
                    <option value="admin">Admin (I want to manage the portal)</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required placeholder="••••••••">
                </div>
            </div>

            <p class="form-hint">Password must be at least 8 characters long.</p>

            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>

        <p class="auth-footer">Already have an account? <a href="index.php?action=login">Log in here</a></p>
    </div>
</div>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>