<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //get user inputs
        $userName = $_POST["username"];
        $email = $_POST["signupEmail"];
        $password = $_POST["password"];
        $cfPassword = $_POST["cfPassword"];
        $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
        $phoneNumber = $_POST["phoneNumber"];
        $description = $_POST["description"];
        //define errors
        $missingUsername = "<p><strong>Please enter your username</strong></p>";
        $missingEmail = "<p><strong>Please enter your email</strong></p>";
        $invalidEmail = "<p><strong>Invalid email</strong></p>";
        $emailExisted = "<p></strong>Email already existed</strong></p>";
        $missingPassword = "<p><strong>Please enter your password</strong></p>";
        $missingCfPassword = "<p><strong>Please enter your password confirmation</strong></p>";
        $invalidPassword = "<p><strong>Your password must contain at least 6 characters, one capital letter, and one number</strong></p>";
        $passwordNotMatch = "<p><strong>Passwords not match</strong></p>";
        $missingGender = "<p><strong>Please choose one of the gender options</strong></p>";
        $missingPhoneNumber = "<p><strong>Please enter your phone number</strong></p>";
        $errors = "";

        //check user inputs
        if(!$userName)
        {
            $errors .= $missingUsername;
        }
        else
        {
            $userName = filter_var($userName, FILTER_SANITIZE_STRING);
        }

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
                //prepare variables for query
                $email = mysqli_real_escape_string($DB, $email);
                $sql = "SELECT * FROM users WHERE email='$email' AND activation='activated'";
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
                else
                {
                    $password = filter_var($password, FILTER_SANITIZE_STRING);
                }
            }
        }

        if(!$gender)
        {
            $errors .= $missingGender;
        }

        if(!$phoneNumber)
        {
            $errors .= $missingPhoneNumber;
        }

        if(!$description)
        {
            $description = "No description";
        }

        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            //prepare variables for query
            $email = mysqli_real_escape_string($DB, $email);
            $password = mysqli_real_escape_string($DB, $password);
            $password = hash("sha256", $password);
            $activation = bin2hex(openssl_random_pseudo_bytes(16));
            $description = mysqli_real_escape_string($DB, $description);
            //execute query
            $sql = "INSERT INTO users (username, gender, telephone, description, email, password, activation) VALUES ('$userName', '$gender', '$phoneNumber', '$description', '$email', '$password', '$activation')";
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