<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
require_once '../config/db.php';

$id = intval($_GET['id']);
$car = $conn->query("SELECT * FROM cars WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE cars SET plate_number=?, owner_name=?, phone=?, car_model=?, car_color=? WHERE id=?");
    $stmt->bind_param("sssssi", $_POST['plate_number'], $_POST['owner_name'], $_POST['phone'], $_POST['car_model'], $_POST['car_color'], $id);
    $stmt->execute();
    header("Location: view_car.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car - CWSMS</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="navbar">
    <a href="../dashboard.php" class="brand">🚗 CWSMS</a>
    <div>
        <a href="../dashboard.php">Dashboard</a>
        <a href="view_car.php">Cars</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>
<div class="container">
    <div class="card">
        <h2>Edit Car</h2>
        <form method="POST">
            <div class="form-group"><label>Plate Number</label><input type="text" name="plate_number" value="<?= $car['plate_number'] ?>" required></div>
            <div class="form-group"><label>Owner Name</label><input type="text" name="owner_name" value="<?= $car['owner_name'] ?>" required></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?= $car['phone'] ?>"></div>
            <div class="form-group"><label>Car Model</label><input type="text" name="car_model" value="<?= $car['car_model'] ?>"></div>
            <div class="form-group"><label>Car Color</label><input type="text" name="car_color" value="<?= $car['car_color'] ?>"></div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="view_car.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
