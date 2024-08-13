<?php
require_once 'controllers/server.php';

if (isset($_GET['sensor_id'])) {
    $sensor_id = intval($_GET['sensor_id']); 
    
    try {
   
        $conn->beginTransaction();

   
        $stmt = $conn->prepare("DELETE FROM sensor WHERE sensor_id = :sensor_id");
        $stmt->bindParam(':sensor_id', $sensor_id);
        $stmt->execute();

        
        $conn->commit();

        header("Location: dashboard_sensor.php?success=Deleted successfully!");
    } catch (PDOException $e) {
        $conn->rollback(); 
        header("Location: dashboard_sensor.php?error=Error deleting match. " . $e->getMessage());
    }
} else {
    header("Location: dashboard_sensor.php?error=Match ID not specified!");
}
?>