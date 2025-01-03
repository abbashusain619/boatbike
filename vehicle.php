<?php
session_start();
include "conn.php";

if (isset($_SESSION['user_id']) && isset($_SESSION['user_username'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve input data
        $vrn = $_POST['vrn'] ?? '';
        $chassisNumber = $_POST['chassisNumber'] ?? '';
        $engineNumber = $_POST['engineNumber'] ?? '';
        $model = $_POST['model'] ?? '';
        $company = $_POST['company'] ?? '';
        $type = $_POST['type'] ?? '';

        // Validate input
        if (empty($vrn) || empty($chassisNumber) || empty($engineNumber) || empty($model)|| empty($company) || empty($type)) {
            header("Location: addVehicle.php?error=All fields are required.");
            exit();
        }

        try {
            // Insert the client into the database
            $stmt = $conn->prepare("INSERT INTO vehicles (VRN, ChassisNumber, EngineNumber, Model, Company, Type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$vrn, $chassisNumber, $engineNumber, $model, $company, $type]);

            // Redirect with success message
            header("Location: addVehicle.php?success=Vehicle added successfully!");
            exit();
        } catch (PDOException $e) {
            // Redirect with error message
            header("Location: addVehicle.php?error=Failed to add contract: " . htmlspecialchars($e->getMessage()));
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
