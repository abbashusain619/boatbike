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
            $stmt = $conn->prepare("SELECT * FROM clients");
            $stmt->execute();

            // Check if any records are found
            if ($stmt->rowCount() > 0) {
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Home Address</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial_number = 1;
                while ($client = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $serial_number++ ?></td>
                        <td><?= htmlspecialchars($client['FirstName']) ?></td>
                        <td><?= htmlspecialchars($client['LastName']) ?></td>
                        <td><?= htmlspecialchars($client['HomeAddress']) ?></td>
                        <td><?= htmlspecialchars($client['PhoneNumber']) ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
            } else {
                echo "<p>No clients found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error fetching clients: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </body>
    </html>
<?php
} else {
    header("Location: index.php?error=You need to login first boi!");
    exit();
}
