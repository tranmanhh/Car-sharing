<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userID = $_SESSION["userID"];
        $phoneNumber = $_POST["phoneNumber"];
        //define errors
        $missingPhoneNumber = "<p><strong>Please enter your new phone number</strong></p>";
        $errors = "";
        if(!$phoneNumber)
        {
            $errors .= $missingPhoneNumber;
        }

        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            $sql = "UPDATE users SET telephone = '$phoneNumber' WHERE user_id = '$userID'";
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