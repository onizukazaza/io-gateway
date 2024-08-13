<?php
require_once 'controllers/server.php';

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = 'Please log in!';
        header('location: login.php');
    }

$devices_per_page = 5;
$current_page = max(1, isset($_GET['page']) ? $_GET['page'] : 1);
$offset = ($current_page - 1) * $devices_per_page;

$getMatchedDevices = $conn->prepare("
    SELECT
        m.match_id,
        CASE 
            WHEN mt1.detail_id < mt2.detail_id THEN d1.device_name
            ELSE d2.device_name
        END AS device1_name,
        CASE 
            WHEN mt1.detail_id < mt2.detail_id THEN d2.device_name
            ELSE d1.device_name
        END AS device2_name,
        CASE 
            WHEN mt1.detail_id < mt2.detail_id THEN g1.gpio_pin
            ELSE g2.gpio_pin
        END AS gpio1_pin,
        CASE 
            WHEN mt1.detail_id < mt2.detail_id THEN g2.gpio_pin
            ELSE g1.gpio_pin
        END AS gpio2_pin,
        CASE 
            WHEN d1.device_id < d2.device_id THEN t1.typename
            ELSE t2.typename
        END AS type1,
        CASE 
            WHEN d1.device_id < d2.device_id THEN t2.typename
            ELSE t1.typename
        END AS type2,
        CASE 
            WHEN d1.device_id < d2.device_id THEN mt1.status_pin
            ELSE mt2.status_pin
        END AS status1,
        CASE 
            WHEN d1.device_id < d2.device_id THEN mt2.status_pin
            ELSE mt1.status_pin
        END AS status2,
        COALESCE(mv.value, 'No data') AS value
        FROM
        `match` m 
        JOIN 
        detail mt1 ON m.match_id = mt1.match_id
    JOIN 
        detail mt2 ON m.match_id = mt2.match_id AND mt1.detail_id < mt2.detail_id
    JOIN 
        gpio g1 ON mt1.gpio_id = g1.gpio_id
    JOIN 
        gpio g2 ON mt2.gpio_id = g2.gpio_id
    
    JOIN 
        device d1 ON g1.device_id = d1.device_id 
    JOIN 
        device d2 ON g2.device_id = d2.device_id
    JOIN
        type t1 ON g1.type_id = t1.type_id
    JOIN
        type t2 ON g2.type_id = t2.type_id
    LEFT JOIN 
        match_value mv ON mt1.match_id = mv.match_id
        WHERE
        d1.user_id = :user_id
    ORDER BY
        m.create_at ASC, mt1.detail_id ASC, mt2.detail_id ASC
    LIMIT :limit OFFSET :offset
");

$getMatchedDevices->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$getMatchedDevices->bindParam(':offset', $offset, PDO::PARAM_INT);
$getMatchedDevices->bindParam(':limit', $devices_per_page, PDO::PARAM_INT);
$getMatchedDevices->execute();
$matchedDevices = $getMatchedDevices->fetchAll(PDO::FETCH_ASSOC);

$query_total = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM
    `match` m 
JOIN 
    detail mt1 ON m.match_id = mt1.match_id
JOIN 
    detail mt2 ON m.match_id = mt2.match_id
JOIN 
    gpio g1 ON mt1.gpio_id = g1.gpio_id AND g1.type_id = 1
JOIN 
    gpio g2 ON mt2.gpio_id = g2.gpio_id AND g2.type_id = 2
JOIN 
    device d1 ON g1.device_id = d1.device_id 
JOIN 
    device d2 ON g2.device_id = d2.device_id
JOIN
    type t1 ON g1.type_id = t1.type_id
JOIN
    type t2 ON g2.type_id = t2.type_id
LEFT JOIN 
    match_value mv ON mt1.match_id = mv.match_id
WHERE
    d1.user_id = :user_id
");
$query_total->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$query_total->execute();
$total_devices = $query_total->fetchColumn();
$total_pages = ceil($total_devices / $devices_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Match</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<link rel="stylesheet" href="css/style.css">
  
<style>
  
  .no-data {
        color: red;
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .has-data {
        color: green;
        font-family: Verdana, sans-serif;
        font-size: 16px;
    }

</style>

     <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this match?");
        }
    </script>
</head>
<body>
    <?php
        include("navbar.php");
        include("sidebar.php");
    ?>
<div class="container" id="tambahOutput">
<h3 class="mb-3">DASHBOARD MATCH</h3>
<button id="toggleButton"><i class="fas fa-question-circle"></i> Description</button>
<div id="displayBoxContainer">
    <?php require 'displaybox-1.php'; ?>
</div>
<hr>
<table class="table table-bordered table-striped enhanced-table" id="matchedDevicesTable" style="width: 1100px;">

    <thead class="thead-dark">
        <tr>
           
            <th>Device 1</th>
            <th>GPIO Pin</th>
            <th>Type</th>
            <th>Status</th>
            <th>Device 2</th>
            <th>GPIO Pin</th>
            <th>Type</th>
            <th>Status</th>
            <th>Value</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
foreach ($matchedDevices as $match) {
    $state1_class = ($match['status1'] == 'Online') ? 'status-online' : 'status-offline';
    $state2_class = ($match['status2'] == 'Online') ? 'status-online' : 'status-offline';
    echo '
    <tr data-match-id="' . $match['match_id'] . '">
        <td>' . $match['device1_name'] . '</td>
        <td>' . $match['gpio1_pin'] . '</td>
        <td>' . $match['type1'] . '</td>  
        <td><span class="' . $state1_class . '">' . $match['status1'] . '</span></td>
        <td>' . $match['device2_name'] . '</td>
        <td>' . $match['gpio2_pin'] . '</td>
        <td>' . $match['type2'] . '</td>  
        <td><span class="' . $state2_class . '">' . $match['status2'] . '</span></td>  
        <td class="status" style="font-weight: bold;">' . $match['value'] . '</td>
        <td><button onclick="confirmDelete(' . $match['match_id'] . ')" class="btn btn-danger">Delete</button></td>
    </tr>';
}

?>
</tbody>
</table>
<div class="pagination" id="paginationmatch">
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
    function confirmDelete(match_id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure you want to delete the pin pairing of this device?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_match.php?match_id=' + match_id;
            }
        });
    }

    $(document).ready(function() {
    if ($('#matchedDevicesTable tbody tr').length === 0) {
        $('#paginationmatch').hide();
    }
});


    function checkStatus() {
        $.ajax({
            url: 'fetch_status.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data && !data.error) {
                    data.forEach(function(match) {
                        const row = document.querySelector(`[data-match-id="${match.match_id}"]`);
                        if (row) {
                            row.querySelector('.status').textContent = match.value;
                        }
                    });
                }
            }
        });
    }

$(document).ready(function() {
        checkStatus();
        setInterval(checkStatus, 5000);  
    });

</script>
</body>
</html>