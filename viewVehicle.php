<?php
session_start();
session_regenerate_id();
if (isset($_SESSION['user_id']) && isset($_SESSION['user_username'])) {
    include "conn.php"; // Ensure database connection is included
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
    </head>
    <body>
        <h1>Welcome <?=$_SESSION['user_username']?></h1>
        <a href="addClient.php">Add client</a><br>
        <a href="addVehicle.php">Add vehicle</a><br>
        <a href="addContract.php">Add contract</a><br>
        <a href="viewClient.php">View client</a><br>
        <a href="viewVehicle.php">View vehicles</a><br>
        <a href="viewContract.php">View contracts</a><br>
        <a href="dashboard.php">View Dashboard</a><br>
        <a href="logout.php">Logout</a>

        <br><br><br>
        <h1>Client Table</h1>
        <?php
        try {
            // Fetch all clients
            $stmt = $conn->prepare("SELECT * FROM vehicles");
            $stmt->execute();

            // Check if any records are found
            if ($stmt->rowCount() > 0) {
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>VRN</th>
                    <th>ChassisNumber</th>
                    <th>EngineNumber</th>
                    <th>Model</th>
                    <th>Company</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial_number = 1;
                while ($vehicle = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $serial_number++ ?></td>
                        <td><?= htmlspecialchars($vehicle['VRN']) ?></td>
                        <td><?= htmlspecialchars($vehicle['ChassisNumber']) ?></td>
                        <td><?= htmlspecialchars($vehicle['EngineNumber']) ?></td>
                        <td><?= htmlspecialchars($vehicle['Model']) ?></td>
                        <td><?= htmlspecialchars($vehicle['Company']) ?></td>
                        <td><?= htmlspecialchars($vehicle['Type']) ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
            } else {
                echo "<p>No vehicles found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error fetching vehicles: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </body>
    </html>
<?php
} else {
    header("Location: index.php?error=You need to login first boi!");
    exit();
}
