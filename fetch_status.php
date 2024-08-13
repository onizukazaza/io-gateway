<?php
require_once 'controllers/server.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not authenticated.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$query = $conn->prepare("
    SELECT
        m.match_id,
        mt1.status_pin AS status1,
        mt2.status_pin AS status2,
        COALESCE(mv.value, 'No data') AS value
    FROM
        `match` m 
    JOIN 
        detail mt1 ON m.match_id = mt1.match_id
    JOIN 
        detail mt2 ON m.match_id = mt2.match_id
    JOIN 
        gpio g1 ON mt1.gpio_id = g1.gpio_id
    JOIN 
        gpio g2 ON mt2.gpio_id = g2.gpio_id
    JOIN 
        device d1 ON g1.device_id = d1.device_id 
    LEFT JOIN 
        match_value mv ON mt1.match_id = mv.match_id
    WHERE
        d1.user_id = :user_id
");

$query->bindParam(':user_id', $user_id);
$query->execute();

$results = $query->fetchAll(PDO::FETCH_ASSOC);

// ส่งกลับข้อมูลสถานะเป็น JSON
echo json_encode($results);
?>
