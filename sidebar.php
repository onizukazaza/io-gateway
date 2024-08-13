<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sidebar</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/sidebar_style.css" />
</head>
<body>
<nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
        <div class="menu_title menu_dahsboard"><p class="headitem">Create</p></div>
          <li class="item">
            <a href="device.php" class="nav_link">
              <span class="navlink">Create New Device</span>
            </a>
          </li>
          <li class="item">
            <a href="gpio.php" class="nav_link">
              <span class="navlink">Create New GPIO</span>
            </a>
          </li>
          <li class="item">
            <a href="sensor.php" class="nav_link">
              <span class="navlink">Create New Sensor</span>
            </a>
          </li>
          <li class="item">
            <a href="match.php" class="nav_link">
              <span class="navlink">Create Match Device</span>
            </a>
          </li>

          <hr>
          <div class="menu_title menu_dahsboard"><p class="headitem">Dahsboard</p></div>          
          <li class="item">
            <a href="dashboard_sensor.php" class="nav_link">
              <span class="navlink">Dashboard Sensor</span>
            </a>
          </li>
          <li class="item">
            <a href="dashboard_match.php" class="nav_link">
              <span class="navlink">Dashboard Match</span>
            </a>
          </li>
          <li class="item">
            <a href="carddevice.php" class="nav_link">
              <span class="navlink">Device Config</span>
            </a>
          </li>
        </ul>
        <div class="bottom_content">
          <div class="bottom collapse_sidebar">
            <a href="logout.php" class="logout_link">
            <span> Log Out</span>
            </a>
          </div>
        </div>
      </div>
    </nav>
</body>
</html>