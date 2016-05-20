
$(document).ready(function() {
    $('#address-map-container').hide();
    $('#address-map-container').show();

    $('.delete_address').click(function() {
        if ($('.delete_address').length > 1)
        {
            if (confirm('Are you sure you want to delete this address?'))
            {
                $.post(site_url+"/secure/delete_address", {id: $(this).attr('rel')},
                function(data) {
                    $('#address_' + data).remove();
                    $('#address_list .my_account_address').removeClass('address_bg');
                    $('#address_list .my_account_address:even').addClass('address_bg');
                });
            }
        }
        else
        {
            alert('You Must leave at least 1 address in the Address Manager.');
        }
    });

    $('.edit_address').click(function() {
        $.post(site_url + '/secure/address_form/' + $(this).attr('rel'),
                function(data) {
                    $('#address_list').hide();
                    $('#address-form-container').html(data);
                    $('#address-form-container').show('slow');
                    $('#address-form-container').find('#f_country_id').change(function() {
                        $.post(site_url + '/locations/get_zone_menu', {id: $('#f_country_id').val()}, function(data) {
                            $('#f_zone_id').html(data);
                        });
                    });
                    init_event($('#address-form-container'));
                }
        );
    });

    $(".show_list").click(function() {
        $('#address_list').show('fast');
        $('#address-form-container').hide('fast');
    });

    $('#backStep2').click(function() {
        $('#address-form-container').show('fast');
    })

    $('#btnStep3').click(function() {
        $.post(site_url + '/secure/address_form/' + $('#f_id').val(), {
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
            lat: $('#f_lat').val(),
            long: $('#f_long').val()
        },
        function(data) {
            if (data == 1)
            {
                window.location = site_url + "/secure/manage_address";
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
        var map = new google.maps.Map(document.getElementById('address-map-container'),
                mapOptions);

        $.each(geoAddresses, function(index, val) {
            var position = new google.maps.LatLng(
                    val.southwest.lat,
                    val.southwest.lng);
            var iconBase = $('#baseUrl').val();
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                title: val.rig_name,
                draggable: true
                        //icon: iconBase + '/img/rigimages/'+val.rig_name+'.PNG'
            });

            google.maps.event.addListener(marker, 'dragend', function()
            {
                var newLat = marker.getPosition().lat();
                var newLng = marker.getPosition().lng();
                $("#f_lat").val(newLat);
                $("#f_long").val(newLng);
                //console.log(newLat+" "+newLng);
//                geocodePosition(marker.getPosition());
            });

//            marker.setTitle(val.rig_name);
//            attachSecretMessage(marker, $('#rigDiv_' + val.rig_id).html());
//            $('.rigsection').find('a').attr('target', '_blank');
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

function set_default(address_id, type)
    {
        var fdata = {
            id: address_id,
            type: type
        }
        $.ajax({
            url: site_url + '/secure/set_default_address',
            type: 'POST',
            data: fdata,
            dataType: 'html',
            async: false,
            error: function() {
                maketoast('danger', 'Error', 'Server error please try after some time');
            },
            success: function(resp) {
                maketoast('success', 'Success', 'Changes done successfully');
            }
        });
        
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
                $('#address-form-container').hide('slow');
                $('#address-map-container').show('slow');
                markPointer(resp);
            }
        });
    })
}