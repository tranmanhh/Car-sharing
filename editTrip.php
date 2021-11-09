<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //define variables
        $userID = $_SESSION["userID"];
        $regularDateArr = [];
        $regularDate = "";
        $date = "";
        $time = "";
        //functions
        function checkRegularDate($key)
        {
            global $regularDateArr;
            if(isset($_POST["$key"]))
            {
                $regularDateArr[] = ucfirst($key);
            }
        }
        //get user inputs
        $tripId = $_POST["id"];
        $origin = $_POST["origin"];
        $destination = $_POST["destination"];
        $price = $_POST["price"];
        $seat = $_POST["seat"];
        $type = isset($_POST["type"]) ? $_POST["type"] : "";
        //define errors
        $missingTripId = "<p><strong>No Trip Id</strong></p>";
        $missingOrigin = "<p><strong>Please enter your departure location</strong></p>";
        $missingDestination ="<p><strong>Please enter your destination</strong></p>";
        $missingPrice = "<p><strong>Please enter your price</strong></p>";
        $missingSeat = "<p><strong>Please enter your available seats</strong></p>";
        $missingType = "<p><strong>Please choose 'Regular' or 'One-off'</strong></p>";
        $missingRegularDate ="<p><strong>Please choose at least one of the days in week</strong></p>";
        $missingRegularTime ="<p><strong>Please enter your time</strong></p>";
        $missingOneOffdate = "<p><strong>Please enter your date</strong></p>";
        $missingOneOfftime = "<p><strong>Please enter your time</strong></p>";
        $errors = "";
        //check user inputs
        if(!$tripId)
        {
            $errors .= $missingTripId;
        }

        if(!$origin)
        {
            $errors .= $missingOrigin;
        }

        if(!$destination)
        {
            $errors .= $missingDestination;
        }

        if(!$price)
        {
            $errors .= $missingPrice;
        }
        
        if(!$seat)
        {
            $errors .= $missingSeat;
        }

        if(!$type)
        {
            $errors .= $missingType;
        }
        else
        {
            if($type == "regular")
            {
                $type = "Regular";
                $regularTime = $_POST["regularTime"];
                //check which day in week was chosen
                checkRegularDate("mon");
                checkRegularDate("tue");
                checkRegularDate("wed");
                checkRegularDate("thu");
                checkRegularDate("fri");
                checkRegularDate("sat");
                checkRegularDate("sun");
                if(empty($regularDateArr))
                {
                    $errors .= $missingRegularDate;
                }
                else
                {
                    $regularDate = $regularDateArr[0];
                    for($i = 1; $i < sizeof($regularDateArr); $i++)
                    {
                        $regularDate .= "-" . $regularDateArr[$i];
                    }
                    $date = $regularDate;
                }
                //check regular time
                if(!$regularTime)
                {
                    $errors .= $missingRegularTime;
                }
                {
                    $time = $regularTime;
                }
            }
            else
            {
                $type = "One-off";
                $oneOffdate = $_POST["oneOffdate"];
                $oneOfftime = $_POST["oneOfftime"];
                //check one-off date
                if(!$oneOffdate)
                {
                    $errors .= $missingOneOffdate;
                }
                else
                {
                    $date = $oneOffdate;
                }
                //check one-off time
                if(!$oneOfftime)
                {
                    $errors .= $missingOneOfftime;
                }
                else
                {
                    $time = $oneOfftime;
                }
            }
        }

        if($errors)
        {
            echo "<div class='alert alert-danger'>$errors</div>";
        }
        else
        {
            //preprare variable for query
            $origin = mysqli_real_escape_string($DB, $origin);
            $destination = mysqli_real_escape_string($DB, $destination);

            //create query
            $sql = "UPDATE journey SET origin='$origin', destination='$destination', type='$type', price='$price', seat='$seat', date='$date', time='$time' WHERE id='$tripId'";
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
?>