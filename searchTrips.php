<?php
    session_start();
    include "connection.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //define variables
        $journeyInfo = "";
        //get user inputs
        $origin = $_POST["origin"];
        $destination = $_POST["destination"];
        $originLatMax = $_POST["originLatMax"];
        $originLatMin = $_POST["originLatMin"];
        $originLngMax = $_POST["originLngMax"];
        $originLngMin = $_POST["originLngMin"];
        $destinationLatMax = $_POST["destinationLatMax"];
        $destinationLatMin = $_POST["destinationLatMin"];
        $destinationLngMax = $_POST["destinationLngMax"];
        $destinationLngMin = $_POST["destinationLngMin"];

        //prepare variable for quey
        $origin = mysqli_real_escape_string($DB, $origin);
        $destination = mysqli_real_escape_string($DB, $destination);
        //create query
        $sql = "SELECT * FROM journey WHERE";
        if($originLngMax < 0 && $originLatMin > 0)
        {
            $sql .= " origin_lat BETWEEN $originLatMin AND $originLatMax AND (origin_lng > $originLngMin OR origin_lng < $originLngMax)";
        }
        else
        {
            if($originLngMax > $originLngMin)
            {
                $sql .= " origin_lat BETWEEN $originLatMin AND $originLatMax AND origin_lng BETWEEN $originLngMin AND $originLngMax";
            }
            else
            {
                $sql .= " origin_lat BETWEEN $originLatMin AND $originLatMax AND origin_lng BETWEEN $originLngMax AND $originLngMin";
            }
        }
        
        if($destinationLngMax < 0 && $destinationLngMin > 0)
        {
            $sql .= " AND destination_lat BETWEEN $destinationLatMin AND $destinationLatMax AND (destination_lng > $destinationLngMin OR destination_lng < $destinationLngMax)";
        }
        else
        {
            if($destinationLngMax > $destinationLngMin)
            {
                $sql .= " AND destination_lat BETWEEN $destinationLatMin AND $destinationLatMax AND destination_lng BETWEEN $destinationLngMin AND $destinationLngMax";
            }
            else
            {
                $sql .= " AND destination_lat BETWEEN $destinationLatMin AND $destinationLatMax AND destination_lng BETWEEN $destinationLngMax AND $destinationLngMin";
            }
        }

        $result = mysqli_query($DB, $sql);
        if(!$result)
        {
            echo "<div class='alert alert-danger bg-danger'>error - Unable to execute query. ERROR: " . $DB->error . "</div>";

        }
        else
        {
            $numRows = mysqli_num_rows($result);
            if($numRows == 0)
            {
                echo "<div class='alert alert-danger bg-danger'>error - No journey matches your input</div>";
            }
            else
            {
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    //define journey variables
                    $id = $row["id"];
                    $origin = $row["origin"];
                    $destination = $row["destination"];
                    $type = ucfirst($row["type"]);
                    $price = $row["price"];
                    $seat = $row["seat"];
                    $date = $row["date"];
                    $time = $row["time"];
                    //define driver variables
                    $username = "";
                    $gender = "";
                    $phoneNumber = "";
                    $description ="";
                    $userID = $row["user_id"];
                    //get user information
                    $sql = "SELECT * FROM users WHERE user_id='$userID'";
                    $result2 = mysqli_query($DB, $sql);
                    if(!$result2)
                    {
                        echo "<div class='alert alert-danger bg-danger'>error - Cannot retrieve drivers information</div>";
                    }
                    else
                    {
                        $numRows = mysqli_num_rows($result2);
                        if($numRows != 1)
                        {
                            echo "<div class='alert alert-danger bg-danger'>error - Errors in driver data</div>";
                        }
                        else
                        {
                            $row2 = mysqli_fetch_array($result2);
                            $username = $row2["username"];
                            $gender = $row2["gender"];
                            $phoneNumber = $row2["telephone"];
                            $description = $row2["description"];
                        }
                    }
                    $journeyInfo .= "<div class='row journey'>
                                        <div class='col-3'>$username</div>
                                        <div class='col-6'>
                                            <p><strong>Departure:</strong> $origin</p>
                                            <p><strong>Destination:</strong> $destination</p>
                                            <p>$date at $time</p>
                                            <p>$type journey</p>
                                        </div>
                                        <div class='col-3'>
                                            <h3>$$price</h3>
                                            <p>Per Seat</p>
                                            <p>$seat left</p>
                                        </div>
                                    </div>";
                    if(!empty($_SESSION["userID"]))
                    {
                        //get driver information
                        $journeyInfo .= "<div class='driverInfo'>
                                        <div class='info'>
                                            <p>Gender: $gender</p>
                                            <p>Tel: $phoneNumber</p>
                                        </div>
                                        <p class='description'>$description</p>
                                    </div>";
                    }
                    else
                    {
                        $journeyInfo .= "<div class='driverInfo'>
                                        <div class='info'>
                                            <p>Please log in or sign up to see driver's information</p>
                                        </div>
                                    </div>";
                    }
                }
                echo $journeyInfo;
            }
        }
    }
?>