<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/db.php';

$page_title = "Daily Reports";

$today = date('Y-m-d');

$total_cars = 0;
$total_payments = 0;
$total_revenue = 0;

/* =========================
   DAILY STATISTICS
========================= */

$car_query = $conn->query("
    SELECT COUNT(*) AS total
    FROM cars
    WHERE DATE(created_at) = '$today'
");

if($car_query){
    $total_cars = $car_query->fetch_assoc()['total'];
}

$payment_query = $conn->query("
    SELECT COUNT(*) AS total
    FROM payments
    WHERE DATE(payment_date) = '$today'
");

if($payment_query){
    $total_payments = $payment_query->fetch_assoc()['total'];
}

$revenue_query = $conn->query("
    SELECT SUM(amount) AS total
    FROM payments
    WHERE DATE(payment_date) = '$today'
");

if($revenue_query){
    $total_revenue = $revenue_query->fetch_assoc()['total'] ?? 0;
}

/* =========================
   DETAILED REPORT
========================= */

$reports = $conn->query("
    SELECT 
        cars.plate_number,
        cars.owner_name,
        services.service_name,
        payments.amount,
        payments.payment_method,
        payments.payment_date

    FROM payments

    JOIN cars ON payments.car_id = cars.id
    JOIN services ON payments.service_id = services.id

    WHERE DATE(payments.payment_date) = '$today'

    ORDER BY payments.payment_date DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<?php include '../includes/header.php'; ?>

<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">

<!-- PAGE HEADER -->

<div class="glass-navbar mb-4">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-bar-chart-line"></i>
            <?= $page_title ?>
        </h5>

        <span class="text-muted">
            Date: <?= date('F d, Y') ?>
        </span>
    </div>
</div>


<!-- SUMMARY CARDS -->

<div class="row g-4 mb-4">

<div class="col-md-4">
<div class="card p-4 text-center">
<h2 class="fw-bold"><?= $total_cars ?></h2>
<small class="text-muted">Cars Registered Today</small>
</div>
</div>

<div class="col-md-4">
<div class="card p-4 text-center">
<h2 class="fw-bold"><?= $total_payments ?></h2>
<small class="text-muted">Services Completed</small>
</div>
</div>

<div class="col-md-4">
<div class="card p-4 text-center">
<h2 class="fw-bold"><?= number_format($total_revenue) ?> RWF</h2>
<small class="text-muted">Total Revenue Today</small>
</div>
</div>

</div>


<!-- DAILY REPORT TABLE -->

<div class="card p-4">

<h5 class="fw-bold mb-3">Daily Service Report</h5>

<div class="table-responsive">

<table class="table table-striped">

<thead class="table-light">
<tr>
<th>#</th>
<th>Plate Number</th>
<th>Owner</th>
<th>Service</th>
<th>Amount</th>
<th>Payment Method</th>
<th>Time</th>
</tr>
</thead>

<tbody>

<?php if($reports && $reports->num_rows > 0): ?>

<?php $count = 1; ?>

<?php while($row = $reports->fetch_assoc()): ?>

<tr>
<td><?= $count++ ?></td>
<td><?= $row['plate_number'] ?></td>
<td><?= $row['owner_name'] ?></td>
<td><?= $row['service_name'] ?></td>
<td><?= number_format($row['amount']) ?> RWF</td>
<td><?= $row['payment_method'] ?></td>
<td><?= date('h:i A', strtotime($row['payment_date'])) ?></td>
</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>
<td colspan="7" class="text-center text-muted">
No reports recorded today
</td>
</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>