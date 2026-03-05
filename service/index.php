<?php include('../config/db.php'); ?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="../style.css"></head>
<body>
    <div class="content" style="margin-left:260px;">
        <h1>Service History</h1>
        <div class="card">
            <table>
                <thead><tr><th>Date</th><th>Car Plate</th><th>Package</th><th>Price</th></tr></thead>
                <tbody>
                    <?php 
                    $sql = "SELECT s.service_date, c.plate, p.name, p.price 
                            FROM servicerecord s 
                            JOIN car c ON s.car_id = c.id 
                            JOIN package p ON s.package_id = p.id";
                    $res = $conn->query($sql);
                    while($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['service_date']; ?></td>
                        <td><?php echo $row['plate']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>