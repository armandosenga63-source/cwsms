<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
require_once '../config/db.php';
$conn->query("DELETE FROM cars WHERE id=" . intval($_GET['id']));
header("Location: view_car.php");
exit;
?>
