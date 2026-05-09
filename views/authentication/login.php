<?php
$pageTitle = 'Login — Eco Tourism';
require_once __DIR__ . "/../../views/partials/header.php";
?>

<div class="auth-page">
    <div class="auth-card animate-slide-up">
        <div class="auth-header">
            <h1>🌿 Eco Tourism</h1>
            <h2>Welcome Back</h2>
            <p class="auth-subtitle">Log in to explore sustainable adventures.</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login_submit" class="auth-form">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required autofocus placeholder="name@example.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Log In</button>
        </form>

        <p class="auth-footer">Don't have an account? <a href="index.php?action=register">Create one here</a></p>
    </div>
</div>

<?php require_once __DIR__ . "/../../views/partials/footer.php"; ?>