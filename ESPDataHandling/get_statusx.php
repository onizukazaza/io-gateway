<?php
include('../controllers/server.php');

// Function to update the device state in the database
function updateDeviceState($conn, $status_pin, $match_id, $gpio_id) {
    $stmt = $conn->prepare("UPDATE detail SET status_pin = ? WHERE match_id = ? AND gpio_id = ?");
    $stmt->bindParam(1, $status_pin);
    $stmt->bindParam(2, $match_id);
    $stmt->bindParam(3, $gpio_id);
    $stmt->execute();
}

// Function to provide JSON response
function jsonResponse($message, $data = null) {
    echo json_encode(["message" => $message, "data" => $data]);
    exit();
}

// Validate the request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse("Invalid request. Use GET method.");
}

// Retrieve and validate the required parameters
$devicename = $_GET['devicename'] ?? '';
$gpio_pin = $_GET['gpio'] ?? '';
$user_token = $_GET['usertoken'] ?? '';

if (empty($devicename) || empty($gpio_pin) || empty($user_token)) {
    jsonResponse("Invalid data. Please provide all required parameters.");
}

// Verify user token
$stmt = $conn->prepare("SELECT user_id FROM user WHERE token = ?");
$stmt->execute([$user_token]);
$user = $stmt->fetch();

if (!$user) {
    jsonResponse("Unauthorized access.");
}

// Retrieve user device
$userId = $user['user_id'];
$stmt = $conn->prepare("SELECT device_id FROM device WHERE user_id = ? AND device_name = ?");
$stmt->execute([$userId, $devicename]);
$user_device = $stmt->fetch();

if (!$user_device) {
    jsonResponse("Device not found.");
}

// Retrieve GPIO
$stmt = $conn->prepare("SELECT gpio_id, type_id FROM gpio WHERE gpio_pin = ? AND device_id = ?");
$stmt->execute([$gpio_pin, $user_device['device_id']]);
$gpio = $stmt->fetch();

if (!$gpio) {
    jsonResponse("GPIO not found.");
}

// Retrieve type
$stmt = $conn->prepare("SELECT typename FROM type WHERE type_id = ?");
$stmt->execute([$gpio['type_id']]);
$type = $stmt->fetch();

if (!$type) {
    jsonResponse("Type not found.");
}

// Validate GPIO type
if ($type['typename'] === 'Input') {
    jsonResponse("This GPIO is set as output");
}

// Retrieve match
$gpio_id = $gpio['gpio_id'];
$stmt = $conn->prepare("SELECT match_id FROM detail WHERE gpio_id = ?");
$stmt->execute([$gpio_id]);
$match = $stmt->fetch();

if (!$match) {
    jsonResponse("Match not found.");
}

// Retrieve the latest status value
$match_id = $match['match_id'];
$stmt = $conn->prepare("SELECT value FROM match_value WHERE match_id = ? ORDER BY match_value_id DESC LIMIT 1");
$stmt->execute([$match_id]);
$statusResult = $stmt->fetch();

if (!$statusResult) {
    jsonResponse("No status data found.");
}

// Provide the response and update the device state
$data = ['value' => $statusResult['value']];
jsonResponse("Status retrieved successfully.", $data);

updateDeviceState($conn, "Online", $match_id, $gpio_id);
?>
