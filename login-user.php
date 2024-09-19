<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php require_once 'includes/header.php'; ?>

</head>
<body>
    
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic">
                    <img src="images/Logo.png" alt="IMG">
                </div>


                <form action="login-user.php" class="login100-form validate-form" method="POST">
                    <span class="login100-form-title">
                        Admin Login
                    </span>

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

                   <div class="wrap-input100 validate-input" data-validate = "Username is required">
                        <input class="input100" type="text" name="name" placeholder="Username" value="<?php echo $name ?>">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>
                    <div class="wrap-input100 validate-input" data-validate = "Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <input class="login100-form-btn" type="submit" name="login" value="Login">
                    </div>

                    <div class="text-center p-t-12">
                        <span class="txt1">
                            Forgot
                        </span>
                        <a class="txt2" href="forgot-password.php">
                            Username / Password?
                        </a>
                    </div>

                    <div class="text-center p-t-50 "><a class="txt2"> 
                        Not yet a member? <a href="signup-user.php">Signup <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>

</body>
</html>
