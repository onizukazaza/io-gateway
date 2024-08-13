<?php

require_once 'controllers/server.php';

$deviceID = isset($_GET['device_id']) ? intval($_GET['device_id']) : null;
$typeID = isset($_GET['type_id']) ? intval($_GET['type_id']) : null;

$options = '';
$response = array();

if ($deviceID !== null && $typeID !== null) {
    try {
        $sql = "
            SELECT g.gpio_id, g.gpio_pin, 
                   CASE WHEN s.gpio_id IS NOT NULL THEN 'used' ELSE 'available' END as status 
            FROM gpio g 
            LEFT JOIN sensor s ON g.gpio_id = s.gpio_id 
            WHERE g.device_id = :device_id AND g.type_id = :type_id";
            
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':device_id', $deviceID, PDO::PARAM_INT);
        $stmt->bindParam(':type_id', $typeID, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            $status = $row['status'] === 'used' ? ' (used)' : '';
            $options .= '<option value="' . $row['gpio_id'] . '"' . ($status ? ' disabled' : '') . '>' . $row['gpio_pin'] . $status . '</option>';
        }

        $response['options'] = $options;
    } catch (PDOException $e) {
        $response['error'] = "Database error: " . $e->getMessage();
    }
} else {
    $response['error'] = "Invalid or missing device_id or type_id.";
}

header('Content-Type: application/json');
echo json_encode($response);


?>



