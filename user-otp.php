<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login-user.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once 'includes/header.php'; ?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login50">  

                <form action="user-otp.php" method="POST" autocomplete="" class="login100-form validate-form">
                    <span class="login100-form-title">Code Verification</span>

                    <div class="wrap-input100 validate-input" data-validate="Please enter your verification code">
                        <input class="input100" type="number" name="otp" placeholder="Enter verification code" required>
                        <span class="symbol-input100">
                            <i class="fa fa-key"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <input class="login100-form-btn" type="submit" name="check" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    if(isset($_SESSION['check'])){
        ?>
        <script>
        swal({
            title: "Success!",
            text: "You can now Login!",
            icon: "success",
            button: "OK",
        }).then(function() {
            window.location.href = "login-user.php";
        });
        </script>
        <?php
        unset($_SESSION['check']); // Unset the session variable after displaying the alert
    }
    ?>
    
</body>
</html>

