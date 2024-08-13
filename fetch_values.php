<?php
require_once 'controllers/server.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT s.status_pin, g.gpio_pin, s.sensor_name, COALESCE(s.value, 'no data') AS value, s.unit, s.sensor_id, d.device_name
    FROM sensor s 
    JOIN gpio g ON s.gpio_id = g.gpio_id
    JOIN device d ON g.device_id = d.device_id  
    WHERE d.user_id = :user_id
    ORDER BY s.sensor_id ASC
");

$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($sensors);
?>
