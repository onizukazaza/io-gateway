<?php
require_once 'controllers/server.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Please log in!';
    header('location: login.php');
    exit();
}

$sensors_per_page = 5;
$current_page = max(1, isset($_GET['page']) ? $_GET['page'] : 1);
$offset = ($current_page - 1) * $sensors_per_page;

try {
    $getSensor = $conn->prepare("
    SELECT s.status_pin, g.gpio_pin, s.sensor_name, s.value, s.unit, s.sensor_id, d.device_name
    FROM sensor s 
    JOIN gpio g ON s.gpio_id = g.gpio_id
    JOIN device d ON g.device_id = d.device_id  
    WHERE d.user_id = :user_id
    ORDER BY s.create_at ASC
    LIMIT :offset, :sensors_per_page
");

    $getSensor->bindParam(':user_id', $_SESSION['user_id']);
    $getSensor->bindParam(':offset', $offset, PDO::PARAM_INT);
    $getSensor->bindParam(':sensors_per_page', $sensors_per_page, PDO::PARAM_INT);
    $getSensor->execute();
    $SensorData = $getSensor->fetchAll(PDO::FETCH_ASSOC);

    $query_total = $conn->prepare("SELECT COUNT(*) as total FROM sensor s JOIN gpio g ON s.gpio_id = g.gpio_id JOIN device d ON g.device_id = d.device_id WHERE d.user_id = :user_id");
    $query_total->bindParam(":user_id", $_SESSION['user_id']);
    $query_total->execute();
    $total_sensors = $query_total->fetchColumn();
    $total_pages = ceil($total_sensors / $sensors_per_page);
    
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sensor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">


</head>
<body>
    <?php
    include("navbar.php");
    include("sidebar.php");
    ?>
    <div class="container" id="tambahOutput">
        <h3 class="mb-3">DASHBOARD SENSOR</h3>
        <button id="toggleButton"><i class="fas fa-question-circle"></i> Description</button>
<div id="displayBoxContainer">
    <?php require 'displaybox-2.php'; ?>
</div>
        <hr>
        <table class="table table-bordered table-striped enhanced-table" id="DevicesTable" style="width: 1100px;">
            <thead class="thead-dark">
                <tr>
                    <th>Status</th>
                    <th>Device Name</th>
                    <th>GPIO Pin</th>
                    <th>Sensor Name</th>
                    <th>Value</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($SensorData as $sensor): ?>
    <tr data-sensor-id="<?= $sensor['sensor_id'] ?>">
        <td><span class="<?= ($sensor['status_pin'] == "Online" ? "status-online" : "status-offline") ?>"><?= $sensor['status_pin'] ?></span></td>
        <td><?= $sensor['device_name'] ?></td>
        <td><?= $sensor['gpio_pin'] ?></td>
        <td><?= $sensor['sensor_name'] ?></td>
        <td class="status"><?= $sensor['value'] ?></td> 
        <td><?= $sensor['unit'] ?></td>
        <td><button onclick="confirmDelete(<?= $sensor['sensor_id'] ?>)" class="btn btn-danger">Delete</button></td>
    </tr>
<?php endforeach; ?>
            </tbody>
            
        </table>

<div class="pagination" id="paginationdevice">
    <a href="?page=1" class="<?= ($current_page == 1) ? 'disabled' : ''; ?>">First</a>
    
    <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="<?= ($current_page == 1) ? 'disabled' : ''; ?>">Prev</a>
    
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="<?= ($i == $current_page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
    
    <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="<?= ($current_page == $total_pages) ? 'disabled' : ''; ?>">Next</a>
    
    <a href="?page=<?php echo $total_pages; ?>" class="<?= ($current_page == $total_pages) ? 'disabled' : ''; ?>">Last</a>
</div>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script>
        function confirmDelete(sensorId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this sensor data?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_sensor.php?sensor_id=' + sensorId;
                }
            });
        }
        $(document).ready(function() {
    if ($('#DevicesTable tbody tr').length === 0) {
        $('#paginationdevice').hide();
    }

    function checkSensorStatus() {
            $.ajax({
                url: 'fetch_values.php',  
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data && !data.error) {
                        data.forEach(function(sensor) {
                            const row = document.querySelector(`[data-sensor-id="${sensor.sensor_id}"]`);
                            if (row) {
                                const statusCell = row.querySelector('.status');
                                statusCell.textContent = sensor.value;
                                const statusSpan = row.querySelector('.status-pin');  
                                if (statusSpan) {
                                    statusSpan.textContent = sensor.status_pin;  
                                    statusSpan.className = sensor.status_pin === 'Online' ? 'status-online' : 'status-offline';  
                                }
                            }
                        });
                    }
                }
            });
        }

        checkSensorStatus();
        setInterval(checkSensorStatus, 5000);
    });

    </script>
</body>
</html>






