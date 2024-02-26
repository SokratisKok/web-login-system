<?php
    require("functions.php");
    if(!isset($_SESSION['user'])){
        header("location: login-page.php");
        exit();
    }

    if(isset($_GET['logout'])){
        logoutUser();
    }

    if(isset($_GET['confirm-account-deletion'])){
        deleteAccount();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <div>

        <a href="?logout">Logout</a>
        <br>
        <?php
            if(isset($_GET['delete-account'])){
                ?>
                    <p>
                        Are you sure you want to delete your account?
                        <a href="?confirm-account-deletion">Delete account</a>
                    </p>
                <?php
            }else{
                ?>
                    <a href="?delete-account">Delete account</a>
                <?php
            }
        ?>

    </div>

</body>
</html>