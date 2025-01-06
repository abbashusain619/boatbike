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
$contractId = $data['id'] ?? null;
$contractNumber = $data['ContractNumber'] ?? null;
$clientId = $data['ClientId'] ?? null;
$vehicleId = $data['VehicleId'] ?? null;
$vehiclePrice = $data['VehiclePrice'] ?? null;
$balanceRemaining = $data['BalanceRemaining'] ?? null;
$dateStart = $data['DateStart'] ?? null;
$dateEnd = $data['DateEnd'] ?? null;
$contractStatus = $data['ContractStatus'] ?? null;
// Validate fields
if (!$contractId || !$contractNumber || !$clientId || !$vehicleId || !$vehiclePrice || !$balanceRemaining || !$dateStart || !$dateEnd || !$contractStatus) {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit();
}

try {
    // Prepare SQL update query
    $stmt = $conn->prepare("
        UPDATE contracts 
        SET ContractNumber = ?, ClientId = ?, VehicleId = ?, VehiclePrice = ?, BalanceRemaining = ?, DateStart = ?, DateEnd = ?, ContractStatus = ? 
        WHERE Id = ?
    ");
    $stmt->execute([$contractNumber, $clientId, $vehicleId, $vehiclePrice, $balanceRemaining, $dateStart, $dateEnd, $contractStatus, $contractId]);

    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Contract updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No changes were made to the record.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
