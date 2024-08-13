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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Match</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/matchdevice.css">
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
        JOIN sensor s ON s.gpio_id = g.gpio_id
    )
");
$check_device->bindParam(":user_id", $user_id);
$check_device->execute();
$Result_device = $check_device->fetchAll(PDO::FETCH_ASSOC); 
?>

<style>
  
    .form-select {
        min-width: 300px;
    }
    .error_message {
    color: red;
    background-color: #ffe6e6;
    padding: 10px;
    border: 1px solid red;
    margin: 10px 0;
    width: 100%;
    text-align: center;
}

</style>

<div class="container" id="tambahOutput">
    <h3 class="mt-4">CREATE MATCH DEVICE</h3>
    <hr>
    
    <form action="controllers/match_db.php" method="post">
      
        <div class="row">
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger error-message" role="alert">
            <?= $_SESSION['error_message'] ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

            <div class="col-md-6">
                <div class="bigbox">
                    <div class="box1">
                        <label for="device1" class="form-label">Device 1</label>
                        <select class="form-select" name="device1" required>
                            <option value="" disabled selected>Select a board</option>
                            <?php foreach ($Result_device as $row): ?>
                                <option value="<?php echo $row['device_id']; ?>"><?php echo $row['device_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <br>
                        <label for="type1" class="form-label">Type</label>
                        <select class="form-select" name="type1" onchange="fetchGpioOptions(1)">
                            <option value="" disabled selected>Select a type</option>
                            <option value="1">Input</option>
                            <option value="2">Output</option>
                        </select>
                        <br>
                        <label for="gpio1" class="form-label">GPIO</label>
                        <select class="form-select" id="gpio1" name="gpio1" required>
                            <option value="" disabled selected>Select a GPIO</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="bigbox">
                    <div class="box2">
                        <label for="device2" class="form-label">Device 2</label>
                        <select class="form-select" name="device2" required>
                            <option value="" disabled selected>Select a board</option>
                            <?php foreach ($Result_device as $row): ?>
                                <option value="<?php echo $row['device_id']; ?>"><?php echo $row['device_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <br>
                        <label for="type2" class="form-label">Type</label>
                        <select class="form-select" name="type2" onchange="fetchGpioOptions(2)">
                            <option value="" disabled selected>Select a type</option>
                            <option value="1">Input</option>
                            <option value="2">Output</option>
                        </select>
                        <br>
                        <label for="gpio2" class="form-label">GPIO</label>
                        <select class="form-select" id="gpio2" name="gpio2" required>
                            <option value="" disabled selected>Select a GPIO</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" name="create" class="btn btn-primary">Match Device</button>
    </form>
</div>

<script>
    function fetchGpioOptions(deviceNumber) {
        const deviceSelect = document.querySelector(`[name="device${deviceNumber}"]`);
        const typeSelect = document.querySelector(`[name="type${deviceNumber}"]`);
        const gpioSelect = document.querySelector(`#gpio${deviceNumber}`);

        if (deviceSelect.value && typeSelect.value) {
            const deviceID = deviceSelect.value;
            const typeID = typeSelect.value;

            fetch(`fetch_gpio_match.php?device_id=${deviceID}&type_id=${typeID}`)
                .then(response => response.json())
                .then(data => {
                    gpioSelect.innerHTML = data.options;
                })
                .catch(error => {
                    console.error('Error fetching GPIO options:', error);
                });
        } else {
            gpioSelect.innerHTML = '<option value="" disabled selected>Select a GPIO</option>';
        }
    }
</script>

</body>
</html>
