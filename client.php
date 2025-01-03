<?php
session_start();
include "conn.php";

if (isset($_SESSION['user_id']) && isset($_SESSION['user_username'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve input data
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $homeAddress = $_POST['homeAddress'] ?? '';
        $phoneNumber = $_POST['phoneNumber'] ?? '';

        // Validate input
        if (empty($firstName) || empty($lastName) || empty($homeAddress) || empty($phoneNumber)) {
            header("Location: addClient.php?error=All fields are required.");
            exit();
        }

        try {
            // Insert the client into the database
            $stmt = $conn->prepare("INSERT INTO clients (FirstName, LastName, HomeAddress, PhoneNumber) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $homeAddress, $phoneNumber]);

            // Redirect with success message
            header("Location: addClient.php?success=Client added successfully!");
            exit();
        } catch (PDOException $e) {
            // Redirect with error message
            header("Location: addClient.php?error=Failed to add client: " . htmlspecialchars($e->getMessage()));
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
