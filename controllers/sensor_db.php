 <?php
session_start();
require_once 'server.php';

function redirectToSensorWithError($errorMessage) {
    $_SESSION['error'] = $errorMessage;
    header("location: /io_gateway/sensor.php");
    exit;
}

if (isset($_POST['create'])) {
    $sensorName = $_POST['sensor_name'];
    $unit = $_POST['unit'];
    $device_id = $_POST['device'];
    $gpio_id = $_POST['gpio'];

    if (empty($sensorName)) {
        redirectToSensorWithError('Please enter the sensor name');
    } elseif (mb_strlen($sensorName, 'UTF-8') > 18) {
        redirectToSensorWithError('Sensor name cannot exceed 10 characters');
    } elseif (empty($unit)) {    
        redirectToSensorWithError('Please enter the unit');
    } elseif (empty($device_id)) {
        redirectToSensorWithError('Please select a device');
    } elseif (empty($gpio_id)) {
        redirectToSensorWithError('Please select a pin');
    } else {
        try {
      
            $checkUsedGPIO = $conn->prepare("SELECT COUNT(*) FROM sensor WHERE gpio_id = :gpio");
            $checkUsedGPIO->bindParam(":gpio", $gpio_id, PDO::PARAM_INT);
            $checkUsedGPIO->execute();

          
            if ($checkUsedGPIO->fetchColumn() > 0) {
                redirectToSensorWithError('The selected GPIO is already in use');
            }

        
            $checkDevice = $conn->prepare("SELECT device_id FROM device WHERE device_id = :device");
            $checkDevice->bindParam(":device", $device_id);
            $checkDevice->execute();
            $device_data = $checkDevice->fetch();

            if ($device_data) {
            
                $checkGpio = $conn->prepare("SELECT gpio_id FROM gpio WHERE gpio_id = :gpio");
                $checkGpio->bindParam(":gpio", $gpio_id);
                
                $checkGpio->execute();
                $gpio_data = $checkGpio->fetch();
            
                if ($gpio_data) {
                   
                    $insertSensor = $conn->prepare("INSERT INTO sensor (sensor_name, unit, gpio_id) VALUES (:sensor_name, :unit, :gpio_id)");
                    $insertSensor->bindParam(":sensor_name", $sensorName);
                    $insertSensor->bindParam(":unit", $unit);
                    $insertSensor->bindParam(":gpio_id", $gpio_id);
            
                    if ($insertSensor->execute()) {
                     
                        echo "Sensor created successfully.";
                        header("location: /io_gateway/dashboard_sensor.php");
                    } else {
                      
                        echo "Error creating sensor.";
                    }
                }
            }
            
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
