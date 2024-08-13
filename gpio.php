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
    <title>Create GPIO</title>
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
.success-message {
  @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap');
    color: green;
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

    $check_data = $conn->prepare("SELECT device_id, device_name FROM device WHERE user_id = :user_id");
    $check_data->bindParam(":user_id", $user_id);
    $check_data->execute();
    $Result = $check_data->fetchAll(PDO::FETCH_ASSOC); 
?>

<div class="container" id="tambahOutput"> 
  <h3 class="mt-4">CREATE NEW GPIO</h3>
  <hr>
  <form action="controllers/gpio_db.php" method="post">
   
  <?php if(isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger error-message " role="alert">
      <?php 
          echo $_SESSION['error'];
          unset($_SESSION['error']);
      ?>
    </div>
  <?php } ?> 
  <?php if(isset($_SESSION['success_message'])) { ?>
    <div class="alert alert-success success-message" role="alert">
      <?php 
          echo $_SESSION['success_message'];
          unset($_SESSION['success_message']);
      ?>
    </div>
  <?php } ?>


    <div class="mb-3">
       <div class="form-group">
        <label for="board" class="form-label">Device Name</label>
        <select class="form-select" name="device">
            <option value="" disabled selected>Select a board</option>
            <?php foreach ($Result as $row): ?>
                <?php $device_id = $row['device_id'];?>
                <option value="<?php echo $device_id; ?>"><?php echo $row['device_name']; ?></option>
            <?php endforeach; ?>
        </select>
      </div>

    </div>
    <label for="gpio" class="form-label">GPIO</label>
    <div class="mb-3">
        <div class="dropdown">
                <select class="form-select" name="gpio" onchange="showDropInfo()">
                    <option disabled selected>Select a GPIO</option>
                    <option value="D0">Pin 0</option>
                    <option value="D1">Pin 1</option>
                    <option value="D2">Pin 2</option>
                    <option value="D3">Pin 3</option>
                    <option value="D4">Pin 4</option>
                    <option value="D5">Pin 5</option>
                    <option value="D6">Pin 6</option>
                    <option value="D7">Pin 7</option>
                    <option value="D8">Pin 8</option>
                </select>      
        </div>
    </div>
    <label for="type" class="form-label">Type</label>
    <div class="mb-3">
        <div class="dropdown">
                <select name="typed" class="form-select" onchange="showDropInfo()">
                    <option disabled selected>Select a type</option>
                    <option value="1">Input</option>
                    <option value="2">Output</option>
         
                </select>      
        </div>
    </div>   
    <button type="submit" name="create" class="btn btn-primary">Done</button>
  </form>
</div>
</body>
</html>