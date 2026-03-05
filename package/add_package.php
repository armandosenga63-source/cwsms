<?php
session_start();
include("../config/db.php");
if(!isset($_SESSION['email'])){ header("Location: ../auth/login.php"); exit(); }

if(isset($_POST['add_package'])){
    $name = $_POST['name'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO package (name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $name, $price);

    if($stmt->execute()){ header("Location: view_package.php"); exit(); }
    else { $error = "Failed to add package!"; }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Package</title><link rel="stylesheet" href="../style.css"></head>
<body>
<div class="card">
    <h2>Add Package</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Package Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <button name="add_package">Add Package</button>
    </form>
</div>
</body>
</html>
