<?php
require_once 'controllers/server.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $check_token = $conn->prepare("SELECT token, status FROM user WHERE token = :token");
    $check_token->bindParam(":token", $token);
    $check_token->execute();
    $token_data = $check_token->fetch(PDO::FETCH_ASSOC);

    if($token_data){
        if($token_data['status'] == 0){
           $click_token = $token_data['token'];
           $update_status = $conn->prepare("UPDATE user SET status = 1 WHERE token = :click_token LIMIT 1");
           $update_status->bindParam(":click_token", $click_token);
           $update_data = $update_status->execute();

           if($update_data){
            $_SESSION['success'] = "Verify email successfully! Place to login";
            header('location:  /io_gateway/login.php');
           } else {
            $_SESSION['error'] = "Failed to update status";
            header('location:  /io_gateway/register.php');
           }
        } else {
            $_SESSION['error'] = "Email already verified";
            header('location:  /io_gateway/login.php');
        }
    } else {
        $_SESSION['error'] = "Invalid token";
        header('location: /io_gateway/register.php');
    }
}
?>
