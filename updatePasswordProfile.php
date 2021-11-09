<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userID = $_SESSION["userID"];
        $currentPassword = $_POST["currentPassword"];
        $password = $_POST["newPassword"];
        $cfPassword = $_POST["cfPassword"];
        //define errors
        $missingCurrentPassword = "<p><strong>Please enter your current password</strong></p>";
        $missingPassword = "<p><strong>Please enter your new password</strong></p>";
        $missingCfPassword = "<p><strong>Please enter your password confirmation</strong></p>";
        $incorrectPassword = "<p><strong>Incorrect password</strong></p>";
        $passwordNotMatch = "<p><strong>New password and confirmation password not match</strong></p>";
        $invalidPassword = "<p><strong>Your password should contain at least 6 characters, one capital letter, and one number</strong></p>";
        $errors = "";
        if(!$currentPassword)
        {
            $errors .= $missingCurrentPassword;
        }
        else
        {
            $currentPassword = hash("sha256", $currentPassword);
            $sql = "SELECT * FROM users WHERE user_id = '$userID' AND password = '$currentPassword'";
            $result = mysqli_query($DB, $sql);
            if(!$result)
            {
                echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
            }
            else
            {
                $numRows = mysqli_num_rows($result);
                if($numRows != 1)
                {
                    $errors .= $incorrectPassword;
                }
            }
        }

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
            $password = mysqli_real_escape_string($DB, $password);
            $password = hash("sha256", $password);
            $sql = "UPDATE users SET password = '$password' WHERE user_id = '$userID'";
            if(!mysqli_query($DB, $sql))
            {
                echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
            }
            else
            {
                echo "success";
                session_destroy();
                setcookie("rememberMe", "", time() - 1000);
            }
        }
    }
?>