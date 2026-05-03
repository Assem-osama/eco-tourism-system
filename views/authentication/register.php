<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register — Eco Tourism</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="auth-page">
    <div class="auth-card">
        <h1>🌿 Eco Tourism</h1>
        <h2>Create an account</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=register_submit">
            <label>Full name</label>
            <input type="text" name="name" required autofocus>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password <small>(min. 8 characters)</small></label>
            <input type="password" name="password" required>

            <label>Confirm password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Create account</button>
        </form>

        <p class="auth-link">Already registered? <a href="index.php?action=login">Log in</a></p>
    </div>
</body>