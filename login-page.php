<?php
    require("functions.php");
    if(isset($_POST['submit'])){
        $response = loginUser($_POST['username'], $_POST['password']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <form action="" method="post" autocomplete="off">
        <div class="login-container">
            <h2>Sign in</h2>
            <div class="avatar-img"><img src="./avatar.png" alt="avatar image"></div>
            <h4>Please fill this form to sign in to your account.</h4>

            <div class="input-container">
                <div class="input-box">
                    <label>Username</label>
                    <input type="text" name="username" placeholder=" Enter Username" value="<?php echo @$_POST['username']; ?>">
                </div>
                
                <div class="input-box">
                    <label>Password</label>
                    <input type="password" name="password" placeholder=" Enter Password" value="<?php echo @$_POST['password']; ?>">
                </div>
            </div>

            <button class="submit-btn" type="submit" name="submit">Submit</button>
            
            <div class="info-container">
                <p>
                    Don't have an account?
                    <a href="register.php">Create here</a>
                </p>

                <p>
                    <a href="forgot-password.php">Forgot password?</a>
                </p>
            </div>
            <div class="error-container">
                <p class="info-msg"><?php echo @$response; ?></p>
            </div>
        </div>

    </form>


</body>

</html>