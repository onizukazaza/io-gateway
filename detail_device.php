<?php
require_once 'controllers/server.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Please log in!';
    header('location: login.php');
    exit();
}

$devices_per_page = 5;
$current_page = max(1, isset($_GET['page']) ? $_GET['page'] : 1);
$offset = ($current_page - 1) * $devices_per_page;


$device_id = $_GET['device_id'];
$check_data = $conn->prepare("SELECT device_name FROM device WHERE device_id = :device_id");
$check_data->bindParam(":device_id", $device_id);
$check_data->execute();
$device = $check_data->fetch(PDO::FETCH_ASSOC);

if (!$device) {
    $_SESSION['error'] = 'Device not found!';
    header('location: detail_device.php');
    exit();
}

$check_data = $conn->prepare("
    SELECT 
    gpio.gpio_id,
    gpio.gpio_pin, 
    type.typename,
    CASE 
        WHEN detail.gpio_id IS NOT NULL OR sensor.gpio_id IS NOT NULL THEN 'used'
        ELSE 'unused'
    END AS gpio_status
    FROM gpio
    LEFT JOIN detail ON gpio.gpio_id = detail.gpio_id
    JOIN type ON gpio.type_id = type.type_id
    LEFT JOIN sensor ON gpio.gpio_id = sensor.gpio_id
    WHERE gpio.device_id = :device_id
    LIMIT :limit OFFSET :offset
");

$query_total = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM gpio
    LEFT JOIN detail ON gpio.gpio_id = detail.gpio_id
    JOIN type ON gpio.type_id = type.type_id
    LEFT JOIN sensor ON gpio.gpio_id = sensor.gpio_id
    WHERE gpio.device_id = :device_id
");
$query_total->bindParam(":device_id", $device_id, PDO::PARAM_INT);
$query_total->execute();
$total_devices = $query_total->fetchColumn();
$total_pages = ceil($total_devices / $devices_per_page);



$check_data->bindParam(':limit', $devices_per_page, PDO::PARAM_INT);
$check_data->bindParam(':offset', $offset, PDO::PARAM_INT);
$check_data->bindParam(":device_id", $device_id);
$check_data->execute();
$Result = $check_data->fetchAll(PDO::FETCH_ASSOC);

$device_info_query = $conn->prepare("
    SELECT device.board, device.create_at, device.device_name, user.token 
    FROM device 
    JOIN user ON device.user_id = user.user_id 
    WHERE device.device_id = :device_id
");
$device_info_query->bindParam(":device_id", $device_id);
$device_info_query->execute();
$device_info = $device_info_query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Detail</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/tabledetail.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>


    <!-- highlight.js CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
</head>
<body>
    <?php 
    include("navbar.php");
    include("sidebar.php");
    ?>


<div class="page-wrapper">
    <div class="container" id="tambahOutput">
        <h3 class="mb-3">DEVICE NAME : <?php echo htmlspecialchars($device['device_name'], ENT_QUOTES, 'UTF-8');
 ?></h3>
        <hr>
        <table class="custom-table" id="allDevicesTable">
            <thead class="thead-dark">
                <tr>
                    <th>Status</th>
                    <th>GPIO Pin</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
           foreach ($Result as $row) {
            echo '
            <tr>
                <td>' . $row['gpio_status'] . '</td> <!-- Display the status directly from the result -->
                <td>' . $row['gpio_pin'] . '</td>
                <td>' . $row['typename'] . '</td>
                <td><button onclick="confirmDelete(' . $row['gpio_id'] . ')" class="btn btn-danger">Delete</button></td>
            </tr>';
        }
                ?>
            </tbody>
        </table>
        <div class="pagination" id="paginationalldevice">
    <a href="?device_id=<?= $device_id ?>&page=1" class="<?= ($current_page == 1) ? 'disabled' : ''; ?>">First</a>
    <a href="?device_id=<?= $device_id ?>&page=<?= max(1, $current_page - 1); ?>" class="<?= ($current_page == 1) ? 'disabled' : ''; ?>">Prev</a>
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?device_id=<?= $device_id ?>&page=<?= $i; ?>" class="<?= ($i == $current_page) ? 'active' : ''; ?>"><?= $i; ?></a>
    <?php endfor; ?>
    <a href="?device_id=<?= $device_id ?>&page=<?= min($total_pages, $current_page + 1); ?>" class="<?= ($current_page == $total_pages) ? 'disabled' : ''; ?>">Next</a>
    <a href="?device_id=<?= $device_id ?>&page=<?= $total_pages; ?>" class="<?= ($current_page == $total_pages) ? 'disabled' : ''; ?>">Last</a>
</div>

         </div>
         <div class="card-sidebar">
        <h2>Device Manager</h2>
        <p>Device: <span id="device-type"><?php echo $device['device_name']; ?></span></p>
        <p>Type: <span id="device-type"><?php echo $device_info['board']; ?></span></p>
        <p>Created: <span id="device-created-at"><?php echo $device_info['create_at']; ?></span></p>
     <h2>Configuration</h2>
        <p>You should declare Device Name and AuthToken</p>
        <pre><code id="arduinoCode" class="cpp">
#define YOUR_USER_TOKEN "<?php echo $device_info['token']; ?>"
#define YOUR_DEVICENAME "<?php echo $device_info['device_name']; ?>"
    </code></pre>
    <button onclick="copyCode()" class="btn btn-outline-primary btn-sm mb-3">
        <i class="fas fa-copy"></i>
    </button>
 <div class="delete-button-wrapper">
        <button onclick="confirmDeleteDevice(<?php echo $device_id; ?>)" class="btn btn-danger">Delete Device</button>
    </div>


 </div>


<script>
function confirmDelete(gpio_id) {
    $.get('controllers/detail_db.php', { gpio_id: gpio_id }, function(data) {
        if (data.canDelete) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You cannot go back!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_gpio.php?gpio_id=' + gpio_id;
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Cannot delete',
                text: data.message 
            });
        }
    }, 'json');
}


    function confirmDeleteDevice(device_id) {
        $.get('controllers/detail_db.php', { device_id: device_id }, function(data) {
            if (data.canDelete) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You cannot go back!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, ลบเลย!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'delete_device.php?device_id=' + device_id;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cannot delete',
                    text: data.message 
                });
            }
        }, 'json');
    }


    $(document).ready(function() {
    if ($('#allDevicesTable tbody tr').length === 0) {
        $('#paginationalldevice').hide();
    }
});



    function copyCode() {
        const codeBlock = document.getElementById("arduinoCode");
        const textArea = document.createElement("textarea");
        textArea.textContent = codeBlock.textContent;
        document.body.append(textArea);
        textArea.select();
        document.execCommand("copy");
        textArea.remove();
        alert("The code has been successfully copied to the clipboard!");
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        hljs.highlightBlock(document.getElementById("arduinoCode"));

        let codeContent = document.getElementById("arduinoCode").innerHTML;
        codeContent = codeContent.replace(/#define/g, '<span style="color:purple">#define</span>');
        codeContent = codeContent.replace(/(YOUR_USER_TOKEN|YOUR_DEVICENAME|SERVER_URL)/g, '<span style="color:orange">$1</span>');
        document.getElementById("arduinoCode").innerHTML = codeContent;
    });
    </script>
    </body>
</html>