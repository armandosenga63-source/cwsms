<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config/db.php';

/* ================================
   1. INITIALIZE VARIABLES
================================ */
$page_title = "Daily Activity Report";
$today = date('Y-m-d');
$new_cars = 0;
$revenue = 0;
$transactions = 0;
$activities = null;

/* ================================
   2. FETCH DATA FROM DATABASE
================================ */

// New Cars Registered Today
$car_res = $conn->query("
    SELECT COUNT(*) AS c 
    FROM cars 
    WHERE DATE(created_at) = '$today'
");

if ($car_res) {
    $row = $car_res->fetch_assoc();
    $new_cars = $row['c'];
} else {
    die("Car Query Error: " . $conn->error);
}


// Total Revenue Today
$rev_res = $conn->query("
    SELECT SUM(amount) AS total 
    FROM payments 
    WHERE DATE(payment_date) = '$today'
");

if ($rev_res) {
    $row = $rev_res->fetch_assoc();
    $revenue = $row['total'] ?? 0;
} else {
    die("Revenue Query Error: " . $conn->error);
}


// Services Completed Today
$trans_res = $conn->query("
    SELECT COUNT(*) AS c 
    FROM payments 
    WHERE DATE(payment_date) = '$today'
");

if ($trans_res) {
    $row = $trans_res->fetch_assoc();
    $transactions = $row['c'];
} else {
    die("Transaction Query Error: " . $conn->error);
}


/* ================================
   3. ACTIVITY TIMELINE
================================ */

$activities = $conn->query("
    (SELECT 
        'New Car' AS type,
        plate_number AS detail,
        created_at AS time
     FROM cars
     WHERE DATE(created_at) = '$today')

    UNION ALL

    (SELECT
        'Payment' AS type,
        CONCAT(amount,' RWF') AS detail,
        payment_date AS time
     FROM payments
     WHERE DATE(payment_date) = '$today')

    ORDER BY time DESC
");

if (!$activities) {
    die("Activity Query Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<?php include '../includes/header.php'; ?>

<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">

    <!-- Navbar -->
    <div class="glass-navbar mb-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>
                <?= $page_title ?>
            </h5>

            <div class="text-muted small">
                Date: <?= date('F d, Y') ?>
            </div>
        </div>
    </div>


    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h2 class="fw-bold mb-0"><?= $new_cars ?></h2>
                <small class="text-muted">New Cars Registered</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h2 class="fw-bold mb-0"><?= number_format($revenue) ?> RWF</h2>
                <small class="text-muted">Total Revenue Today</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-4 text-center">
                <h2 class="fw-bold mb-0"><?= $transactions ?></h2>
                <small class="text-muted">Services Completed</small>
            </div>
        </div>

    </div>


    <!-- Activity Timeline -->
    <div class="card p-4">

        <h5 class="fw-bold mb-4">Activity Timeline</h5>

        <div class="table-responsive">

            <table class="table table-bordered">

                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($activities instanceof mysqli_result && $activities->num_rows > 0): ?>

                    <?php while($row = $activities->fetch_assoc()): ?>

                        <tr>
                            <td><?= date('h:i A', strtotime($row['time'])) ?></td>

                            <td>
                                <?php if($row['type'] == 'New Car'): ?>
                                    <span class="badge bg-primary"><?= $row['type'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?= $row['type'] ?></span>
                                <?php endif; ?>
                            </td>

                            <td><?= $row['detail'] ?></td>

                            <td>
                                <span class="badge bg-success">Completed</span>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No activities recorded today.
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