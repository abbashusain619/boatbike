<?php
session_start();
session_regenerate_id(true); // Regenerate session ID for security

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_username'])) {
    header("Location: index.php?error=You need to login first!");
    exit();
}

include "conn.php"; // Include the database connection

// Generate a CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
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
    <h1>Welcome <?= htmlspecialchars($_SESSION['user_username'], ENT_QUOTES, 'UTF-8') ?></h1>
    <a href="addClient.php">Add client</a><br>
    <a href="addVehicle.php">Add vehicle</a><br>
    <a href="addContract.php">Add contract</a><br>
    <a href="viewClient.php">View client</a><br>
    <a href="viewVehicle.php">View vehicles</a><br>
    <a href="viewContract.php">View contracts</a><br>
    <a href="dashboard.php">View Dashboard</a><br>
    <a href="logout.php">Logout</a>

    <h1>Vehicle Table</h1>
    <?php
    try {
        // Fetch all vehicles
        $stmt = $conn->prepare("SELECT * FROM vehicles");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
    ?>
    <table id="editableTable">
        <thead>
            <tr>
                <th>S/N</th>
                <th>VRN</th>
                <th>ChassisNumber</th>
                <th>EngineNumber</th>
                <th>Model</th>
                <th>Company</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $serial_number = 1;
            while ($vehicle = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr data-id="<?= htmlspecialchars($vehicle['Id'], ENT_QUOTES, 'UTF-8') ?>">
                    <td><?=htmlspecialchars($serial_number)?></td>
                <td class="editable" contenteditable="true"><?= htmlspecialchars($vehicle['VRN'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td class="editable" contenteditable="true"><?= htmlspecialchars($vehicle['ChassisNumber'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td class="editable" contenteditable="true"><?= htmlspecialchars($vehicle['EngineNumber'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td class="editable" contenteditable="true"><?= htmlspecialchars($vehicle['Model'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td class="editable" contenteditable="true"><?= htmlspecialchars($vehicle['Company'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td class="editable" contenteditable="true"><?= htmlspecialchars($vehicle['Type'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>


                    <td><button onclick="saveRow(this)">Save</button></td>
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
        echo "<p>Error fetching vehicles: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
    ?>

    <script>
       function saveRow(button) {
            const row = button.closest('tr');
            const id = row.getAttribute('data-id');
            const cells = row.querySelectorAll('.editable');
            const data = {
                id: id,
                csrf_token: '<?= $csrf_token ?>' // Include CSRF token
            };

            // Match field names with database columns
            const fields = ['vrn', 'chassisnumber', 'enginenumber', 'model', 'company', 'type'];

            cells.forEach((cell, index) => {
                data[fields[index]] = cell.textContent.trim();
            });

            console.log('Sending data:', data); // Debugging: Log the data being sent

            fetch('updateVehicle.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
                .then((response) => response.text())
                .then((text) => {
                    console.log('Raw response:', text); // Debugging: Log raw response
                    const result = JSON.parse(text);
                    if (result.success) {
                        alert('Data saved successfully!');
                    } else {
                        alert('Error saving data: ' + result.error);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }


    </script>
</body>
</html>
