<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit; }
require_once 'config/db.php';

// Fetch Statistics
$cars     = $conn->query("SELECT COUNT(*) as c FROM cars")->fetch_assoc()['c'];
$services = $conn->query("SELECT COUNT(*) as c FROM services")->fetch_assoc()['c'];
$payments = $conn->query("SELECT COUNT(*) as c FROM payments")->fetch_assoc()['c'];
$today    = $conn->query("SELECT COALESCE(SUM(amount),0) as t FROM payments WHERE DATE(payment_date)=CURDATE()")->fetch_assoc()['t'];

$recent = $conn->query("
    SELECT cars.plate_number, services.service_name, payments.amount, payments.payment_date
    FROM payments
    JOIN cars ON payments.car_id = cars.id
    JOIN services ON payments.service_id = services.id
    ORDER BY payments.payment_date DESC LIMIT 5
");

$page_title = "System Overview";
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.php'; ?>
<body>

    <?php include 'includes/sidebar.php'; ?>

    <div class="main">
        <nav class="glass-navbar">
            <h5 class="fw-bold mb-0"><?= $page_title ?></h5>
            <div class="nav-right d-flex align-items-center gap-3">
                <input type="text" placeholder="Search..." class="form-control form-control-sm border-0 bg-light px-3" style="border-radius:10px; width:250px;">
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle px-3" data-bs-toggle="dropdown" style="border-radius:8px;">
                        <i class="bi bi-person-circle me-1"></i> <?= $_SESSION['username'] ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li><a class="dropdown-item" href="auth/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small class="text-muted">Total Cars</small><h3 class="fw-bold mb-0"><?= $cars ?></h3></div>
                        <div class="text-primary fs-2"><i class="bi bi-car-front"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small class="text-muted">Services</small><h3 class="fw-bold mb-0"><?= $services ?></h3></div>
                        <div class="text-info fs-2"><i class="bi bi-droplet"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small class="text-muted">Transactions</small><h3 class="fw-bold mb-0"><?= $payments ?></h3></div>
                        <div class="text-success fs-2"><i class="bi bi-cash-stack"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small class="text-white-50">Today's Revenue</small><h3 class="fw-bold mb-0"><?= number_format($today) ?></h3></div>
                        <div class="fs-2 text-white-50"><i class="bi bi-graph-up"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <h5 class="fw-bold mb-4">Recent Sales Activity</h5>
            <table class="table align-middle">
                <thead class="table-light">
                    <tr><th>Plate Number</th><th>Wash Package</th><th>Paid Amount</th><th>Timestamp</th></tr>
                </thead>
                <tbody>
                    <?php while($row = $recent->fetch_assoc()): ?>
                    <tr>
                        <td><span class="badge bg-dark"><?= $row['plate_number'] ?></span></td>
                        <td><?= $row['service_name'] ?></td>
                        <td class="fw-bold"><?= number_format($row['amount']) ?> RWF</td>
                        <td class="text-muted small"><?= date('M d, H:i', strtotime($row['payment_date'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>