<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /CWSMS_Project/auth/login.php"); exit; }
require_once $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/config/db.php';
$page_title = "Service Packages";

if (isset($_POST['add_service'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = intval($_POST['price']);
    $conn->query("INSERT INTO services (service_name, price) VALUES ('$name', '$price')");
}
$services = $conn->query("SELECT * FROM services");
?>
<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/includes/header.php'; ?>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/includes/sidebar.php'; ?>
    <div class="main">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/includes/navbar.php'; ?>
        <div class="card p-4 mb-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3">Add Package</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Service Name" required></div>
                <div class="col-md-4"><input type="number" name="price" class="form-control" placeholder="Price (RWF)" required></div>
                <div class="col-md-2"><button name="add_service" class="btn btn-primary w-100">Add</button></div>
            </form>
        </div>
        <div class="row">
            <?php while($s = $services->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card p-3 text-center border-0 shadow-sm">
                    <h6 class="fw-bold"><?= $s['service_name'] ?></h6>
                    <h4 class="text-primary fw-bold"><?= number_format($s['price']) ?> RWF</h4>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>