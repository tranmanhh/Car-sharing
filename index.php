<?php
    session_start();
    include "connection.php";
    include "rememberme.php";
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Car Sharing</title>
        <link rel="stylesheet" href="styling/indexStyling.css">
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
                    <li class="nav-item"><a href="#" class="nav-link">Help</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
                </ul>
                <form class="form-inline ml-auto">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#loginModal">Login</button>
                </form>
            </div>
        </nav>

        <div class="jumbotron bg-transparent">
            <div class="container">
                <h1>Plan your next trip now!</h1>
                <p>Save Money! Save the Environment!</p>
                <p>You can save up to $3000 a year using car sharing!</p>
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
                <button type="button" class="btn btn-success btn-lg" id="signup-btn" data-toggle="modal" data-target="#signupModal">Sign up-It's free</button>
            </div>
        </div>

        <form method="POST" id="loginForm">
            <div class="modal" id="loginModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Login:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="loginMsg"></div>
                            <div class="form-group">
                                <label for="loginEmail" class="sr-only">Email:</label>
                                <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="Email" maxlength="50">
                                <label for="loginPassword" class="sr-only">Password:</label>
                                <input type="password" class="form-control mt-2" name="loginPassword" id="loginPassword" placeholder="Password" maxlength="50">
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" class="mr-auto" name="rememberMe" id="rememberMe">
                                <label for="rememberMe"> Remember me</label>
                                <a href="#" class="float-right" data-target="#passwordModal" data-toggle="modal" data-dismiss="modal">Forgot Password?</a>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default border mr-auto" data-target="#signupModal" data-toggle="modal" data-dismiss="modal">Register</button>
                            <input type="submit" class="btn btn-success" name="login" id="login" value="Login">
                            <button type="button" class="btn btn-default border" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form method="POST" id="signupForm">
            <div class="modal" id="signupModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Sign up today and Start using our Car Sharing App!</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="signupMsg"></div>
                            <div class="form-group">
                                <h6>Login information:</h6>
                                <label for="username" class="sr-only">Username:</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" maxlength="50">
                                <label for="singupEmail" class="sr-only">Email:</label>
                                <input type="email" class="form-control mt-2" name="signupEmail" id="signupEmail" placeholder="Email Address" maxlength="50">
                                <label for="password" class="sr-only">Password:</label>
                                <input type="password" class="form-control mt-2" name="password" id="password" placeholder="Choose a password" maxlength="50">
                                <label for="cfPassword" class="sr-only">Confirm password:</label>
                                <input type="password" class="form-control mt-2" name="cfPassword" id="cfPassword" placeholder="Confirm password" maxlength="50">
                            </div>
                            <div class="form-group">
                                <h6>Personal information:</h6>                            
                                <input type="radio" name="gender" id="male" value="male">
                                <label for="male"> Male</label>
                                <input type="radio" name="gender" id="female" value="female">
                                <label for="female"> Female</label>
                                <input type="radio" name="gender" id="notSpecified" value="notSpecified">
                                <label for="notSpecified"> Not specified</label>
                                <label for="phoneNumber" class="sr-only">Phone number:</label>
                                <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="Phone number">
                                <textarea class="form-control mt-2" rows="5" name="description" id="description" placeholder="Please introduce yourself in a few sentences. (Optional)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" name="signup" id="signup" value="Sign up">
                            <button type="button" class="btn btn-default border" name="cancel" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form method="POST" id="passwordForm">
            <div class="modal" id="passwordModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Forgot password? Enter your email address:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="passwordMsg"></div>
                            <div class="form-group">
                                <label for="forgotPasswordEmail" class="sr-only">Email:</label>
                                <input type="email" class="form-control" name="forgotPasswordEmail" id="forgotPasswordEmail" placeholder="Email" maxlength="50">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default border mr-auto" data-toggle="modal" data-target="#signupModal" data-dismiss="modal">Register</button>
                            <input type="submit" class="btn btn-success" value="Submit">
                            <button type="button" class="btn btn-default border" data-dismiss="modal">Cancel</button>
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
        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
        <script src="index.js"></script>
    </body>
</html>