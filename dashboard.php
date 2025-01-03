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
            $stmt = $conn->prepare("SELECT
                                        c.Id AS ClientID,
                                        CONCAT(c.FirstName, ' ', c.LastName) AS ClientName,
                                        ct.ContractNumber,
                                        v.VRN AS VehicleRegistration,
                                        v.Type AS VehicleType,
                                        ct.VehiclePrice,
                                        ct.BalanceRemaining,
                                        ct.ContractStatus,
                                        ct.DateStart,
                                        ct.DateEnd
                                    FROM
                                        Clients c
                                    INNER JOIN
                                        Contracts ct ON c.Id = ct.ClientId
                                    INNER JOIN
                                        Vehicles v ON ct.VehicleId = v.Id
                                    ORDER BY
                                        c.Id, ct.DateStart DESC;
                                ");
            $stmt->execute();

            // Check if any records are found
            if ($stmt->rowCount() > 0) {
        ?>
        <table border="1">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Client Id</th>
                    <th>Client Name</th>
                    <th>Contract Number</th>
                    <th>Vehicle Registration</th>
                    <th>Vehicle Type</th>
                    <th>Price</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial_number = 1;
                while ($summary = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $serial_number++ ?></td>
                        <td><?= htmlspecialchars($summary['ClientID']) ?></td>
                        <td><?= htmlspecialchars($summary['ClientName']) ?></td>
                        <td><?= htmlspecialchars($summary['ContractNumber']) ?></td>
                        <td><?= htmlspecialchars($summary['VehicleRegistration']) ?></td>
                        <td><?= htmlspecialchars($summary['VehicleType']) ?></td>
                        <td><?= htmlspecialchars($summary['VehiclePrice']) ?></td>
                        <td><?= htmlspecialchars($summary['BalanceRemaining']) ?></td>
                        <td><?= htmlspecialchars($summary['ContractStatus']) ?></td>
                        <td><?= htmlspecialchars($summary['DateStart']) ?></td>
                        <td><?= htmlspecialchars($summary['DateEnd']) ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
            } else {
                echo "<p>No information found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error fetching summary: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </body>
    </html>
<?php
} else {
    header("Location: index.php?error=You need to login first boi!");
    exit();
}
