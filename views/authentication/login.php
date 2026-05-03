<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="auth-page">
    <div class="auth-card">
        <h1>🌿 Eco Tourism</h1>
        <h2>Log in</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login_submit">
            <label>Email</label>
            <input type="email" name="email" required autofocus>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Log in</button>
        </form>

        <p class="auth-link">No account? <a href="index.php?action=register">Register here</a></p>
    </div>
</body>

</html>