<?php
    include "connection.php";
    $userID = $_SESSION["userID"];
    //create query
    $sql = "SELECT * FROM journey WHERE user_id='$userID'";
    $result = mysqli_query($DB, $sql);
    if(!$result)
    {
        echo "<div class='alert alert-danger'>Unable to execute query. ERROR: " . $DB->error . "</div>";
    }
    else
    {
        $numRows = mysqli_num_rows($result);
        if($numRows == 0)
        {
            echo "<div class='alert alert-warning'>You have not created any trips.</div>";
        }
        else
        {
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {
                $id = $row["id"];
                $origin = $row["origin"];
                $destination = $row["destination"];
                $type = ucfirst($row["type"]);
                $price = $row["price"];
                $seat = $row["seat"];
                $date = $row["date"];
                $time = $row["time"];

                $myTrip = "<div class='row trip'>
                                <div class='col-7'>
                                    <p><strong>Departure:</strong> $origin.</p>
                                    <p><strong>Destination:</strong> $destination.</p>
                                    <p>$date at $time.</p>
                                    <p>$type journey</p>
                                </div>
                                <div class='col-3'>
                                    <h3>$$price</h3>
                                    <p>Per Seat</p>
                                    <p>$seat left</p>
                                </div>
                                <div class='col-2' data-id='$id' data-origin='$origin' data-destination='$destination' data-price='$price' data-seat='$seat' data-tripType='$type' data-date='$date' data-time='$time'>
                                    <button type='button' class='btn btn-success editTrip' data-toggle='modal' data-target='#editTripModal'>Edit</button>
                                </div>
                            </div>";
                echo $myTrip;
            }
        }
    }
?>