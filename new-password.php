<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Password</title>
    <?php require_once 'includes/header.php'; ?>
</head>
<body>
    <div class="limiter">
		<div class="container-login100">
            <div class="wrap-login50">


                <form action="new-password.php" method="POST" autocomplete="" class="login100-form validate-form">
                <span class="login100-form-title">
                New Password
				</span>
                    <?php 
                    if(isset($_SESSION['info'])){
                        ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['info']; ?>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if(count($errors) > 0){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="wrap-input100 validate-input" data-validate = "New Password is required">
                        <input class="input100" type="password" name="password" placeholder="Create new password">
                        <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100" type="password" name="cpassword" placeholder="Confirm your password">
                        <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                    </div>

                       <div class="container-login100-form-btn">
                        <input class="login100-form-btn" type="submit" name="change-password" value="Change">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>

<?php
if(isset($_SESSION['change-password'])){
    ?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    swal({
        title: "Success!",
        text: "Your password has been changed!",
        icon: "success",
        button: "OK",
    }).then(function() {
        window.location.href = "login-user.php";
    });
    </script>
    <?php
    unset($_SESSION['change-password']); // Unset the session variable after displaying the alert
}
?>
