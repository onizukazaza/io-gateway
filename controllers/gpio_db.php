<?php
require_once 'server.php';

if (isset($_POST['create'])) {
    $gpio = $_POST["gpio"];
    $type_id = $_POST["typed"];
    $device_id = $_POST["device"];

function redirectToGPIOWithError($gpio, $errorMessage) {

    if ($errorMessage != 'Please select a pin' && $errorMessage != 'Please select a device' && $errorMessage != 'Please select pin type' && !empty($gpio)) {
        $_SESSION['error'] = $errorMessage . ' ' . $gpio . ' already';
    } else {
        $_SESSION['error'] = $errorMessage;
    }
    header("location: /io_gateway/gpio.php");
    exit;
}

  
    if (empty($device_id)) {
        redirectToGPIOWithError($gpio, 'Please select a device');
    } elseif (empty($gpio)) {
        redirectToGPIOWithError($gpio, 'Please select a pin');
    } elseif (empty($type_id)) {
        redirectToGPIOWithError($gpio, 'Please select pin type');
    }


$check_sql = "SELECT gpio.*, type.typename FROM gpio 
INNER JOIN type ON gpio.type_id = type.type_id 
WHERE gpio.gpio_pin = :gpio AND gpio.type_id = :typed AND gpio.device_id = :device";

$check_stmt = $conn->prepare($check_sql);
$check_stmt->bindParam(":gpio", $gpio);
$check_stmt->bindParam(":typed", $type_id);
$check_stmt->bindParam(":device", $device_id);
$check_stmt->execute();

if ($check_stmt->rowCount() > 0) {

$data = $check_stmt->fetch(PDO::FETCH_ASSOC);
$typename = $data['typename'];  
redirectToGPIOWithError($gpio, 'The selected device has added a pin of type ' . $typename , 'already');
    } else {
    
        $sql = "INSERT INTO gpio(gpio_pin, type_id, device_id) VALUES (:gpio, :typed, :device)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":gpio", $gpio);
        $stmt->bindParam(":typed", $type_id);
        $stmt->bindParam(":device", $device_id);

        if ($stmt->execute()) {
            $type_sql = "SELECT typename FROM type WHERE type_id = :type_id";
            $type_stmt = $conn->prepare($type_sql);
            $type_stmt->bindParam(":type_id", $type_id);
            $type_stmt->execute();
            $type = $type_stmt->fetch(PDO::FETCH_ASSOC);
        
            $typename = isset($type['typename']) ? $type['typename'] : 'Unknown';
            $_SESSION['success_message'] = 'Added pin ' . $gpio . ' of type ' . $typename . ' successfully!';
        } else {
            $_SESSION['error'] = 'Something went wrong. Unable to add pin';
        }
        
        header("location: /io_gateway/gpio.php");
    }
}
?>
