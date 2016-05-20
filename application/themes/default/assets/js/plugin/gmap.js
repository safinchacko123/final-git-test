
$(document).ready(function() {

    $('.delete_venture').click(function() {
        if ($('.delete_venture').length > 1)
        {
            if (confirm('Are you sure you want to delete this venture?'))
            {
                $.post(site_url + "/secure/delete_venture", {id: $(this).attr('rel')},
                function(data) {
                    $('#venture_' + data).remove();
                });
            }
        }
        else
        {
            alert('error_must_have_address');
        }
    });

    $('.add_store').click(function() {
        var fdata = {
            venture_id: $(this).attr('rel')
        }
        $.ajax({
            url: site_url + '/ajax/addStore',
            type: 'POST',
            data: fdata,
            dataType: 'html',
            async: false,
            error: function() {
                //alert('Server error please try after some time')
            },
            success: function(resp) {                
                $('#venture-form-container').hide('slow');
                $('#venture-map-container').hide('slow');
                
                $('#venture_list').hide();
                $('#venture-store-container').show();                
                $('#venture-store-container').html(resp);
                markPointer(resp);
            }
        });
    });

    $('.edit_venture').click(function() {
        $.post(site_url + '/secure/venture_form/' + $(this).attr('rel'),
                function(data) {
                    $('#venture-form-container').html(data);

                    $('#venture_list').hide();
                    $('#venture-form-container').html(data);
                    $('#venture-form-container').show('slow');
                    $('#venture-form-container').find('#f_country_id').change(function() {
                        $.post(site_url + '/locations/get_zone_menu', {id: $('#f_country_id').val()}, function(data) {
                            $('#f_zone_id').html(data);
                        });
                    });
                    init_event($('#venture-form-container'));
                }
        );
    });

    $(".show_list").click(function() {
        $('#venture_list').show('fast');
        $('#venture-form-container').hide('fast');
    });

    $('#backStep2').click(function() {
        $('#venture-form-container').show('fast');
    })

    $('#btnStep3').click(function() {
        $.post(site_url + '/secure/venture_form/' + $('#f_id').val(), {
            company: $('#f_company').val(),
            firstname: $('#f_firstname').val(),
            lastname: $('#f_lastname').val(),
            email: $('#f_email').val(),
            phone: $('#f_phone').val(),
            address1: $('#f_address1').val(),
            address2: $('#f_address2').val(),
            city: $('#f_city').val(),
            country_id: $('#f_country_id').val(),
            zone_id: $('#f_zone_id').val(),
            zip: $('#f_zip').val(),
            coverage_area: $('#f_coverage').val(),
            lat: $('#f_lat').val(),
            long: $('#f_long').val()
        },
        function(data) {
            if (data == 1)
            {
                window.location = site_url + '/secure/manage_ventures';
            }
            else
            {
                $('#form-error').html(data).show();
            }
        });
    })
})

/**
 * Function to display pointer on Google Map
 * @returns {undefined}
 */
function markPointer(geoAddresses) {
    //var geoAddresses = jQuery.parseJSON($('#geoAddress').val());
    //geoAddresses = jQuery.parseJSON('[{"name":"Deschutes","southwest":{"lat":43.6102319,"lng":-122.0026749},"northeast":{"lat":44.393437,"lng":-119.8963652}},{"name":"Deschutes","southwest":{"lat":23.6102319,"lng":-122.0026749},"northeast":{"lat":24.393437,"lng":-119.8963652}}]');
    function initialize() {
        var mapOptions = {
            zoom: 11,
            center: new google.maps.LatLng(geoAddresses[0].southwest.lat, geoAddresses[0].southwest.lng),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        $("#f_lat").val(geoAddresses[0].southwest.lat);
        $("#f_long").val(geoAddresses[0].southwest.lng);
        var map = new google.maps.Map(document.getElementById('venture-map-container'),
                mapOptions);
        var cityCircle;
        $.each(geoAddresses, function(index, val) {
            var populationOptions = {
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                draggable: true,
                map: map,
                center: new google.maps.LatLng(val.southwest.lat, val.southwest.lng),
                radius: eval($('#f_coverage').val() * 1000)
            };
            // Add the circle for this city to the map.
            cityCircle = new google.maps.Circle(populationOptions);

            var position = new google.maps.LatLng(
                    val.southwest.lat,
                    val.southwest.lng);


            var marker = new google.maps.Marker({
                position: position,
                map: map,
                draggable: true
            });
            marker.bindTo("position", cityCircle, "center");
            google.maps.event.addListener(marker, 'dragend', function()
            {
                var newLat = marker.getPosition().lat();
                var newLng = marker.getPosition().lng();
                $("#f_lat").val(newLat);
                $("#f_long").val(newLng);

            });

        })
    }

    // The five markers show a secret message when clicked
    // but that message is not within the marker's instance data
    function attachSecretMessage(marker, message) {
        var infowindow = new google.maps.InfoWindow({
            content: message
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(marker.get('map'), marker);
        });
    }

    //google.maps.event.addDomListener(window, 'load', initialize);
    initialize();
}

function init_event(source) {

    $(source).find('#btnStep2').click(function() {
        var parent = $(this).parent().parent();
        // Pass address to get geolocation 
        var fdata = {
            address1: $(parent).find('#f_address1').val(),
            city: $(parent).find('#f_city').val(),
            country_id: $(parent).find('#f_country_id').val(),
            country: $(parent).find("#f_country_id :selected").text(),
            zone_id: $(parent).find('#f_zone_id').val(),
            zip: $(parent).find('#f_zip').val()
        }
        $.ajax({
            url: site_url + '/ajax/getGeoLoc',
            type: 'POST',
            data: fdata,
            dataType: 'json',
            async: false,
            error: function() {
                alert('Server error please try after some time')
            },
            success: function(resp) {
                $('#venture-form-container').hide('slow');
                $('#venture-map-container').show('slow');
                markPointer(resp);
            }
        });
    })
}