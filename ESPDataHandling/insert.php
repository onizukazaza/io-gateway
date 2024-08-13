<?php

include ('../controllers/server.php');

function updateDeviceState($conn, $status_pin, $gpio_id) {
    $stmt = $conn->prepare("UPDATE sensor SET status_pin = ? WHERE gpio_id = ?");
    $stmt->bindParam(1, $status_pin);
    $stmt->bindParam(2, $gpio_id);
    $stmt->execute();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["message" => "Invalid request."]);

    exit();
}
$devicename = $_POST['devicename'] ?? '';
$gpio_pin = $_POST['gpio'] ?? '';
$value = $_POST['value'] ?? '';
$user_token = $_POST['usertoken'] ?? '';

if (empty($devicename) || empty($gpio_pin) || empty($value) || empty($user_token)) {
    echo json_encode(["message" => "Invalid data. Please provide all required parameters."]);

    exit();
}
$stmt = $conn->prepare("SELECT user_id FROM user WHERE token = ?");
$stmt->execute([$user_token]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["message" => "Unauthorized access."]);
    exit();
}

$userId = $user['user_id'];
$stmt = $conn->prepare("SELECT device_id FROM device WHERE user_id = ? AND device_name = ?");
$stmt->execute([$userId, $devicename]);
$user_device = $stmt->fetch();

if (!$user_device) {
    echo json_encode(["message" => "Device not found."]);
    exit();
}

$stmt = $conn->prepare("SELECT gpio_id FROM gpio WHERE gpio_pin = ? AND device_id = ?");
$stmt->execute([$gpio_pin, $user_device['device_id']]);
$gpio = $stmt->fetch();

if (!$gpio) {
    echo json_encode(["message" => "GPIO not found."]);
    exit();
}

$gpio_id = $gpio['gpio_id'];
$stmt = $conn->prepare("SELECT sensor_id, value as existing_value FROM sensor WHERE gpio_id = ?");
$stmt->execute([$gpio_id]);
$sensor_data = $stmt->fetch();

if ($sensor_data) {
    if ($sensor_data['existing_value'] !== $value) {
        $stmt = $conn->prepare("UPDATE sensor SET value = ?, create_at = NOW() WHERE gpio_id = ?");
        $stmt->bindParam(1, $value);
        $stmt->bindParam(2, $gpio_id);
        $stmt->execute();
        echo json_encode(["message" => "Data updated successfully"]);
        updateDeviceState($conn, "Online", $gpio_id);
    } else {
        echo json_encode(["message" => "No changes detected."]);
    }
} else {
    echo json_encode(["message" => "Not found sensor"]);
}
?>

