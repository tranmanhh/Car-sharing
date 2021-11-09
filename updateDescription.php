<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $userID = $_SESSION["userID"];
        $description = isset($_POST["description"]) ? $_POST["description"] : "";
        if(!$description)
        {
            echo "success";
        }
        else
        {
            $description = mysqli_real_escape_string($DB, $description);
            $sql = "UPDATE users SET description = '$description' WHERE user_id = '$userID'";
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