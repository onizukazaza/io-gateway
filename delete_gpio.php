<?php
require_once 'controllers/server.php';

if (isset($_GET['gpio_id'])) {
    $gpio_id = intval($_GET['gpio_id']); 
    

    $fetch_device_id_stmt = $conn->prepare("SELECT device_id FROM gpio WHERE gpio_id = :gpio_id");
    $fetch_device_id_stmt->bindParam(':gpio_id', $gpio_id);
    $fetch_device_id_stmt->execute();
    $associated_device = $fetch_device_id_stmt->fetch(PDO::FETCH_ASSOC);
    if (!$associated_device) {
        $_SESSION['error'] = 'Associated device not found!';
        header("Location: detail_device.php");
        exit();
    }
    $associated_device_id = $associated_device['device_id'];

    try {
      
        $conn->beginTransaction();

       
        $stmt = $conn->prepare("DELETE FROM gpio WHERE gpio_id = :gpio_id");
        $stmt->bindParam(':gpio_id', $gpio_id);
        $stmt->execute();

       
        $conn->commit();

        $_SESSION['success'] = 'GPIO deleted successfully!';
    } catch (PDOException $e) {
        $conn->rollback(); 
        $_SESSION['error'] = 'Error deleting GPIO.'; 
    }
} else {
    $_SESSION['error'] = 'GPIO ID not specified!';
}

header("Location: detail_device.php?device_id=" . $associated_device_id);
exit();
?>