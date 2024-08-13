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
  <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap');
        .error-message {
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
    <div class="container">
    <div class="logo-container">
        <img src="image/io_logo.png" alt="Your Logo" class="logo" />
    </div>
        <h3 class="mt-4 text-center">Forgot Password</h3>
        <hr>
        <form action="controllers/reset_password_db.php" method="post">
            <?php if(isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger error-message"role="alert">
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
            <button type="submit" name="reset_password" class="btn btn-primary w-100">send</button>
        </form>
    </div>
</body>
</html>