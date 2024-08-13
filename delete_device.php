<?php
require_once 'controllers/server.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Please log in!';
    header('location: login.php');
    exit();
}

if (isset($_GET['device_id'])) {
    $device_id = intval($_GET['device_id']); 

    try {

        $stmt = $conn->prepare("DELETE FROM device WHERE device_id = :device_id");
        $stmt->bindParam(':device_id', $device_id);
        $stmt->execute();

        $_SESSION['success'] = 'Device deleted successfully!';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting device.';
    }
} else {
    $_SESSION['error'] = 'Device ID not specified!';
}

header("Location: carddevice.php");  
exit();
?>
