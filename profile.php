<?php 
    require_once 'controllers/server.php';
    if(!isset($_SESSION['user_id'])){
        header("location: login.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<?php 
    include("navbar.php");
    include("sidebar.php"); 
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    if(empty($row['token'])) {
        $token = bin2hex(random_bytes(16));
       
        $updateStmt = $conn->prepare("UPDATE user SET token = :token WHERE user_id = :user_id");
        $updateStmt->bindParam(":token", $token);
        $updateStmt->bindParam(":user_id", $user_id);
        $updateStmt->execute();
    } else {
        $token = $row['token'];
    }
?>
   <div class="container" id="tambahOutput">
   <h3 class="mb-3">PROFILE</h3>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name:</label>
                    <input type="text" class="form-control" id="firstname" value="<?php echo $row['firstname']; ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lastname" value="<?php echo $row['lastname']; ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" value="<?php echo $row['email']; ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Registration:</label>
                    <input type="email" class="form-control" id="create_at" value="<?php echo $row['create_at']; ?>" disabled>
                </div>

                <div class="mb-3">
    <label for="token" class="form-label">Token: (copy to paste arduino ide)</label>
    <div class="input-group">
        <input type="text" class="form-control" id="token" value="<?php echo $token; ?>" disabled>
        <button class="btn btn-outline-secondary" type="button" onclick="copyTokenToClipboard()">Copy</button>
    </div>
</div>
            </div>
        </div>
    </div>

 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8FqFjcJ6pajs/rfdfs3SO+kD5tr6R7D6ZKrvsQMyFDPgQX" crossorigin="anonymous"></script>
    <script>
    function copyTokenToClipboard() {
        const tokenInput = document.getElementById('token');
   
        const textArea = document.createElement('textarea');
        textArea.value = tokenInput.value;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        
        alert('Token copied to clipboard!');
    }
    
</script>

</body>
</html>
