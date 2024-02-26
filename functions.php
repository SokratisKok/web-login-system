<?php

    require "config.php";

    function connect(){
        try{
            //Create an object with database connection
            $mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE); 
            return $mysqli; //If connection is established return the connection object
        }catch (Exception $e){
            // Handle the exception (e.g., display an error message)
            $error_date = date("F j; Y; g:i a");
            $message = "{$error_date} ; {$e->getMessage()} \r\n";
            file_put_contents("./db-log.csv", $message, FILE_APPEND);
            return false; // Return false to indicate failure
        }  
    }

    function registerUser($email, $username, $password, $confirm_password) {
        try{
            // Establish a database connection.
            $mysqli = connect();

            // Trim whitespace from input values.
            $email = trim($email);
            $username = trim($username);
            $password = trim($password);
            $confirm_password = trim($confirm_password);

            // Check if any field is empty.
            $args = func_get_args();
            foreach ($args as $value) {
                if (empty($value)) {
                    // If any field is empty, return an error message.
                    return "All fields are required";
                }
            }

            // Check for disallowed characters (< and >).
            foreach ($args as $value) {
                if (preg_match("/([<|>])/", $value)) {
                    // If disallowed characters are found, 
                    // return an error message.
                    return "< and > characters are not allowed";
                }
            }

            // Validate email format.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // If email is not valid, return an error message.
                return "Email is not valid";
            }

            // Check if the email already exists in the database.
            $stmt = $mysqli->prepare("SELECT user_email FROM users WHERE user_email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            if ($data != NULL) {
                // If email already exists, return an error message.
                return "Email already exists";
            }

            // Check if the username is too long.
            if (strlen($username) > 100) {
                // If username is too long, return an error message.
                return "Username is too long";
            }

            // Check if the username already exists in the database.
            $stmt = $mysqli->prepare("SELECT user_name FROM users WHERE user_name = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            if ($data != NULL) {
                // If username already exists, return an error message.
                return "Username already exists, please use a different username";
            }

            // Check if the password is too long.
            if (strlen($password) > 255) {
                // If password is too long, return an error message.
                return "Password is too long";
            }

            // Check if the passwords match.
            if ($password != $confirm_password) {
                // If passwords don't match, return an error message.
                return "Passwords don't match";
            }

            // Hash the password for security.
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the 'users' table.
            $stmt = $mysqli->prepare("INSERT INTO users (user_name, user_pw, user_email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $email);
            $stmt->execute();

            // Check if the insertion was successful.
            if ($stmt->affected_rows != 1) {
                // If an error occurred during insertion, return an error message.
                return "An error occurred. Please try again";
            } else {
                // If successful, return a success message.
                return "success";
            }
        }catch(Exception $e){
            // Handle the exception (e.g., display an error message)
            $error_date = date("F j; Y; g:i a");
            $message = "{$error_date} ; {$e->getMessage()} \r\n";
            file_put_contents("./db-log.csv", $message, FILE_APPEND);
            return false; // Return false to indicate failure
        }
    }

    function loginUser($username, $password){
        try{
            $mysqli = connect();

            $username = trim($username);
            $password = trim($password);
    
            if($username == "" || $password == ""){
                return "Both fields are required";
            }
    
            $username = htmlspecialchars($username, ENT_QUOTES);
            $password = htmlspecialchars($password, ENT_QUOTES);
    
            $sql = "SELECT user_name, user_pw FROM users WHERE user_name = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
    
            if($data == NULL) {
                return "Wrong username or password";
            }
    
            if(password_verify($password, $data["user_pw"]) == FALSE){
                return "Wrong username or password";
            }else{
                $_SESSION["user"] = $username;
                header("Location: ./account.php");
                exit();
            }
        }catch(Exception $e){
            // Handle the exception (e.g., display an error message)
            $error_date = date("F j; Y; g:i a");
            $message = "{$error_date} ; {$e->getMessage()} \r\n";
            file_put_contents("./db-log.csv", $message, FILE_APPEND);
            return false; // Return false to indicate failure
        }
        
    }

    function logoutUser(){
        try{
            session_destroy();
            header("location: ../login-system/login-page.php");
            exit();
        }catch(Exception $e){
            // Handle the exception (e.g., display an error message)
            $error_date = date("F j; Y; g:i a");
            $message = "{$error_date} ; {$e->getMessage()} \r\n";
            file_put_contents("./db-log.csv", $message, FILE_APPEND);
            return false; // Return false to indicate failure
        }
        
    }

    function passwordReset($email){
        try{
            $mysqli = connect();

            $email = trim($email);
    
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                return "Email is not valid";
            }
    
            $stmt = $mysqli->prepare("SELECT user_email FROM users WHERE user_email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
    
            if($data == NULL){
                return "Email doesn't exist in the database";
            }
    
            $str = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $password_length = 7;
            $shuffled_str = str_shuffle($str);
            $new_pass = substr($shuffled_str, 0, $password_length);
    
            $subject = "Password recovery";
            $body = "You can login with your new password"."\r\n";
            $body .= $new_pass;
    
            #Email headers
            $headers = "MIME-version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: admin@email.com" . "\r\n"; #replace the email address with a real one
    
            $send = mail($email, $subject, $body, $headers);
            if($send == FALSE){
                return "Email not send. Please try again.";
            }else{
                $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
    
                $stmt = $mysqli->prepare("UPDATE users SET user_pw = ? WHERE user_email = ?");
                $stmt->bind_param("ss", $hashed_password, $email);
                $stmt->execute();
    
                if($stmt->affected_rows != 1){
                    return "There was a connection error, please try again.";
                }else{
                    return "success";
                }
            }
        }catch(Exception $e){
            // Handle the exception (e.g., display an error message)
            $error_date = date("F j; Y; g:i a");
            $message = "{$error_date} ; {$e->getMessage()} \r\n";
            file_put_contents("./db-log.csv", $message, FILE_APPEND);
            return false; // Return false to indicate failure
        }

        

    }

    function deleteAccount(){
        try{
            $mysqli = connect();

            $sql = "DELETE FROM users WHERE user_name = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();

            if($stmt->affected_rows != 1){
                throw new AssertionError("Deletion failed: Affected rows are different than one.");
            }
            session_destroy();
            header("location: delete-message.php");
            exit();
        }catch(AssertionError $e){
            // Handle the exception (e.g., display an error message)
            $error_date = date("F j; Y; g:i a");
            $message = "{$error_date} ; {$e->getMessage()} \r\n";
            file_put_contents("./db-log.csv", $message, FILE_APPEND);
            return "An error occured. Please try again";
        }catch(Exception $e){
            // Handle the exception (e.g., display an error message)
            $error_date = date("F j; Y; g:i a");
            $message = "{$error_date} ; {$e->getMessage()} \r\n";
            file_put_contents("./db-log.csv", $message, FILE_APPEND);
            return false; // Return false to indicate failure
        }
    }