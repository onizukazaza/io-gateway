<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
          @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
      html, body {
    height: 100%;
    margin: 0;
    font-family: "Poppins", sans-serif;
    background-color: #e9f2fc;
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    max-width: 550px;
    width: 560px;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    background-color: #fff;
    position: relative;
}

.logo-container {
    position: absolute; 
    top: -75px;
    left: 50%; 
    transform: translateX(-50%); 
    z-index: 10; 
}

.logo {
    width: 180px; 
    height: auto;
    padding: 3px 42px;
}



        .btn-primary {
            background-color: #0066cc;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
     
        hr {
            border: 0;
            height: 1px;
            background: #333;
            background-image: linear-gradient(to right, #ccc, #333, #ccc);
            margin: 20px 0;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .already-member {
    text-align: center;
}

.action-link {
    color: #0066cc;
    font-weight: 500;
    transition: color 0.3s ease-in-out;
    text-decoration: none; 
}

.action-link:hover {
    color: #0056b3;
    text-decoration: none; 
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.2); 
}

    </style>
</head>
<body>

    <div class="container">
    <div class="logo-container">
        <img src="image/io_logo.png" alt="Your Logo" class="logo" />
    </div>
    <h3 class="mt-4 text-center">Register</h3>
        <hr>
        <form action="controllers/register_db.php" method="post">
<?php if(isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>
            <?php if(isset($_SESSION['warning'])) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php 
                        echo $_SESSION['warning'];
                        unset($_SESSION['warning']);
                    ?>
                </div>
            <?php } ?>

            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstname">
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lastname">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label for="c_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="c_password">
            </div>
            <button type="submit" name="register" class="btn btn-primary">Sign Up</button>
        </form>
        <hr>
        
        <p class="already-member">Go back to the login <a href="login.php" class="action-link">Login here</a></p>

</div>

</body>
</html>

