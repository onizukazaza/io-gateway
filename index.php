<?php 
    require_once 'controllers/server.php';
    if (!isset($_SESSION['user_id'])) {
        // $_SESSION['error'] = 'Please log in!';
        header('location: login.php');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IO Gateway</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <style>
 
  @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap');

        .contain {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         
        }
     .contain h1 {
    font-size:28px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 20px;
    font-family: 'IBM Plex Sans Thai', sans-serif;
    
}
        .highlight {
            color: #007BFF;
            font-weight: bold;
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }


        .con {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .con h1 {
    font-size:28px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 20px;
    font-family: 'IBM Plex Sans Thai', sans-serif;
    
}      
       h2, h3 {
            color: #333;
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }

        .left-align {
    text-align: left;
    position: relative;
    display: inline-block;
}
.left-align::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    height: 5px; 
    width: 100%; 
    background-color: #007BFF; 
    border-radius: 10px;
}
    </style>
</head>
<body>
<?php
      include("navbar.php");
      include("sidebar.php");
  ?>
<div class="container" id="tambahOutput">
    <div class="contain">
        <h1 class="left-align">What is IoT?</h1>
        <br>
        <p><span class="highlight">IoT</span> is an abbreviation for <span class="highlight">"Internet of Things,"</span> meaning <span class="highlight">"the internet of objects"</span> or <span class="highlight">"network of objects."</span> Generally, <span class="highlight">IoT</span> is a concept or technology that connects various devices and objects to the internet, enabling them to communicate and exchange data with each other. It allows for the remote control and monitoring of connected devices via different web applications associated with <span class="highlight">IoT</span>.</p>

<p>Currently, there are web applications available that facilitate the connection and control of <span class="highlight">IoT</span> devices. However, these often have <span class="highlight">limitations</span> in data <span class="highlight">input</span> and <span class="highlight">output</span> from <span class="highlight">IoT</span> devices. As a result, when utilized in real-world applications, they may not fully satisfy the users' needs within the task's scope.</p>
    </div>
    <div class="con">
        <h1 class="left-align">What is IO Gateway?</h1>
        <br>
        <p>The <span class="highlight">IO Gateway</span> is a web application platform for <span class="highlight">IoT</span> created to overcome the limitations of data transmission. The <span class="highlight">IO Gateway</span> serves as a mediator, similar to a data hosting web, collecting data from <span class="highlight">input</span> devices to be used by <span class="highlight">output</span> devices. It also displays various status values of the devices for convenient usage, both on the <span class="highlight">input</span> and <span class="highlight">output</span> sides. With this capability, users can freely send values to control <span class="highlight">IoT</span> devices via the web application, according to the users' objectives.</p>
       
    </div>
 </div>
</body>

</html>