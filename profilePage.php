<?php
    session_start();
    include "connection.php";
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
        <link rel="stylesheet" href="styling/profilePageStyling.css">
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
                    <li class="nav-item active"><a href="profilePage.php" class="nav-link">Profile</a></li>
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

        <div class="container">
            <?php
                $userID = $_SESSION["userID"];
                //get user details
                $sql = "SELECT * FROM users WHERE user_id='$userID'";
                $result = mysqli_query($DB, $sql);
                if(!$result)
                {
                    echo "<div class='alert alert-danger'>Unable to execute query. ERROR " . $DB->error . "</div>";
                }
                else
                {
                    $numRows = mysqli_num_rows($result);
                    if($numRows != 1)
                    {
                        echo "<div class='alert alert-danger'>Cannot retrieve user data</div>";
                    }
                    else
                    {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        $userName = $row["username"];
                        $email = $row["email"];
                        $gender = $row["gender"];
                        $phoneNumber = $row["telephone"];
                        $description = $row["description"];

                        $userDetails = "<table class='table table-bordered' id='userData'><tbody>";
                        $userDetails .= "<tr id='username' data-toggle='modal' data-target='#updateUsernameModal'>
                                            <th scope='row'>username</th>
                                            <td>$userName</td>
                                        </tr>";
                        $userDetails .= "<tr id='email' data-toggle='modal' data-target='#updateEmailModal'>
                                            <th scope='row'>email</th>
                                            <td>$email</td>
                                        </tr>";
                        $userDetails .= "<tr id='password' data-toggle='modal' data-target='#updatePasswordModal'>
                                            <th scope='row'>password</th>
                                            <td>**********</td>
                                        </tr>";
                        $userDetails .= "<tr id='gender' data-toggle='modal' data-target='#updateGenderModal'>
                                            <th scope='row'>gender</th>
                                            <td>$gender</td>
                                        </tr>";
                        $userDetails .= "<tr id='phoneNumber' data-toggle='modal' data-target='#updateTelephoneModal'>
                                            <th scope='row'>telephone</th>
                                            <td>$phoneNumber</td>
                                        </tr>";
                        $userDetails .= "<tr id='description' data-toggle='modal' data-target='#updateDescriptionModal'>
                                            <th scope='row'>description</th>
                                            <td>$description</td>
                                        </tr>";
                        $userDetails .= "</tbody></table>";
                        echo $userDetails;
                    }
                }
            ?>
        </div>

        <!--update name modal--> 
        <form method="POST" id="updateUsernameForm">
            <div class="modal" id="updateUsernameModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Update username:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="updateUsernameMsg"></div>
                            <div class="form-group">
                                <label for="userName" class="sr-only">Update username:</label>
                                <input type="text" class="form-control" name="userName" id="userName" placeholder="New username" maxlength="50">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="Submit">
                            <button type="button" class="btn btn-default border" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--update email modal--> 
        <form method="POST" id="updateEmailForm">
            <div class="modal" id="updateEmailModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Update email:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="updateEmailMsg"></div>
                            <div class="form-group">
                                <label for="email" class="sr-only">Update email:</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="New email address" maxlength="50">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="Submit">
                            <button type="button" class="btn btn-default border" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--update password modal--> 
        <form method="POST" id="updatePasswordForm">
            <div class="modal" id="updatePasswordModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Update Password:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="updatePasswordMsg"></div>
                            <div class="form-group">
                            <label for="currentPassword" class="sr-only">Current password:</label>
                                <input type="password" class="form-control" name="currentPassword" id="currentPassword" placeholder="Current Password" maxlength="50">
                                <label for="newPassword" class="sr-only">New password:</label>
                                <input type="password" class="form-control mt-2" name="newPassword" id="newPassword" placeholder="New password" maxlength="50">
                                <label for="cfPassword" class="sr-only">Confirm password:</label>
                                <input type="password" class="form-control mt-2" name="cfPassword" id="cfPassword" placeholder="Password confirmation" maxlength="50">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="Submit">
                            <button type="button" class="btn btn-default border" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--update gender modal--> 
        <form method="POST" id="updateGenderForm">
            <div class="modal" id="updateGenderModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Update Gender:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="updateGenderMsg"></div>
                            <div class="form-group">
                                <input type="radio" name="gender" id="male" value="male">
                                <label for="male"> Male</label>
                                <input type="radio" name="gender" id="female" value="female">
                                <label for="female"> Female</label>
                                <input type="radio" name="gender" id="notSpecified" value="notSpecified">
                                <label for="notSpecified"> Not Specified</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="Submit">
                            <button type="button" class="btn btn-default border" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--update telephone modal--> 
        <form method="POST" id="updateTelephoneForm">
            <div class="modal" id="updateTelephoneModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Update Telephone:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="updateTelephoneMsg"></div>
                            <div class="form-group">
                                <label for="phoneNumber" class="sr-only">Update Telephone:</label>
                                <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" placeholder="New Phone Number">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" value="Submit">
                            <button type="button" class="btn btn-default border" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--update description modal--> 
        <form method="POST" id="updateDescriptionForm">
            <div class="modal" id="updateDescriptionModal" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>Update Description:</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="updateDescriptionMsg"></div>
                            <div class="form-group">
                                <label for="description" class="sr-only">Update description:</label>
                                <textarea rows="5" class="form-control" id="newDescription" placeholder="New Description"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
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
        <script src="profilePage.js"></script>
    </body>
</html>