<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userID = $_POST["userID"];
        $key = $_POST["key"];
        $password = $_POST["updatePassword"];
        $cfPassword = $_POST["cfUpdatePassword"];
        //define errors
        $missingPassword = "<p><strong>Please enter your password</strong></p>";
        $missingCfPassword = "<p><strong>Please enter your password confirmation</strong></p>";
        $passwordNotMatch = "<p><strong>Passwords not match</strong></p>";
        $invalidPassword = "<p><strong>Your password should contain at least 6 characters, one capital letter, and one number</strong></p>";
        $errors = "";
        //check user input
        if(!$password)
        {
            $errors .= $missingPassword;
        }
        
        if(!$cfPassword)
        {
            $errors .= $missingCfPassword;
        }

        if($password && $cfPassword)
        {
            if($password != $cfPassword)
            {
                $errors .= $passwordNotMatch;
            }
            else
            {
                if(strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password))
                {
                    $errors .= $invalidPassword;
                }
            }
        }

        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            //prepare variables for query
            $password = mysqli_real_escape_string($DB, $password);
            $password = hash("sha256", $password);
            $sql = "UPDATE users SET password='$password' WHERE user_id='$userID'";
            if(!mysqli_query($DB, $sql))
            {
                echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
            }
            else
            {
                //update status of password update from pending to used
                $sql = "UPDATE forgotpassword SET status='used' WHERE user_id='$userID' AND validation_key='$key'";
                if(!mysqli_query($DB, $sql))
                {
                    echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
                }
                else
                {
                    echo "<div class='alert alert-success'>Password updated. Please click <a href='index.php'>here</a> to return.</div>";
                }
            }
        }
    }
?>