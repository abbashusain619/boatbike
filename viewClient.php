<?php
session_start();
session_regenerate_id(true); // Regenerate session ID for security

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_username'])) {
    header("Location: index.php?error=You need to login first!");
    exit();
}

include "conn.php"; // Include the database connection

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Clients</title>
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
        <a href="viewVehicles.php">View vehicles</a><br>
        <a href="viewContract.php">View contracts</a><br>
        <a href="dashboard.php">View Dashboard</a><br>
        <a href="logout.php">Logout</a>
    <h1>Client Table</h1>
    <?php
    try {
        // Fetch all clients
        $stmt = $conn->prepare("SELECT * FROM clients");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
    ?>
    <table border="1" id="editableTable">
        <thead>
            <tr>
                <th>S/N</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Home Address</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $serial_number = 1;
            while ($client = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr data-id="<?= htmlspecialchars($client['Id'], ENT_QUOTES, 'UTF-8') ?>">
                    <td><?= $serial_number++ ?></td>
                    <td class="editable" contenteditable="true"><?= htmlspecialchars($client['FirstName'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="editable" contenteditable="true"><?= htmlspecialchars($client['LastName'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="editable" contenteditable="true"><?= htmlspecialchars($client['HomeAddress'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="editable" contenteditable="true"><?= htmlspecialchars($client['PhoneNumber'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><button onclick="saveRow(this)">Save</button></td>
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
        echo "<p>Error fetching clients: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    }
    ?>
    <script>
        function saveRow(button) {
        const row = button.closest('tr');
        const id = row.getAttribute('data-id');
        const cells = row.querySelectorAll('.editable');
        const data = {
            id: id, // Use lowercase for the id key
            csrf_token: '<?= $csrf_token ?>'
        };

    const fieldNames = ['FirstName', 'LastName', 'HomeAddress', 'PhoneNumber'];
    let isValid = true;

    cells.forEach((cell, index) => {
        const value = cell.textContent.trim();
        if (!value) {
            isValid = false;
            alert(fieldNames[index] + ' cannot be empty.');
            return;
        }
        data[fieldNames[index]] = value; // Dynamically add fields to the data object
    });

    if (!isValid) return;

    console.log('Sending data:', data); // Debugging log

    fetch('updateClient.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Client updated successfully!');
        } else {
            alert('Error updating client: ' + result.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

    </script>
</body>
</html>
