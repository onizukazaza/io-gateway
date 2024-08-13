<?php
require_once 'controllers/server.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Please log in!';
    header('location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices Config</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/carddevice.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <?php
        include("navbar.php");
        include("sidebar.php");

        $cards_per_page = 10;
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($current_page - 1) * $cards_per_page;

        
  
        $query_total = $conn->prepare("SELECT COUNT(*) as total FROM device WHERE user_id = :user_id");
        $query_total->bindParam(":user_id", $_SESSION['user_id']);
        $query_total->execute();
        $total_cards = $query_total->fetchColumn();
        
        $total_pages = ceil($total_cards / $cards_per_page);

        $check_data = $conn->prepare("SELECT device_id, device_name FROM device WHERE user_id = :user_id LIMIT $cards_per_page OFFSET $offset");
        $check_data->bindParam(":user_id", $_SESSION['user_id']);
        $check_data->execute();
        $devices = $check_data->fetchAll(PDO::FETCH_ASSOC);
    ?>


<div class="container" id="tambahOutput">
        <h3 class="mb-3">DEVICES CONFIG</h3>
        <hr>
        <div class="mb-3">
            <div class="card-container"  id="allDevicesTable">
                <?php if(count($devices) > 0): ?>
                    <?php foreach ($devices as $device): ?>
                        <div class="card">
                            <a href="detail_device.php?device_id=<?php echo $device['device_id']; ?>"><img src="image/esp8266.jpg" alt="รูปภาพ" class="card-image"></a>
                            <div class="card-content">
                                <h2>Device: <?php echo $device['device_name']; ?></h2>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h2 class="">DEVICE Found: Please add a new device to get started.</h2>
                <?php endif; ?>
            </div>
        </div>
        <div class="pagination" id="paginationalldevice">
    <a href="?page=1" class="<?= ($current_page == 1) ? 'disabled' : ''; ?>">First</a>
    <a href="?page=<?= max(1, $current_page - 1); ?>" class="<?= ($current_page == 1) ? 'disabled' : ''; ?>">Prev</a>
 
     <a href="?page=<?= max(1, $current_page - 1); ?>" class="<?= ($current_page == 1) ? 'disabled' : ''; ?>">
        <i class="fas fa-arrow-left"></i> Prev
    </a>
    
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i; ?>" class="<?= ($i == $current_page) ? 'active' : ''; ?>"><?= $i; ?></a>
    <?php endfor; ?>
    
    <a href="?page=<?= min($total_pages, $current_page + 1); ?>" class="<?= ($current_page == $total_pages) ? 'disabled' : ''; ?>">Next</a>

        <a href="?page=<?= min($total_pages, $current_page + 1); ?>" class="<?= ($current_page == $total_pages) ? 'disabled' : ''; ?>">
        Next <i class="fas fa-arrow-right"></i>
    </a>

    <a href="?page=<?= $total_pages; ?>" class="<?= ($current_page == $total_pages) ? 'disabled' : ''; ?>">Last</a>
</div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>  
 $(document).ready(function() {
    if ($('.card-container .card').length === 0) {
        $('#paginationalldevice').hide();
    }
});
</script>
</body>
</html>
