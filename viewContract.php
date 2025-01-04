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

        <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .editable { cursor: pointer; }
        .editable:hover { background-color: #f9f9f9; }
    </style>
    </head>
    <body>
        <h1>Welcome <?=$_SESSION['user_username']?></h1>
        <a href="addClient.php">Add client</a><br>
        <a href="addVehicle.php">Add vehicle</a><br>
        <a href="addContract.php">Add contract</a><br>
        <a href="viewClient.php">View client</a><br>
        <a href="viewVehicles.php">View vehicles</a><br>
        <a href="viewContract.php">View contracts</a><br>
        <a href="dashboard.php">View Dashboard</a><br>
        <a href="logout.php">Logout</a>

        <br><br><br>
        <h1>Contract Table</h1>
        <?php
        try {
            // Fetch all clients
            $stmt = $conn->prepare("SELECT * FROM contracts");
            $stmt->execute();

            // Check if any records are found
            if ($stmt->rowCount() > 0) {
        ?>
        <table>
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Contract Number</th>
                    <th>Client Id</th>
                    <th>Vehicle Id</th>
                    <th>Vehicle Price</th>
                    <th>Balance Remaining</th>
                    <th>DateStart</th>
                    <th>DateEnd</th>
                    <th>ContractStatus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial_number = 1;
                while ($contract = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?= $serial_number++ ?></td>
                        <td><?= htmlspecialchars($contract['ContractNumber']) ?></td>
                        <td><?= htmlspecialchars($contract['ClientId']) ?></td>
                        <td><?= htmlspecialchars($contract['VehicleId']) ?></td>
                        <td><?= htmlspecialchars($contract['VehiclePrice']) ?></td>
                        <td><?= htmlspecialchars($contract['BalanceRemaining']) ?></td>
                        <td><?= htmlspecialchars($contract['DateStart']) ?></td>
                        <td><?= htmlspecialchars($contract['DateEnd']) ?></td>
                        <td><?= htmlspecialchars($contract['ContractStatus']) ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
            } else {
                echo "<p>No contracts found.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error fetching contracts: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        ?>
    </body>
    </html>
<?php
} else {
    header("Location: index.php?error=You need to login first boi!");
    exit();
}
