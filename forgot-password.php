<?php
    require("functions.php");
    if(isset($_POST['submit'])){
        $response = passwordReset($_POST['email']);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <form action="" method="post" autocomplete="off">
        <div class="forgot-pw-container">

            <h2>Password reset</h2>
            <h4>Please enter your email so we can send you a new password.</h4>

            <div class="input-container">
                <div class="input-box">
                    <label>Email</label>
                    <input type="text" name="email" placeholder=" Enter your Email address" value="<?php echo @$_POST['email']; ?>">
                </div>
            </div>
            
            <button class="submit-btn" type="submit" name="submit">Submit</button>
            
            <div class="info-container">
                <p>
                    <a href="login-page.php">Back to login page</a>
                </p>
            <?php
                if(@$response == "success"){
                    ?>
                        <p>Please go to your email account and use your new password.</p>
                    <?php
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

    </form>
</body>
</html>