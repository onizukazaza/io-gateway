<?php
include('../controllers/server.php');

function updateDeviceState($conn, $status_pin, $match_id, $gpio_id) {
    $stmt = $conn->prepare("UPDATE detail SET status_pin = ? WHERE match_id = ? AND gpio_id = ?");
    $stmt->bindParam(1, $status_pin);
    $stmt->bindParam(2, $match_id);
    $stmt->bindParam(3, $gpio_id);
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

$stmt = $conn->prepare("SELECT gpio_id, type_id FROM gpio WHERE gpio_pin = ? AND device_id = ?");
$stmt->execute([$gpio_pin, $user_device['device_id']]);
$gpio = $stmt->fetch();

if (!$gpio) {
    echo json_encode(["message" => "GPIO not found."]);
    exit();
}

$stmt = $conn->prepare("SELECT typename FROM type WHERE type_id = ?");
$stmt->execute([$gpio['type_id']]);
$type = $stmt->fetch();

if (!$type) {
    echo json_encode(["message" => "Type not found."]);
    exit();
}

if ($type['typename'] === 'Input') {
    if (empty($value)) {
        echo json_encode(["message" => "Value is required for input type GPIO."]);
        exit();
    }
} elseif ($type['typename'] === 'Output') {
    echo json_encode(["message" => "This GPIO is set as Output"]);
    exit();
} else {
    echo json_encode(["message" => "Invalid GPIO type."]);
    exit();
}

$gpio_id = $gpio['gpio_id'];
$stmt = $conn->prepare("SELECT match_id FROM detail WHERE gpio_id = ?");
$stmt->execute([$gpio_id]);
$match = $stmt->fetch();

if (!$match) {
    echo json_encode(["message" => "Match not found."]);
    exit();
}

$match_id = $match['match_id'];
$stmt = $conn->prepare("SELECT * FROM match_value WHERE match_id = ?");
$stmt->execute([$match_id]);
$matchValue = $stmt->fetch();

if ($matchValue) {
    if ($matchValue['value'] !== $value) {
        $stmt = $conn->prepare("UPDATE match_value SET value = ?, create_at = NOW() WHERE match_id = ?");
        $stmt->bindParam(1, $value);
        $stmt->bindParam(2, $match_id);
        $stmt->execute();
        echo json_encode(["message" => "Data updated successfully"]);
        updateDeviceState($conn, "Online", $match_id, $gpio_id);
    } else {
        echo json_encode(["message" => "No changes detected."]);
    }
} else {
    $stmt = $conn->prepare("INSERT INTO match_value (match_id, value) VALUES (?, ?)");
    $stmt->bindParam(1, $match_id);
    $stmt->bindParam(2, $value);
    $stmt->execute();
    echo json_encode(["message" => "Data received and inserted successfully"]);
    updateDeviceState($conn, "Online", $match_id, $gpio_id);
}
?>


