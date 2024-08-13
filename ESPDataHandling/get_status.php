<?php
include ('../controllers/server.php');

function updateDeviceState($conn, $status_pin, $match_id, $gpio_id) {
    $stmt = $conn->prepare("UPDATE detail SET status_pin = ? WHERE match_id = ? AND gpio_id = ?");
    $stmt->bindParam(1, $status_pin);
    $stmt->bindParam(2, $match_id);
    $stmt->bindParam(3, $gpio_id);
    $stmt->execute();
}


function jsonResponse($message) {
    echo json_encode(["message" => $message]);
    exit();
}


if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonResponse("Invalid request. Use POST method.");
}

$devicename = $_GET['devicename'] ?? '';
$gpio_pin = $_GET['gpio'] ?? '';
$user_token = $_GET['usertoken'] ?? '';  


if (empty($devicename) || empty($gpio_pin) || empty($user_token)) {
    jsonResponse("Invalid data. Please provide all required parameters.");
}

$stmt = $conn->prepare("SELECT user_id FROM user WHERE token = ?");
$stmt->execute([$user_token]);
$user = $stmt->fetch();

if (!$user) {
    jsonResponse("Unauthorized access.");
}

$userId = $user['user_id'];
$stmt = $conn->prepare("SELECT device_id FROM device WHERE user_id = ? AND device_name = ?");
$stmt->execute([$userId, $devicename]);
$user_device = $stmt->fetch();

if (!$user_device) {
    jsonResponse("Device not found.");
}

$stmt = $conn->prepare("SELECT gpio_id FROM gpio WHERE gpio_pin = ? AND device_id = ?");
$stmt->execute([$gpio_pin, $user_device['device_id']]);
$gpio = $stmt->fetch();

if (!$gpio) {
    jsonResponse("GPIO not found.");
}

$gpio_id = $gpio['gpio_id'];
$stmt = $conn->prepare("SELECT match_id FROM detail WHERE gpio_id = ?");
$stmt->execute([$gpio_id]);
$match = $stmt->fetch();

if (!$match) {
    jsonResponse("Match not found.");

}

$match_id = $match['match_id'];
$stmt = $conn->prepare("SELECT value FROM match_value WHERE match_id = ? ORDER BY match_value_id DESC LIMIT 1");
$stmt->execute([$match_id]);
$statusResult = $stmt->fetch();

if (!$statusResult) {
    jsonResponse("No status data found.");
}

echo $statusResult['value'];
updateDeviceState($conn, "Online", $match_id, $gpio_id);

?>