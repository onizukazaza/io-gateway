<?php
require_once 'server.php';

if (isset($_POST['create'])) {
    $gpio1 = $_POST['gpio1'];
    $gpio2 = $_POST['gpio2'];

    if ($gpio1 == $gpio2) {
        $_SESSION['error_message'] = "Duplicate devices";
        header("location: /io_gateway/match.php");
        exit;
    }

    try {
     
        $checkDevice = $conn->prepare("SELECT device_id FROM gpio WHERE gpio_id = :gpio1 OR gpio_id = :gpio2");
        $checkDevice->bindParam(":gpio1", $gpio1);
        $checkDevice->bindParam(":gpio2", $gpio2);
        $checkDevice->execute();
        $devices = $checkDevice->fetchAll(PDO::FETCH_COLUMN);

        if ($devices[0] == $devices[1]) {
            $_SESSION['error_message'] = "Cannot pair the same device";
            header("location: /io_gateway/match.php");
            exit;
        }

        $checkType = $conn->prepare("SELECT type_id FROM gpio WHERE gpio_id = :gpio1 OR gpio_id = :gpio2");
        $checkType->bindParam(":gpio1", $gpio1);
        $checkType->bindParam(":gpio2", $gpio2);
        $checkType->execute();
        $types = $checkType->fetchAll(PDO::FETCH_COLUMN);


        if (!(($types[0] == 1 && $types[1] == 2) || ($types[0] == 2 && $types[1] == 1))) {
            $_SESSION['error_message'] = "Must be type 'Input' paired only with 'Output'!";
            header("location: /io_gateway/match.php");
            exit;
        }

        $checkUsedGPIO = $conn->prepare("SELECT COUNT(*) FROM detail WHERE gpio_id = :gpio1 OR gpio_id = :gpio2");
        $checkUsedGPIO->bindParam(":gpio1", $gpio1);
        $checkUsedGPIO->bindParam(":gpio2", $gpio2);
        $checkUsedGPIO->execute();
        $count = $checkUsedGPIO->fetchColumn();

        if ($count > 0) {
            $_SESSION['error_message'] = "GPIOs have been paired";
            header("location: /io_gateway/match.php");
            exit;
        }
        
    
        $insertMatch = $conn->prepare("INSERT INTO `match` () VALUES ()");
        $insertMatch->execute();
        

        $match_id = $conn->lastInsertId();


        $insertDetail = $conn->prepare("INSERT INTO detail (gpio_id, match_id) VALUES (:gpio1, :match_id), (:gpio2, :match_id)");
        $insertDetail->bindParam(":gpio1", $gpio1);
        $insertDetail->bindParam(":gpio2", $gpio2);
        $insertDetail->bindParam(":match_id", $match_id);
    
        $insertDetail->execute();

        header("location: /io_gateway/dashboard_match.php");
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
} else {
   
    header("Location: /io_gateway/match.php");
    exit();
}
?>