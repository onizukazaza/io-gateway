<?php 
    require_once 'controllers/server.php';
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Please log in!';
        header('location: login.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Sensor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <style>
.error-message {
  @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap');
    color: red;
    font-size:17px;
    line-height: 1.5;
    margin-bottom: 20px;
    font-family: 'Noto Sans Thai', sans-serif;
    font-weight: bold; 
}
</style>
</head>
<body>

<?php 
    include("navbar.php");
    include("sidebar.php");
    $check_device = $conn->prepare("
    SELECT device_id, device_name 
    FROM device 
    WHERE user_id = :user_id AND device_id NOT IN (
        SELECT g.device_id 
        FROM gpio g
        INNER JOIN detail d ON g.gpio_id = d.gpio_id
        INNER JOIN `match` m ON d.match_id = m.match_id
    )
");

    $check_device->bindParam(":user_id", $user_id);
    $check_device->execute();
    $Result_device = $check_device->fetchAll(PDO::FETCH_ASSOC);
?>

    <div class="container" id="tambahOutput">
    <h3 class="mt-4">CREATE NEW SENSOR</h3>
    <hr>
    <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger error-message ">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error'])?>
        <?php endif; ?>
        <form action="controllers/sensor_db.php" method="post">
            <div class="mb-3">
                <label for="sensor_name">Sensor Name</label>
                <input type="text" class="form-control" id="sensor_name" name="sensor_name">
            </div>
            <div class="mb-3">
                <label for="sensor_name">Unit</label>
                <select class="form-select" id="unit" name="unit">
                        <option disabled selected>Select a type</option>
                            <option value="°C">Celsius  °C</option>
                            <option value="°F">Fahrenheit  °F</option>
                    </select>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="device" class="form-label">Device</label>
                    <select class="form-select" id="device" name="device">
                        <option value="" disabled selected>Select a board</option>
                            <?php foreach ($Result_device as $row): ?>
                                <option value="<?php echo $row['device_id']; ?>"><?php echo $row['device_name']; ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>   
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select class="form-select" id="type" name="type">
                    <option value="" disabled selected>Select a type</option>
                                    <option value="1">Input</option>
                                    <option value="2">Output</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-group">
                    <label for="gpio">GPIO</label>
                    <select class="form-select" id="gpio" name="gpio">
                        <option value="" disabled selected>Select a GPIO</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="create" class="btn btn-primary">Done</button>
        </form>
    </div>

    <script>
        function updateGPIODropdown() {
            let deviceId = document.getElementById('device').value;
            let typeId = document.getElementById('type').value; 
            fetch(`fetch_gpio_sensor.php?device_id=${deviceId}&type_id=${typeId}`)
                .then(response => response.json())
                .then(data => {
                    let gpioSelect = document.getElementById('gpio');
                    gpioSelect.innerHTML = data.options;
                });
        }
      
        document.getElementById('device').addEventListener('change', updateGPIODropdown);
        document.getElementById('type').addEventListener('change', updateGPIODropdown);
    </script>
</body>
</html>