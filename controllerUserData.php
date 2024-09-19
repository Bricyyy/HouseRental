<?php 
session_start();
require "db_conn.php";
$email = "";
$name = "";
$errors = array();

// if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    
    // Check if name already exists in the database
    $name_check_query = "SELECT * FROM usertable WHERE name = '$name' LIMIT 1";
    $name_check_result = mysqli_query($conn, $name_check_query);
    $name_exists = mysqli_fetch_assoc($name_check_result);
    
    if($name_exists){
        $errors['name'] = "Name already exists. Please choose a different name.";
    }
    
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }
    
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO usertable (name, email, password, code, status)
                        values('$name', '$email', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($conn, $insert_data);
        if($data_check){
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From: trialkoito1@gmail.com"; 
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into the database!";
        }
    }
}

//if user otp click verification code submit button
if(isset($_POST['check'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($conn, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($conn, $update_otp);
        if($update_res){
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header('location: login-user.php');
            exit();
        }else{
            $errors['otp-error'] = "Failed while updating code!";
        }
    }else{
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

// if user clicks the login button
if(isset($_POST['login'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $check_name = "SELECT * FROM usertable WHERE BINARY name = '$name'"; //The BINARY keyword ensures that the comparison is done with exact character matching, considering the case of the letters.
    $res = mysqli_query($conn, $check_name);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $fetch['email']; // To set the email in the session
            $status = $fetch['status'];
            if($status == 'verified'){
                $_SESSION['password'] = $password;
                header('location: dashboard.php'); // Redirect to dashboard
                exit();
            }else{
                $info = "It looks like you haven't verified your email yet - $name";
                $_SESSION['info'] = $info;
                header('location: user-otp.php');
                exit();
            }
        }else{
            $errors['name'] = "Incorrect username or password!";
        }
    }else{
        $errors['name'] = "It looks like you're not yet a member! Click on the link below to sign up.";
    }
}




// if user clicks the continue button in the forgot password form
if(isset($_POST['check-email'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    
    // Check if the provided name exists in the database
    $check_user = "SELECT * FROM usertable WHERE name='$name'";
    $run_query = mysqli_query($conn, $check_user);
    
    if(mysqli_num_rows($run_query) > 0){
        $row = mysqli_fetch_assoc($run_query);
        $email = $row['email'];
        
        $code = rand(999999, 111111);
        $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
        $run_update = mysqli_query($conn, $insert_code);
        
        if($run_update){
            $subject = "Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: trialkoito1@gmail.com";
            
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a password reset OTP to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Something went wrong!";
        }
    }else{
        $errors['name'] = "This name does not exist!";
    }
}

//if user click check reset otp button
if(isset($_POST['check-reset-otp'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($conn, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;
        header('location: new-password.php');
        exit();
    }else{
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click change password button
if(isset($_POST['change-password'])){
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }else{
        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($conn, $update_pass);
        if($run_query){
            $_SESSION['change-password'] = true; // Set session variable to indicate successful password change
            
        }else{
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}


// //if login now button click
// if(isset($_POST['login-now'])){
//     header('Location: login-user.php');
// }
?>
