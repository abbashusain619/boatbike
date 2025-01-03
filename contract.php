<?php
session_start();
include "conn.php";

if (isset($_SESSION['user_id']) && isset($_SESSION['user_username'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve input data
        $contractNumber = $_POST['contractNumber'] ?? '';
        $clientId = $_POST['clientId'] ?? '';
        $vehicleId = $_POST['vehicleId'] ?? '';
        $vehiclePrice = $_POST['vehiclePrice'] ?? '';
        $balanceRemaining = $_POST['balanceRemaining'] ?? '';
        $dateStart = $_POST['dateStart'] ?? '';
        $dateEnd = $_POST['dateEnd'] ?? '';
        $contractStatus = $_POST['contractStatus'] ?? '';

        // Validate input
        if (empty($contractNumber) || empty($clientId) || empty($vehicleId) || empty($vehiclePrice)|| empty($balanceRemaining) || empty($dateStart) || empty($dateEnd) || empty($contractStatus)) {
            header("Location: addContract.php?error=All fields are required.");
            exit();
        }

        try {
            // Insert the client into the database
            $stmt = $conn->prepare("INSERT INTO contracts (ContractNumber, ClientId, VehicleId, VehiclePrice, BalanceRemaining, DateStart, DateEnd, ContractStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$contractNumber, $clientId, $vehicleId, $vehiclePrice, $balanceRemaining, $dateStart, $dateEnd, $contractStatus]);

            // Redirect with success message
            header("Location: addContract.php?success=Contract added successfully!");
            exit();
        } catch (PDOException $e) {
            // Redirect with error message
            header("Location: addContract.php?error=Failed to add contract: " . htmlspecialchars($e->getMessage()));
            exit();
        }
    } else {
        header("Location: dashboard.php?error=Invalid request method.");
        exit();
    }
} else {
    header("Location: index.php?error=You need to login first boi!");
    exit();
}
