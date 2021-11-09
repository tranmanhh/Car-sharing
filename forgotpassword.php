<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $email = $_POST["forgotPasswordEmail"];
        $userID = 0;
        //define errors
        $missingEmail = "<p><strong>Please enter your email</strong></p>";
        $invalidEmail = "<p><strong>Invalid email</strong></p>";
        $emailNotRegistered = "<p><strong>Your email have not been registed. Please sign up.</strong></p>";
        $errors = "";
        //check user inputs
        if(!$email)
        {
            $errors .= $missingEmail;
        }
        else
        {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $errors .= $invalidEmail;
            }
            else
            {
                //check if email registered
                //prepare variables for query
                $email = mysqli_real_escape_string($DB, $email);
                $sql = "SELECT * FROM users WHERE email='$email'";
                $result = mysqli_query($DB, $sql);
                if(!$result)
                {
                    echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
                }
                else
                {
                    $numRows = mysqli_num_rows($result);
                    if($numRows == 0)
                    {
                        $errors .= $emailNotRegistered;
                    }
                    else if($numRows == 1)
                    {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        $userID = $row["user_id"];
                    }
                    else
                    {
                        echo "<div class='alert alert-danger'>Unable to retrieve user data</div>";
                    }
                }
            }
        }

        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            //prepare variable for query
            $key = bin2hex(openssl_random_pseudo_bytes(16));
            //set expired time in 24 hours
            $expiredTime = time() + 24*60*60;
            //create query
            $sql = "INSERT INTO forgotpassword (user_id, validation_key, time, status) VALUES ('$userID', '$key', '$expiredTime', 'pending')";
            //execute query
            if(!mysqli_query($DB, $sql))
            {
                echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
            }
            else
            {
                //send user email to update password
                $message = "Please click on this link to update your password:\r\n";
                $message .= "localhost:3000/updatePassword.php?userID=$userID&key=$key";
                if(!mail($email, "Update Password", $message, "From: abc@gmail.com"))
                {
                    echo "<div class='alert alert-danger'>Unable to send email</div>";
                }
                else
                {
                    echo "<div class='alert alert-success'>Please check your email at <strong>$email</strong> to update your password.</div>";
                }
            }
        }
    }
?>