<?php
session_start();
require_once '../config/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // 1. Security: Create a new session ID
            session_regenerate_id(true); 
            
            // 2. Set Session Variables
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['logged_in'] = true;

            // 3. FORCE SAVE: Crucial for XAMPP stability
            session_write_close(); 

            // 4. Use Absolute Path for Redirect
            header("Location: /CWSMS_Project/dashboard.php");
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CWSMS Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body class="auth-body">

<div class="glass-card">
    <div class="auth-logo">CWS</div>
    <h2>Welcome Back</h2>
    <p>Sign in to manage your wash operations</p>

    <?php if (!empty($error)): ?>
        <div style="background: rgba(255,0,0,0.1); color: #b91c1c; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Enter username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-signin">Sign In</button>
    </form>

    <div style="margin-top: 2rem; font-size: 0.9rem; color: #64748b;">
        Need an account? <a href="register.php" style="color: var(--primary-blue); font-weight: 600; text-decoration: none;">Create one</a>
    </div>
</div>

</body>
</html>