<?php
require_once 'server.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($email, $subject, $message) {
    $mailer = new PHPMailer(true);
    try {
        $mailer->SMTPDebug = 2;  
        $mailer->isSMTP();
        $mailer->Host = 'smtp.gmail.com';  
        $mailer->SMTPAuth = true;
        $mailer->Username = '1621010541163@rmutr.com';  
        $mailer->Password = '20042543';  
        $mailer->SMTPSecure = 'tls';  
        $mailer->Port = 587;  
        
        $mailer->setFrom('iot.io.gateway@gmail.com', 'IO Gateway');  
        $mailer->addAddress($email);  
        $mailer->isHTML(true);
        $mailer->Subject = $subject;  
        $mailer->Body = $message;  

        $mailer->send();
        echo "Email sent successfully!";
    } catch (Exception $e) {
        echo "Unable to send email . Error: {$mailer->ErrorInfo}";
    }
}

if(isset($_POST['reset_password'])){
    $email = $_POST['email'];

    $check_email = $conn->prepare("SELECT email FROM user WHERE email = :email");
    $check_email->bindParam(":email", $email);
    $check_email->execute();
    $row = $check_email->fetch(PDO::FETCH_ASSOC);

    if($row && $row['email'] == $email){
        $reset_link = "http://localhost/io_gateway/update_password.php?email=$email"; 

        $subject = "Reset Password";
        $message = "You can reset your password at this link: $reset_link";

        sendEmail($email, $subject, $message);

        $_SESSION['success'] = "A password reset email has been sent to your email address";
        header('location: /io_gateway/reset_password.php');
    } else {
        $_SESSION['error'] = "Email not found in the system";
        header('location: /io_gateway/reset_password.php');
    }
}
?>