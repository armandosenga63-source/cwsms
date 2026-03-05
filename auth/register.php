<?php
session_start();
require_once '../config/db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Username is already taken.";
        } else {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'staff')");
            $insert->bind_param("ss", $username, $hashed_password);
            
            if ($insert->execute()) {
                $success = "Account created! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CWSMS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/CWSMS_Project/style.css">
</head>
<body class="auth-body">

<div class="glass-card">
    <div class="auth-logo">CWS</div>
    <h2>Create Account</h2>
    <p>Join the team and start managing operations</p>

    <?php if (!empty($error)): ?>
        <div style="background: rgba(185, 28, 28, 0.1); color: #b91c1c; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem; border: 1px solid rgba(185, 28, 28, 0.2);">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="background: rgba(21, 128, 61, 0.1); color: #15803d; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem; border: 1px solid rgba(21, 128, 61, 0.2);">
            <?= $success ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Choose a unique username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-signin">Register Account</button>
    </form>

    <div style="margin-top: 2rem; font-size: 0.9rem; color: #64748b;">
        Already have an account? <a href="login.php" style="color: #2563eb; font-weight: 600; text-decoration: none;">Sign In</a>
    </div>
</div>

</body>
</html>