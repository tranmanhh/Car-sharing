<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //get user inputs
        $email = $_POST["loginEmail"];
        $password = $_POST["loginPassword"];
        //define errors
        $missingEmail = "<p><strong>Please enter your email</strong></p>";
        $invalidEmail = "<p><strong>Invalid email</strong></p>";
        $missingPassword = "<p><strong>Please enter your password</strong></p>";
        $incorrect = "<p><strong>Incorrect email or password. Or, email has not been confirmed.</strong></p>";
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
        }

        if(!$password)
        {
            $errors .= $missingPassword;
        }

        //check if there are any errors
        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            //prepare variable for query
            $email = mysqli_real_escape_string($DB, $email);
            $password = mysqli_real_escape_string($DB, $password);
            $password = hash("sha256", $password);
            //create query
            $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND activation='activated'";
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
                    echo "<div class='alert alert-danger'>$incorrect</div>";
                }
                else
                {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $_SESSION["userID"] = $row["user_id"];
                    $_SESSION["username"] = $row["username"];

                    if(empty($_POST["rememberMe"]))
                    {
                        echo "success";
                    }
                    else
                    {
                        //generate authentificators for remember me
                        $authentificator1 = bin2hex(openssl_random_pseudo_bytes(32));
                        $authentificator2 = openssl_random_pseudo_bytes(32);
                        //concat authentificator and store in a cookie
                        function concatAuthentificator($authen1, $authen2)
                        {
                            return $authen1 . "." . bin2hex($authen2);
                        }
                        //set remeberMe cookie with expired time 15 days
                        $expiredTime = time() + 15*24*60*60;
                        setcookie("rememberMe", concatAuthentificator($authentificator1, $authentificator2), $expiredTime);
                        //encrypted authentificator
                        function encryptedAuthentificator($authentificator)
                        {
                            return hash("sha256", $authentificator);
                        }

                        //prepare variables for query
                        $authentificator1 = mysqli_real_escape_string($DB, $authentificator1);
                        $authentificator2 = mysqli_real_escape_string($DB, $authentificator2);
                        $f2authentificator2 = encryptedAuthentificator($authentificator2);
                        $userID = $row["user_id"];
                        $sql = "INSERT INTO rememberme (authentificator1, f2authentificator2, user_id, expired_time) VALUES ('$authentificator1', '$f2authentificator2', '$userID', '$expiredTime')";
                        //execute query
                        if(!mysqli_query($DB, $sql))
                        {
                            echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
                        }
                        else
                        {
                            echo "success";
                        }
                    }
                }
            }
        }
    }
?>