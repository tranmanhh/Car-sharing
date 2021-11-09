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

//set up autocomplete
var autocompleteOptions = {
    type: ['(cities)']
};
var autocompleteOrigin = new google.maps.places.Autocomplete(document.getElementById("origin"), autocompleteOptions);
var autocompleteDestination = new google.maps.places.Autocomplete(document.getElementById("destination"), autocompleteOptions);

//set up directions service and directions renderer
var directionsService = new google.maps.DirectionsService();
var directionsRenderer = new google.maps.DirectionsRenderer();
directionsRenderer.setMap(map);

//set up geocoder to find lat and lng
var geoCoder = new google.maps.Geocoder();

$("#search").click(function(){
    //define variable
    var dataToSend = [];
    var origin = $("#origin").val();
    var destination = $("#destination").val();
    if(!origin || !destination)
    {
        $("#mapMsg").html("<div class='alert alert-danger bg-danger'>Missing origin input or destination input!</div>");
    }
    else
    {
        //get origin coordinates and define lat tolerance and lng tolerance
        var request = {
            address: origin
        };
        geoCoder.geocode(request, function(result, status){
            if(status == google.maps.GeocoderStatus.OK)
            {
                var lat = result[0].geometry.location.lat();
                var lng = result[0].geometry.location.lng();
                //find latitude tolerance and longitude tolerance within 10 miles
                var latTolerance = (10*180)/12430;
                var lngTolerance = (10*360)/(24901*Math.cos(lat));
                var latMax = toDegreeLat(lat + latTolerance);
                var latMin = toDegreeLat(lat - latTolerance);
                var lngMax = toDegreeLng(lng + lngTolerance);
                var lngMin = toDegreeLng(lng -  lngTolerance);
                dataToSend.push({name: "origin", value: origin});
                dataToSend.push({name: "originLatMax", value: latMax});
                dataToSend.push({name: "originLatMin", value: latMin});
                dataToSend.push({name: "originLngMax", value: lngMax});
                dataToSend.push({name: "originLngMin", value: lngMin});
            }
            else
            {
                $("#mapMsg2").html("<div class='alert alert-danger'>Unable to retrieve location coordiates</div>");
            }
        });
        //get destination coordinates and define lat tolerance and lng tolerance
        request = {
            address: destination
        };
        geoCoder.geocode(request, function(result, status){
            if(status == google.maps.GeocoderStatus.OK)
            {
                var lat = result[0].geometry.location.lat();
                var lng = result[0].geometry.location.lng();
                //find latitude tolerance and longitude tolerance within 10 miles
                var latTolerance = (10*180)/12430;
                var lngTolerance = (10*360)/(24901*Math.cos(lat));
                var latMax = toDegreeLat(lat + latTolerance);
                var latMin = toDegreeLat(lat - latTolerance);
                var lngMax = toDegreeLng(lng + lngTolerance);
                var lngMin = toDegreeLng(lng -  lngTolerance);
                dataToSend.push({name: "destination", value: destination});
                dataToSend.push({name: "destinationLatMax", value: latMax});
                dataToSend.push({name: "destinationLatMin", value: latMin});
                dataToSend.push({name: "destinationLngMax", value: lngMax});
                dataToSend.push({name: "destinationLngMin", value: lngMin});
            }
            else
            {
                $("#mapMsg2").html("<div class='alert alert-danger'>Unable to retrieve location coordiates</div>");
            }
        });

        //find route from origin to destination and search trips
        request = {
            origin: origin,
            destination: destination,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(result, status){
            if(status == google.maps.DirectionsStatus.OK)
            {
                $("#mapMsg").html(`<div class='alert alert-primary'>From <strong>${request.origin}</strong> to <strong>${request.destination}.<br></strong>Closest Journeys:</div>`);
                directionsRenderer.setDirections(result);
                map.setZoom(15);
                $.ajax({
                    url: "searchTrips.php",
                    type: "POST",
                    data: dataToSend,
                    success: function(message){
                        if(message.includes("error"))
                        {
                            $("#mapResult").html("");
                            $("#mapMsg2").html(message);
                        }
                        else
                        {
                            $("#mapResult").html(message);
                            $("#mapMsg2").html("");
                        }
                        journeyClick();
                    },
                    error: function(){
                        $("mapMsg2").html(message);
                    }
                });
            }
            else
            {
                $("#mapMsg").html("<div class='alert alert-danger bg-danger'>Cannot retrieve route!</div>");
            }
        });
    }
});

//sign up
$("#signupForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    console.log(userInputs);
    $.ajax({
        url: "signup.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message)
            {
                $("#signupMsg").html(message);
            }
        },
        error: function(){
            $("#signupMsg").html("<div class='alert alert-danger'>Cannot execute Ajax call. Please try again later!</div>");
        }
    });
});

//log in
$("#loginForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    $.ajax({
        url: "login.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message == "success")
            {
                window.location = "mainpageLoggedin.php";
            }
            else
            {
                $("#loginMsg").html(message);
            }
        },
        error: function(){
            $("#loginMsg").html("<div class='alert alert-danger'>Cannot execute Ajax call. Please try again later!</div>");
        }
    });
});

//forgot password
$("#passwordForm").submit(function(event){
    event.preventDefault();
    var userInputs = $(this).serializeArray();
    $.ajax({
        url: "forgotpassword.php",
        type: "POST",
        data: userInputs,
        success: function(message){
            if(message)
            {
                $("#passwordMsg").html(message);
            }
        },
        error: function(){
            $("#passwordMsg").html("<div class='alert alert-danger'>Unable to execute Ajax call. Please try again later!</div>");
        }
    });
});

//functions
function journeyClick(){
    $(".journey").click(function(){
        $(this).next().slideToggle();
    });
}

function toDegreeLat(value)
{
    if(value > 90)
    {
        var distance = value - 90;
        value = 90 - distance;
    }
    if(value < -90)
    {
        var value = 90 - value;
        value = value - 90;
    }
    return value;
}

function toDegreeLng(value)
{
    if(value > 180)
    {
        value -= 360;
    }
    if(value < -180)
    {
        value += 360;
    }
    return value;
}