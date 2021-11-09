<?php
    session_start();
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>My Trips</title>
        <link rel="stylesheet" href="styling/myTrips.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <nav role="navigation" class="navbar navbar-expand-md navbar-dark">
            <a href="#" class="navbar-brand">Car Sharing</a>
            <button type="button" class="navbar-toggler" data-target="#navbarContents" data-toggle="collapse">
                <span class="sr-only">navbar toggler icon</span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarContents">
                <ul class="nav navbar-nav">
                    <li class="nav-item"><a href="mainpageLoggedIn.php" class="nav-link">Search</a></li>
                    <li class="nav-item"><a href="profilePage.php" class='nav-link'>Profile</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Help</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
                    <li class="nav-item active"><a href="myTrips.php" class="nav-link">My Trips</a></li>
                </ul>
                <ul class="nav navbar-nav ml-auto user">
                    <li class="nav-item"><img src="styling/car-profile.jpg"></li>
                    <li class="nav-item"><a href="#" class="nav-link"><?php echo $_SESSION["username"]; ?></a></li>
                </ul>
                <button type="button" class="btn btn-info" id="logout">Log out</button>
            </div>
        </nav>

        <div class="container-fluid" id="tripInfo">
            <button type="button" class="btn btn-success" id="addTrips" data-toggle="modal" data-target="#addTripModal">Add trips</button>
            <div id="myTrips">
                <?php
                    include "loadTrips.php";
                ?>
            </div>
        </div>

        <form method="POST" id="addTripForm">
            <div class="modal" id="addTripModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>New trip:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="map"></div>
                            <div id="addTripMsg"></div>
                            <div class="form-group">
                                <label for="addOrigin" class="sr-only">Departure:</label>
                                <input type="text" class="form-control" name="origin" id="addOrigin" placeholder="Departure" maxlength="100">
                                <label for="addDestination" class="sr-only">Destination:</label>
                                <input type="text" class="form-control mt-2" name="destination" id="addDestination" placeholder="Destination" maxlength="100">
                                <label for="price" class="sr-only">Price:</label>
                                <input type="number" class="form-control mt-2" name="price" id="addPrice" placeholder="Price">
                                <label for="seat" class="sr-only">Seat:</label>
                                <input type="number" class="form-control mt-2" name="seat" id="addSeat" placeholder="Seat available">
                            </div>
                            <div class="form-group">
                                <input type="radio" name="type" class="regular" value="regular">
                                <label for="regular"> <strong>Regular</strong></label>
                                <input type="radio" class="ml-2 oneOff" name="type" value="oneOff">
                                <label for="oneOff"> <strong>One-off</strong></label>
                            </div>
                            <div class="form-group">
                                <div class="regularOptions">
                                    <input type="checkbox" name="mon">
                                    <label for="mon"> Mon</label>
                                    <input type="checkbox" class="ml-2" name="tue">
                                    <label for="tue"> Tue</label>
                                    <input type="checkbox" class="ml-2" name="wed">
                                    <label for="wed"> Wed</label>
                                    <input type="checkbox" class="ml-2" name="thu">
                                    <label for="thu"> Thu</label>
                                    <input type="checkbox" class="ml-2" name="fri">
                                    <label for="fri"> Fri</label>
                                    <input type="checkbox" class="ml-2" name="sat">
                                    <label for="sat"> Sat</label>
                                    <input type="checkbox" class="ml-2" name="sun">
                                    <label for="sun"> Sun</label>
                                    <input type="time" class="form-control" name="regularTime" class="time">
                                </div>
                                <div class="oneOffOptions">
                                    <input type='date' class='form-control' name='oneOffdate'>
                                    <input type='time' class='form-control mt-2' name='oneOfftime' class='time'>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" name="createTrip" id="createTrip" value="Create Trip">
                            <button type="button" class="btn btn-default border" name="cancel" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form method="POST" id="editTripForm">
            <div class="modal" id="editTripModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Edit trip:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="editMsg"></div>
                            <div class="form-group">
                                <label for="editOrigin" class="sr-only">Departure:</label>
                                <input type="text" class="form-control" name="origin" id="editOrigin" placeholder="Departure" maxlength="100">
                                <label for="editDestination" class="sr-only">Destination:</label>
                                <input type="text" class="form-control mt-2" name="destination" id="editDestination" placeholder="Destination" maxlength="100">
                                <label for="price" class="sr-only">Price:</label>
                                <input type="number" class="form-control mt-2" name="price" id="editPrice" placeholder="Price">
                                <label for="seat" class="sr-only">Seat:</label>
                                <input type="number" class="form-control mt-2" name="seat" id="editSeat" placeholder="Seat available">
                            </div>
                            <div class="form-group">
                                <input type="radio" name="type" class="regular" id="editRegularRadio" value="regular">
                                <label for="regular"> <strong>Regular</strong></label>
                                <input type="radio" class="ml-2 oneOff" name="type" id="editOneOffRadio" value="oneOff">
                                <label for="oneOff"> <strong>One-off</strong></label>
                            </div>
                            <div class="form-group">
                                <div class="regularOptions">
                                    <input type="checkbox" name="mon" id="editMon">
                                    <label for="mon"> Mon</label>
                                    <input type="checkbox" class="ml-2" name="tue" id="editTue">
                                    <label for="tue"> Tue</label>
                                    <input type="checkbox" class="ml-2" name="wed" id="editWed">
                                    <label for="wed"> Wed</label>
                                    <input type="checkbox" class="ml-2" name="thu" id="editThu">
                                    <label for="thu"> Thu</label>
                                    <input type="checkbox" class="ml-2" name="fri" id="editFri">
                                    <label for="fri"> Fri</label>
                                    <input type="checkbox" class="ml-2" name="sat" id="editSat">
                                    <label for="sat"> Sat</label>
                                    <input type="checkbox" class="ml-2" name="sun" id="editSun">
                                    <label for="sun"> Sun</label>
                                    <input type="time" class="form-control" name="regularTime" class="time" id="editRegularTime">
                                </div>
                                <div class="oneOffOptions">
                                    <input type='date' class='form-control' id="editOneOffDate" name='oneOffdate'>
                                    <input type='time' class='form-control mt-2' name='oneOfftime' class='time' id="editOneOffTime">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" name="edit" id="editTrip" value="Edit Trip">
                            <button type="button" class="btn btn-danger" name="delete" id="delete">Delete</button>
                            <button type="button" class="btn btn-default border" name="cancel" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <footer>
            <div class="container">
                <p>Developed with chong Minh A'nh</p>
            </div>
        </footer>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqggUyLDlLQC6KDFquz6Zz_q6rd8rOKIc&libraries=places"></script>
        <script src="myTrips.js"></script>
    </body>
</html>