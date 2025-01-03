<?php
session_start();
header('Content-Type: application/json');

// Include database connection
include 'conn.php';

// Retrieve JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON input.']);
    exit();
}

// Validate CSRF token
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $data['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
    exit();
}

// Extract fields from JSON input
$vehicleId = $data['id'] ?? null;
$VRN = $data['vrn'] ?? null;
$chassisNumber = $data['chassisnumber'] ?? null;
$engineNumber = $data['enginenumber'] ?? null;
$model = $data['model'] ?? null;
$company = $data['company'] ?? null;
$type = $data['type'] ?? null;

// Validate fields
if (!$vehicleId || !$VRN || !$chassisNumber || !$engineNumber || !$model || !$company || !$type) {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit();
}

try {
    // Prepare SQL update query
    $stmt = $conn->prepare("
        UPDATE vehicles 
        SET VRN = ?, ChassisNumber = ?, EngineNumber = ?, Model = ?, Company = ?, Type = ? 
        WHERE id = ?
    ");
    $stmt->execute([$VRN, $chassisNumber, $engineNumber, $model, $company, $type, $vehicleId]);

    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Vehicle updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No changes were made to the record.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
