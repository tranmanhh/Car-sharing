<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userID = $_SESSION["userID"];
        $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
        //define errors
        $missingGender = "<p><strong>Please choose your gender</strong></p>";
        $errors = "";
        if(!$gender)
        {
            $errors .= $missingGender;
        }

        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            $sql = "UPDATE users SET gender = '$gender' WHERE user_id = '$userID'";
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