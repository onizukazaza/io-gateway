<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/sign.css">
  
</head>
<body>
    <div class="ground">
    <div class="container">
     <div class="logo-container">
        <img src="image/io_logo.png" alt="Your Logo" class="logo" />
      </div>
        <h3 class="mt-4 text-center">Login</h3>
        <hr>
        <form action="controllers/login_db.php" method="post">
            <?php if(isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['success'])) { ?>
                <div class="alert alert-success">
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Sign In</button>
        </form>
        <hr>
        <div class="action-container text-center">
    <p><a href="register.php" class="action-link">Register here</a></p>
    <!-- <p><a href="forgotpassword.php" class="action-link">Forgot Password</a></p> -->
</div>
<div>
</body>
</html>
