<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require_once 'includes/header.php'; ?>
</head>
<body>
<div class="limiter">
		<div class="container-login100">
            <div class="wrap-login50">


                <form action="forgot-password.php" method="POST" autocomplete="" class="login100-form validate-form">
                <span class="login100-form-title">
                    Forgot Password
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Name is required">
                        <input class="input100" type="text" name="name" placeholder="Name/Username" pattern="[A-Za-z]{4,15}" title="Name must be between 4 and 15 characters long. No numbers or symbols allowed.">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                     <!-- Non-editable email input 
                     <div class="wrap-input100">
                        <input class="input100" type="email" name="email" value="trialkoito1@gmail.com" readonly>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>-->
                    
                    <div class="container-login100-form-btn">
                        <input class="login100-form-btn" type="submit" name="check-email" value="Continue">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>