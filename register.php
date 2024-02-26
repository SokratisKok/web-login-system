<?php 
   require "functions.php";
   if(isset($_POST['submit'])){
      $response = registerUser($_POST['email'], $_POST['username'], $_POST['password'], $_POST['confirm-password']);
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Register</title>
</head>
<body>
    <form action="" method="post">
        <div class="register-container">
            <h2>Create Account</h2>
            <br>
            <h4>Please fill this form to create a new account.</h4>

            <div class="input-container">
                <div class="input-box">
                    <label>Email</label>
                    <input type="text" name="email" placeholder=" Enter Email" value="<?php echo @$_POST['email']; ?>" >
                </div>
                
                <div class="input-box">
                    <label>Username</label>
                    <input type="text" name="username" placeholder=" Enter Username" value="<?php echo @$_POST['username']; ?>" >
                </div>

                <div class="input-box">
                    <label>Password</label>
                    <input type="text" name="password" placeholder=" Enter Password" value="<?php echo @$_POST['password']; ?>">
                </div>

                <div class="input-box">
                    <label>Confirm Password</label>
                    <input type="text" name="confirm-password" placeholder="Repeat Password" value="<?php echo @$_POST['confirm-password']; ?>">
                </div>
            </div>

            <button class="submit-btn" type="submit" name="submit">Create</button>
            
            
            <div class="info-container">
                <p>
                    <a href="login-page.php">Back to login page</a>
                </p>

            <?php 
                if(@$response == "success"){
                    ?>
                        <p class="success">Your registration was successful</p>
                    <?php
                    header("Location: ../login-page.php");
                }else{
                    ?>
            </div>
                        <div class="error-container">
                            <p class="info-msg"><?php echo @$response; ?></p>
                        </div>
                    <?php
                }
            ?>


        </div>
    </form> <!-- end of register form -->	
</body>
</html>