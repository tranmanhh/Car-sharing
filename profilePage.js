//update username
$("#updateUsernameForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    $.ajax({
        url: "updateUsername.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message == "success")
            {
                window.location.reload();
            }
            else
            {
                $("#updateUsernameMsg").html(message);
            }
        },
        error: function(){
            $("#updateUsernameMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
        }
    });
});

//update gender
$("#updateGenderForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    $.ajax({
        url: "updateGender.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message == "success")
            {
                window.location.reload();
            }
            else
            {
                $("#updateGenderMsg").html(message);
            }
        },
        error: function(){
            $("#updateGenderMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
        }
    });
});

//update telephone
$("#updateTelephoneForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    $.ajax({
        url: "updateTelephone.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message == "success")
            {
                window.location.reload();
            }
            else
            {
                $("#updateTelephoneMsg").html(message);
            }
        },
        error: function(){
            $("#updateTelephoneMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
        }
    });
});

//update description
$("#updateDescriptionForm").submit(function(event){
    event.preventDefault();
    $.ajax({
        url: "updateDescription.php",
        type: "POST",
        data: [{name: "description", value: $("#newDescription").val()}],
        success: function(message){
            if(message == "success")
            {
                window.location.reload();
            }
            else
            {
                $("#updateDescriptionMsg").html(message);
            }
        },
        error: function(){
            $("#updateDescriptionMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
        }
    });
});

//update email
$("#updateEmailForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    $.ajax({
        url: "updateEmail.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message)
            {
                $("#updateEmailMsg").html(message);
            }
        },
        error: function(){
            $("#updateEmailMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
        }
    });
});

//update email
$("#updatePasswordForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    console.log(userInputs);
    $.ajax({
        url: "updatePasswordProfile.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message == "success")
            {
                window.location = "index.php";
            }
            else
            {
                $("#updatePasswordMsg").html(message);
            }
        },
        error: function(){
            $("#updatePasswordMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
        }
    });
});


//log out
$("#logout").click(function(){
    $.ajax({
        url: "logout.php",
        type: "GET",
        data: {"logout": 1},
        success: function(){
            window.location = "index.php";
        },
        error: function(){
            console.log("Unable to log out");
        }
    });
});