//set up map
var mapCenter = {
    lat: 51.5,
    lng: -0.1
};
var mapOptions = {
    center: mapCenter,
    zoom: 7,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};
var map = new google.maps.Map(document.getElementById("map"), mapOptions);

//set up autocomplete for add trip modal and edit trip modal
var autocompleteOptions = {
    type: ['(cities)']
};
var autocompleteAddOrigin = new google.maps.places.Autocomplete(document.getElementById("addOrigin"), autocompleteOptions);
var autocompleteAddDestination = new google.maps.places.Autocomplete(document.getElementById("addDestination"), autocompleteOptions);
var autocompleteEditOrigin = new google.maps.places.Autocomplete(document.getElementById("editOrigin"), autocompleteOptions);
var autocompleteEditDestination = new google.maps.places.Autocomplete(document.getElementById("editDestination"), autocompleteOptions);

var directionsService = new google.maps.DirectionsService();
var directionsRenderer = new google.maps.DirectionsRenderer();
directionsRenderer.setMap(map);

//create geoCoder for finding lat and lng
var geoCoder = new google.maps.Geocoder();

//click on Regular type
$(".regular").click(function(){
    $(".regularOptions").show();
    $(".oneOffOptions").hide();
});
//click on One-off type
$(".oneOff").click(function(){
    $(".regularOptions").hide();
    $(".oneOffOptions").show();
});

//define variables
var tripId = 0;

//add trip
$("#addTripForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    //finding the latitude and longitude for origin input
    var request = {
        address: userInputs[0].value
    };
    geoCoder.geocode(request, function(result, status){
        if(status == google.maps.GeocoderStatus.OK)
        {
            var lat = result[0].geometry.location.lat();
            var lng = result[0].geometry.location.lng();
            userInputs.push({name: "originLat", value: lat});
            userInputs.push({name: "originLng", value: lng});
        }
        else
        {
            $("#addTripMsg").html("<div class='alert alert-danger'>Unable to retrieve origin's coordinates.</div>")
        }
    });
    //finding the latitude and longitude for destination input
    request = {
        address: userInputs[1].value
    };
    geoCoder.geocode(request, function(result, status){
        if(status == google.maps.GeocoderStatus.OK)
        {
            var lat = result[0].geometry.location.lat();
            var lng = result[0].geometry.location.lng();
            userInputs.push({name: "destinationLat", value: lat});
            userInputs.push({name: "destinationLng", value: lng});
        }
        else
        {
            $("#addTripMsg").html("<div class='alert alert-danger'>Unable to retrieve destination's coordinates.</div>");
        }
    });

    //calculate distance between two locations
    request = {
        origin: userInputs[0].value,
        destination: userInputs[1].value,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, (result, status) => {
        if(status == google.maps.DirectionsStatus.OK)
        {
            map.setZoom(14);
            directionsRenderer.setDirections(result);
            $.ajax({
                url: "addTrip.php",
                type: "POST",
                data: userInputs,
                success: function(message){
                    if(message == "success")
                    {
                        window.location.reload();
                    }
                    else
                    {
                        $("#addTripMsg").html(message);
                    }
                },
                error: function(){
                    $("#addTripMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
                }
            });
        }
        else
        {
            $("#addTripMsg").html("<div class='alert alert-danger'>Unable to retrieve route between two locations. Please select different locations.</div>");
        }
    });
});

//display trip information when click on edit button
$.ajax({
    url: "loadTrips.php",
    success: function(){
        $(".editTrip").click(function(){
            tripId = $(this).parent().attr("data-id");
            $("#editOrigin").val($(this).parent().attr("data-origin"));
            $("#editDestination").val($(this).parent().attr("data-destination"));
            $("#editPrice").val($(this).parent().attr("data-price"));
            $("#editSeat").val($(this).parent().attr("data-seat"));
            var type = $(this).parent().attr("data-tripType");
            if(type == "Regular")
            {
                $("#editRegularRadio").prop("checked", true);
                $("#editRegularTime").val($(this).parent().attr("data-time"));
                var date = $(this).parent().attr("data-date").split("-");
                date.forEach(day => {
                    $(`#edit${day}`).prop("checked", true);
                });
                $(".regularOptions").show();
                $(".oneOffOptions").hide();
            }
            else
            {
                $("#editOneOffRadio").prop("checked", true);
                $("#editOneOffDate").val($(this).parent().attr("data-date"));
                $("#editOneOffTime").val($(this).parent().attr("data-time"));
                $(".regularOptions").hide();
                $(".oneOffOptions").show();
            }
        });
    },
    error: function(){
        console.log("error to load trips");
    }
});

//edit trip
$("#editTripForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    userInputs.push({name: "id", value: tripId});
    //calculate distance between two locations
    var request = {
        origin: userInputs[0].value,
        destination: userInputs[1].value,
        travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, (result, status) => {
        if(status == google.maps.DirectionsStatus.OK)
        {
            map.setZoom(14);
            directionsRenderer.setDirections(result);
            console.log(result);
            $.ajax({
                url: "editTrip.php",
                type: "POST",
                data: userInputs,
                success: function(message){
                    if(message == "success")
                    {
                        window.location.reload();
                    }
                    else
                    {
                        $("#editMsg").html(message);
                    }
                },
                error: function(){
                    $("#editMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
                }
            });
        }
        else
        {
            $("#editMsg").html("<div class='alert alert-danger'>Unable to retrieve route between two locations. Please select different locations.</div>");
        }
    });
});

//delete trip
$("#delete").click(function(){
    $.ajax({
        url: "deleteTrip.php",
        type: "POST",
        data: {"id": tripId},
        success: function(message){
            if(message == "success")
            {
                window.location.reload();
            }
            else
            {
                $("#editMsg").html(message);
            }
        },
        error: function(){
            $("editMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later.</div>");
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