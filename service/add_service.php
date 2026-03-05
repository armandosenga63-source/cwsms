<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO services (service_name, description, price) VALUES (?,?,?)");
    $stmt->bind_param("ssd", $_POST['service_name'], $_POST['description'], $_POST['price']);
    $stmt->execute();
    header("Location: view_service.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service - CWSMS</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="navbar">
    <a href="../dashboard.php" class="brand">🚗 CWSMS</a>
    <div>
        <a href="../dashboard.php">Dashboard</a>
        <a href="view_service.php">Services</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>
<div class="container">
    <div class="card">
        <h2>Add Service</h2>
        <form method="POST">
            <div class="form-group"><label>Service Name</label><input type="text" name="service_name" required></div>
            <div class="form-group"><label>Description</label><textarea name="description"></textarea></div>
            <div class="form-group"><label>Price (RWF)</label><input type="number" name="price" step="0.01" required></div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="view_service.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
