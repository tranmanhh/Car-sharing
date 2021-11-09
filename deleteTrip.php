<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //get trip id
        $tripId = $_POST["id"];
        //create query
        $sql = "DELETE FROM journey WHERE id='$tripId'";
        if(!mysqli_query($DB, $sql))
        {
            echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
        }
        else
        {
            echo "success";
        }
    }
?>