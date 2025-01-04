<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "conn.php";

    // Check CSRF token
    $input = json_decode(file_get_contents('php://input'), true);

    // Debug: Log received input to a file for analysis
    // file_put_contents('debug.log', print_r($input, true), FILE_APPEND);

    
    if (!isset($input['csrf_token']) || $input['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
        exit();
    }

    $id = $input['id'] ?? '';
    $first_name = $input['FirstName'] ?? '';
    $last_name = $input['LastName'] ?? '';
    $home_address = $input['HomeAddress'] ?? '';
    $phone_number = $input['PhoneNumber'] ?? '';

    if (empty($id) || empty($first_name) || empty($last_name) || empty($home_address) || empty($phone_number)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE clients SET FirstName = ?, LastName = ?, HomeAddress = ?, PhoneNumber = ? WHERE Id = ?");
        $stmt->execute([$first_name, $last_name, $home_address, $phone_number, $id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
