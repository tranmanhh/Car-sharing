<?php
    include "connection.php";

    if(!empty($_COOKIE["rememberMe"]) && !isset($_SESSION["userID"]))
    {
        $authentificatorArr = explode(".", $_COOKIE["rememberMe"]);
        $authentificator1 = $authentificatorArr[0];
        $authentificator2 = hex2bin($authentificatorArr[1]);
        //prepare variables for query
        $authentificator1 = mysqli_real_escape_string($DB, $authentificator1);
        $authentificator2 = mysqli_real_escape_string($DB, $authentificator2);
        $f2authentificator2 = hash("sha256", $authentificator2);
        //prepare query
        $sql = "SELECT * FROM rememberme WHERE authentificator1='$authentificator1' AND f2authentificator2";
        $result = mysqli_query($DB, $sql);
        if(!$result)
        {
            echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
        }
        else
        {
            $numRows = mysqli_num_rows($result);
            if($numRows == 1)
            {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $expiredTime = $row["expired_time"];
                //check if time expired
                $currentTime = time();
                if($currentTime - $expiredTime < 15)
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

                    $_SESSION["userID"] = $row["user_id"];
                    header("Location: mainpageLoggedin.php");
                }
            }
        }
    }
?>