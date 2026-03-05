<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: /CWSMS_Project/auth/login.php"); exit; }
require_once $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/config/db.php';
$page_title = "Vehicle Management";

if (isset($_POST['add_car'])) {
    $plate = mysqli_real_escape_string($conn, $_POST['plate']);
    $owner = mysqli_real_escape_string($conn, $_POST['owner']);
    $conn->query("INSERT INTO cars (plate_number, owner_name) VALUES ('$plate', '$owner')");
    header("Location: view_car.php");
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM cars WHERE id = $id");
    header("Location: view_car.php");
}
$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<?php include $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/includes/header.php'; ?>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/includes/sidebar.php'; ?>
    <div class="main">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/CWSMS_Project/includes/navbar.php'; ?>
        <div class="d-flex justify-content-between mb-4">
            <h4 class="fw-bold">Registered Vehicles</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">+ New Car</button>
        </div>
        <div class="card p-4 border-0 shadow-sm">
            <table class="table table-hover">
                <thead><tr><th>Plate Number</th><th>Owner</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    <?php while($row = $cars->fetch_assoc()): ?>
                    <tr>
                        <td><span class="badge bg-dark"><?= $row['plate_number'] ?></span></td>
                        <td><?= $row['owner_name'] ?></td>
                        <td class="text-end">
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="addCarModal" tabindex="-1">
        <div class="modal-dialog"><form class="modal-content" method="POST">
            <div class="modal-header"><h5>Add Vehicle</h5></div>
            <div class="modal-body">
                <input type="text" name="plate" placeholder="Plate Number" class="form-control mb-3" required>
                <input type="text" name="owner" placeholder="Owner Name" class="form-control" required>
            </div>
            <div class="modal-footer"><button type="submit" name="add_car" class="btn btn-primary w-100">Save</button></div>
        </form></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>