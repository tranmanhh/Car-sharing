<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userID = $_SESSION["userID"];
        $userName = $_POST["userName"];
        //define errors
        $missingUsername = "<p><strong>Please enter your new username</strong></p>";
        $errors = "";
        if(!$userName)
        {
            $errors .= $missingUsername;
        }

        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            $userName = mysqli_real_escape_string($DB, $userName);
            $sql = "UPDATE users SET username = '$userName' WHERE user_id = '$userID'";
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
?>