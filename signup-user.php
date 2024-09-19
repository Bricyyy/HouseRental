<?php require_once "controllerUserData.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php require_once 'includes/header.php'; ?>
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login50">
                
                <form action="signup-user.php" method="POST" autocomplete="" class="login100-form validate-form">
                    <span class="login100-form-title">
                        Registration
                    </span>
                    
                    <?php
                    if(count($errors) == 1){
                        ?>
                        <div class="alert alert-danger text-center">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </div>
                        <?php
                    }elseif(count($errors) > 1){
                        ?>
                        <div class="alert alert-danger">
                            <?php
                            foreach($errors as $showerror){
                                ?>
                                <li><?php echo $showerror; ?></li>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    
                    <div class="wrap-input100 validate-input" data-validate="Name is required">
                        <input class="input100" type="text" name="name" placeholder="Name/Username" pattern="[A-Za-z]{4,15}" title="Name must be between 4 and 15 characters long. No numbers or symbols allowed.">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,20}" title="Password must contain at least one uppercase letter, one lowercase letter, one number, and one symbol. It should be between 6 and 20 characters long.">
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="cpassword" placeholder="Confirm password">
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <!-- Non-editable email input -->
                    <div class="wrap-input100">
                        <input class="input100" type="email" name="email" value="trialkoito1@gmail.com" readonly>
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <input class="login100-form-btn" type="submit" name="signup" value="Signup">
                    </div>

                    <div class="text-center p-t-50">
                        <a class="txt2"> Already a member? <a href="login-user.php">Login here<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i></a></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
</body>
</html>
