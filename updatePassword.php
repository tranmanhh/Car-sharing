<?php
    session_start();
    include "connection.php";
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Update Password</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h1>Update password:</h1>
            <div id="updatePasswordMsg"></div>
            <?php
                if(!isset($_GET["userID"]) || !isset($_GET["key"]))
                {
                    echo "<div class='alert alert-danger'>Please click on the correct link.</div>";
                }
                else
                {
                    //check if time is expired
                    $userID = $_GET["userID"];
                    $key = $_GET["key"];
                    $currentTime = time();
                    $sql = "SELECT * FROM forgotpassword WHERE user_id='$userID' AND validation_key='$key' AND status='pending' AND time > '$currentTime'";
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
                            echo "<div class='alert alert-danger'>Link has been used or time expired</div>";
                        }
                        else
                        {
                            echo "<form method='POST' id='updatePasswordForm'>
                                <div class='form-group'>
                                    <label for='updatePassword'>Enter your password:</label>
                                    <input type='password' class='form-control' name='updatePassword' id='updatePassword' placeholder='Password'>
                                    <label for='cfUpdatePassword'>Confirm your password:</label>
                                    <input type='password' class='form-control' name='cfUpdatePassword' id='cfUpdatePassword' placeholder='Password confirmation'>
                                    <input type='hidden' name='userID' id='userID' value='" . $userID . "'>
                                    <input type='hidden' name='key' id='key' value='" . $key . "'>
                                </div>
                                <input type='submit' class='btn btn-success' value='Submit'>
                            </form>";
                        }
                    }
                }
            ?>
        </div>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqggUyLDlLQC6KDFquz6Zz_q6rd8rOKIc&libraries=places"></script>
        <script>
            $("#updatePasswordForm").submit(function(event){
                event.preventDefault();
                var userInputs = $(this).serializeArray();
                $.ajax({
                    url: "updatePasswordProcess.php",
                    type: "POST",
                    data: userInputs,
                    success: function(message){
                        if(message)
                        {
                            $("#updatePasswordMsg").html(message);
                        }
                    },
                    error: function(){
                        $("#updatePasswordMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
                    }
                });
            });
        </script>
    </body>
</html>