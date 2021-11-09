<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        if($_GET["logout"] == 1)
        {
            //delete session variables and cookies
            session_destroy();
            setcookie("rememberMe", "", time() - 1000);
        }
    }
?>