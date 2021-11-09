<?php
    $DB = new mysqli("server name", "username", "password", "database name");
    if($DB->errno > 0)
    {
        die("<div class='alert alert-danger'>ERROR: Unable to connect to database " . $DB->error . "</div>");
    }
?>