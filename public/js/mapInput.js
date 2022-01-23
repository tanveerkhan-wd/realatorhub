function initialize() {

    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    const locationInputs = document.getElementsByClassName("map-input");

    const autocompletes = [];
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {

        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");
        const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';

        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -33.8688;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 151.2195;

        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: {lat: latitude, lng: longitude},
            zoom: 13
        });
        const marker = new google.maps.Marker({
            map: map,
            position: {lat: latitude, lng: longitude},
            draggable:true,
        });

        marker.setVisible(isEdit);

        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({input: input, map: map, marker: marker, autocomplete: autocomplete});
    }

    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();
            console.log(place);
            geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var componentForm = {
                        street_number: 'short_name',
                        route: 'long_name',
                        postal_town: 'long_name',
                        locality: 'long_name',
                        administrative_area_level_1: 'long_name',
                        administrative_area_level_2: 'short_name',
                        country: 'long_name',
                        postal_code: 'short_name'
                    };
                    var google_zipcode='';
                    var google_city='';
                    var google_country='';
                    var state_long_name='';
                    for (var i = 0; i < place.address_components.length; i++) {
                        //console.log(place.address_components);
                        var addressType = place.address_components[i].types[0];
                        //console.log(place.address_components);
                        if (componentForm[addressType]) {
                            var val = place.address_components[i][componentForm[addressType]];
                            if(addressType == 'postal_code'){
                                google_zipcode = place.address_components[i]['short_name'];
                            }
                            if(addressType == 'administrative_area_level_1'){
                                google_state = val;
                                var state_short_name = place.address_components[i]['short_name'];
                                state_long_name = place.address_components[i]['long_name'];

                            }
                            if(addressType == 'locality'){
                                var val = place.address_components[i]['long_name'];
                                google_city = val.trim();
                            }
                            if(google_city==''){
                                if(addressType == 'postal_town' ){
                                    var val = place.address_components[i]['long_name'];
                                    google_city = val.trim();
                                }
                            }
                            if(addressType == 'country'){
                                google_country = place.address_components[i]['long_name'];
                            }
                        }
                    }
                    //console.log(results[0]);
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    setLocationCoordinates(autocomplete.key, lat, lng,google_zipcode,google_city,state_long_name,google_country);
                }
            });

            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

        });
        google.maps.event.addListener(marker, 'dragend', function(results) 
{   
    geocodePosition(marker.getPosition());
});
    }
}

function setLocationCoordinates(key, lat, lng,zipcode,city,state,country) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    const cityField = document.getElementById(key + "-" + "city");
    const stateField = document.getElementById(key + "-" + "state");
    const countryField = document.getElementById(key + "-" + "country");
    const zipcodeField = document.getElementById(key + "-" + "zipcode");
    latitudeField.value = lat;
    longitudeField.value = lng;
    cityField.value = city;
    stateField.value = state;
    countryField.value = country;
    zipcodeField.value = zipcode;
}


function geocodePosition(pos) 
{
   geocoder = new google.maps.Geocoder();
   geocoder.geocode
    ({
        latLng: pos
    }, 
        function(results, status) 
        {
            if (status == google.maps.GeocoderStatus.OK) 
            {
                //$("#address-input").val(results[0].formatted_address);
                $("#mapErrorMsg").hide(100);
                                var arrAddress = results[0].address_components;
                                var componentForm = {
                        street_number: 'short_name',
                        route: 'long_name',
                        postal_town: 'long_name',
                        locality: 'long_name',
                        administrative_area_level_1: 'long_name',
                        administrative_area_level_2: 'short_name',
                        country: 'long_name',
                        postal_code: 'short_name'
                    };
                    var google_zipcode1='';
                    var google_city1='';
                    var google_country1='';
                    var state_long_name1='';
                    var google_state1='';
                                $.each(arrAddress, function (i, address_components) {
                                    console.log(address_components);
                                    var addressType = address_components.types[0];
            //if (componentForm[addressType]) {
                var val = address_components[componentForm[addressType]];
                if(addressType == 'postal_code'){
                    google_zipcode1 = val;
                }
                if(addressType == 'administrative_area_level_1'){
                    google_state1 = val;
                }
                if(addressType == 'locality' ){
                    var val = address_components['long_name'];
                    google_city1 = val.trim();
                }
                if(google_city1==''){
                    if(addressType == 'postal_town' ){
                        var val = address_components['long_name'];
                        google_city1 = val.trim();
                    }
                }
                if(addressType == 'country'){
                    google_country1 = val;
                }
           
//           $('#address-city').val(google_city1);
//           $('#address-state').val(google_state1);
//           $('#address-country').val(google_country1);
//           $('#address-zipcode').val(google_zipcode1);
           
        });
        const lat1 = results[0].geometry.location.lat();
        const lng1 = results[0].geometry.location.lng();
        $('#address-latitude').val(lat1);
        $('#address-longitude').val(lng1);
        
            } 
            else 
            {
                $("#mapErrorMsg").html('Cannot determine address at this location.'+status).show(100);
            }
        }
    );
}