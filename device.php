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
    <title>Create Device</title>
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
?>
<div class="container" id="tambahOutput">
  <h3 class="mt-4">CREATE NEW DEVICE</h3>
  <hr>
  <form action="controllers/device_db.php" method="post">
   
  <?php if(isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger error-message" role="alert">
      <?php 
          echo $_SESSION['error'];
          unset($_SESSION['error']);
      ?>
    </div>
  <?php } ?> 
  <?php if(isset($_SESSION['error_message'])) { ?>
    <div class="alert alert-danger error-message" role="alert">
      <?php 
          echo $_SESSION['error_message'];
          unset($_SESSION['error_message']);
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
        <label for="projectname" class="form-label">Device Name</label>
        <input type="text" class="form-control" name="projectname" aria-describedby="projectname">
    </div>
    <div class="mb-3">
        <label for="board" class="form-label">Board</label>
        <div class="dropdown">
                <select class="form-select" name="board" onchange="showDropInfo()">
                    <option disabled selected>Select a Board</option>    
                    <option value="ESP8266">ESP8266</option>
                    <option value="ESP32">ESP32</option>
                </select>      
        </div>
    </div>
 
    <button type="submit" name="create" class="btn btn-primary">Done</button>
  </form>
</div>

    <script src="script.js"></script>
</body>
</html>
