<?php 
require_once 'server.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function sendEmail($firstname, $lastname, $email, $token) {
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 2;  
        $mail->isSMTP();                                       
        $mail->Host = 'smtp.gmail.com';                 
        $mail->SMTPAuth = true;                               
        $mail->Username = '1621010541163@rmutr.ac.th';                 
        $mail->Password = '20042543';                       
        $mail->SMTPSecure = 'ssl';  
        $mail->Port =  465;    

        $mail->setFrom('1621010541163@rmutr.ac.th', 'IO Gateway');           
        $mail->addAddress($email, $firstname.''.$lastname);       
        $mail->isHTML(true);                                  
        $mail->Subject = 'Welcome to IO Gateway';
        $mail->Body    = "
                            <h2>Your have Registered with IO Gateway</h2>
                            <h5>Verify your email address to Login with the below give link</5>
                            <br><br>
                            <a href='http://192.168.60.80/io_gateway/verify_email.php?token=$token'> Click me </a> 
                         ";
        $mail->send();
        echo "Mail has been sent successfully!";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $c_password = $_POST['c_password'];
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $status = 0;

    if (empty($firstname)) {
        $_SESSION['error'] = 'Please enter your name';
        header("location: /io_gateway/register.php");
    } else if (empty($lastname)) {
        $_SESSION['error'] = 'Please enter your last name';
        header("location: /io_gateway/register.php");
    } else if (empty($email)) {
        $_SESSION['error'] = 'กรุณาใส่อีเมล์ของคุณ';
        header("location: /io_gateway/register.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Please enter your email address';
        header("location: /io_gateway/register.php");
    } else if (empty($password)) {
        $_SESSION['error'] = 'Please enter your password';
        header("location: /io_gateway/register.php");
    } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        $_SESSION['error'] = 'The password must be between 5 and 20 characters long';
        header("location: /io_gateway/register.php");
    } else if (empty($c_password)) {
        $_SESSION['error'] = '"Please confirm your password';
        header("location: /io_gateway/register.php");
    } else if ($password != $c_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("location: /io_gateway/register.php");
    } else {
   
        try {
            $check_email = $conn->prepare("SELECT email FROM user WHERE email = :email");
            $check_email->bindParam(":email", $email);
            $check_email->execute();
            $row = $check_email->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['email'] == $email) { 
                echo "This email is already registered. <a href='login.php'>Click here to login.</a>";
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, email, password, token, status) 
                                        VALUES(:firstname, :lastname, :email, :password, :token, :status)");
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->bindParam(":token", $token);
                $stmt->bindParam(":status", $status);

                $stmt->execute();
                echo "You have successfully registered!";
                
                sendEmail($firstname, $lastname, $email, $token);
                $_SESSION['success'] = "Registration successful! Please verify your identity to log in!";
                header('location:/io_gateway/login.php');
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
