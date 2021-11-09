<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userID = $_SESSION["userID"];
        $email = $_POST["email"];
        //define errors
        $missingEmail = "<p><strong>Please enter your email</strong></p>";
        $invalidEmail = "<p><strong>Invalid email</strong></p>";
        $emailExisted = "<p><strong>Email existed</strong></p>";
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
                $sql = "SELECT * FROM users WHERE email='$email'";
                $result = mysqli_query($DB, $sql);
                if(!$result)
                {
                    echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
                }
                else
                {
                    $numRows = mysqli_num_rows($result);
                    if($numRows)
                    {
                        $errors .= $emailExisted;
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
            $activation = bin2hex(openssl_random_pseudo_bytes(16));
            $sql = "UPDATE users SET activation = '$activation', email = '$email' WHERE user_id='$userID'";
            if(!mysqli_query($DB, $sql))
            {
                echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
            }
            else
            {
                $message = "Please click on this link to confirm your email:\r\n";
                $message .= "localhost:3000/activate.php?email=" . urlencode($email) . "&key=$activation";
                //send confirmation email
                if(!mail($email, "Registration Confirmation", $message, "From: abc@gmail.com"))
                {
                    echo "<div class='alert alert-danger'>Unable to send confirmation email</div>";
                }
                else
                {
                    echo "<div class='alert alert-success'>Thank you for your sign up. Please check your email at <strong>$email</strong> for confirmation</div>";
                }
            }
        }
    }
?>