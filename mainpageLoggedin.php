<?php
    session_start();
    if(!isset($_SESSION["userID"]))
    {
        header("Location: index.php");
        session_destroy();
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Car Sharing</title>
        <link rel="stylesheet" href="styling/mainpageStyling.css">
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
                    <li class="nav-item active"><a href="#" class="nav-link">Search</a></li>
                    <li class="nav-item"><a href="profilePage.php" class="nav-link">Profile</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Help</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
                    <li class="nav-item"><a href="myTrips.php" class="nav-link">My Trips</a></li>
                </ul>
                <ul class="nav navbar-nav ml-auto user">
                    <li class="nav-item"><img src="styling/car-profile.jpg"></li>
                    <li class="nav-item"><a href="#" class="nav-link"><?php echo $_SESSION["username"]; ?></a></li>
                </ul>
                <button type="button" class="btn btn-info" id="logout">Log out</button>
            </div>
        </nav>

        <div class="jumbotron bg-transparent">
            <div class="container">
                <div class="row">
                    <div class=" offset-2 col-3">
                        <input type="text" class="form-control" id="origin" placeholder="Origin">
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control" id="destination" placeholder="Destination">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-success" id="search">Search</button>
                    </div>
                </div>
                <div id="map"></div>
                <div id="mapMsg"></div>
                <div id="mapResult"></div>
                <div id="mapMsg2"></div>
            </div>
        </div>

        <footer>
            <div class="container">
                <p>Developed with chong Minh A'nh</p>
            </div>
        </footer>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
        <script src="mainpageLoggedin.js"></script>
    </body>
</html>