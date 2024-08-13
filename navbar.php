<?php 
      require_once 'controllers/server.php';
?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
   
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <title>Navbar</title>
    <link rel="stylesheet" href="css/navbar_style.css" />
  </head>
  <body>
   
    <nav class="navbar">
        <div class="logo_item">
            <i class="bx bx-menu" id="sidebarOpen"></i>
            <img src="image/io_logo.png" alt=""><a href="index.php" style="text-decoration:none"> IO Gateway</i></a>
        </div>

        <div class="navbar_content">
        <?php 
                $user_id = $_SESSION['user_id'];
                $stmt = $conn->query("SELECT * FROM user WHERE user_id = $user_id");
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h1 class="textuser"><a href="profile.php" style="text-decoration:none"> <?php echo $row['firstname'] . ' ' . $row['lastname'] ?></h1></a>
        </div>
    </nav>
 
  </body>
</html>