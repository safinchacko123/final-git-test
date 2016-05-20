
$(document).ready(function() {

    $('.delete_address').click(function() {
        if ($('.delete_address').length > 1)
        {
            if (confirm('Are you sure you want to delete this venture?'))
            {
                $.post(site_url + "/secure/delete_venture", {id: $(this).attr('rel')},
                function(data) {
                    $('#address_' + data).remove();
                });
            }
        }
        else
        {
            alert('error_must_have_address');
        }
    });

    $('.edit_store').click(function() {

        var storeId = $(this).attr('rel');
        var fdata = {
            callCase: storeId
        }
        $.ajax({
            url: site_url + '/ajax/addStore/' + $(this).attr('rel'),
            type: 'GET',
            dataType: 'html',
            async: true,
            error: function() {
            },
            success: function(data) {
                $('#address-form-container').html(data);

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
        });

    });

    $('.add_store').click(function() {
        var fdata = {
            callCase: 'add'
        }
        $.ajax({
            url: site_url + '/ajax/addStore',
            type: 'POST',
            data: fdata,
            dataType: 'html',
            async: true,
            error: function() {
            },
            success: function(resp) {
                var obj = jQuery.parseJSON(resp);
                if (obj.journal != '') {
                    $('#tooltips').centr("We have found current Drill and Report date related Journal\n\
                        and added to Journal section", false);
                    $('#event_journal').val(obj.journal);
                    //$('#event_journal').focus();   
                }
            }
        });
    });

    $(".show_list").click(function() {
        $('#address_list').show('fast');
        $('#address-form-container').hide('fast');
    });

    $('#backStep2').click(function() {
        $('#address-form-container').show('fast');
    })

    $('#btnStep3').click(function() {

        var fldId = $('#f_id').val();

        var callCase = '';
        if (fldId) {
            callCase = 'edit';
        } else {
            callCase = 'add';
        }
        var fdata = {
            callCase: callCase,
            name: $('#my-modal').find('#f_storename').val(),
            address1: $('#my-modal').find('#f_address1').val(),
            country_id: $('#my-modal').find('#f_country_id').val(),
            city: $('#my-modal').find('#f_city').val(),
            zone: $('#my-modal').find('#f_zone_id').val(),
            zip: $('#my-modal').find('#f_zip').val(),
            coverage_area: $('#my-modal').find('#f_coverage').val(),
            lat: $('#my-modal').find('#f_lat').val(),
            long: $('#my-modal').find('#f_long').val()
        }
        $.ajax({
            url: site_url + '/ajax/storeManage',
            type: 'POST',
            data: fdata,
            dataType: 'html',
            async: true,
            error: function() {
            },
            success: function(resp) {
                var obj = jQuery.parseJSON(resp);
                if (obj.journal != '') {
                    $('#tooltips').centr("We have found current Drill and Report date related Journal\n\
                        and added to Journal section", false);
                    $('#event_journal').val(obj.journal);
                    //$('#event_journal').focus();   
                }
            }
        });
    })

    $('.add_address').click(function() {
        var ventId = $(this).attr('rel');
        manage_venture_address(ventId, 'getForm');
    });

    $('.edit_address').click(function() {
        var addressId = $(this).attr('rel');
        manage_venture_address('', 'editForm', addressId);
    });

    $('.delete_address').click(function() {
        var addressId = $(this).attr('rel');
        manage_venture_address('', 'deleteForm', addressId);
    });
    
    $('#saveDeliveryAddress').click(function() { 
		var formData = $("#adminForm-deliveryAddress").serializeArray();
		$("#loader-IndertDeliveryArea").show();
		  $.ajax({
        url: site_url + '/ajax/insert_venture_deliveryAddress',
        type: 'POST',
        data: formData,
       // dataType: 'html',
        async: false,
        error: function() {
            maketoast('danger', 'Error', 'Server error please try after some time');
        },
        success: function(resp) {
			
			$("#loader-IndertDeliveryArea").hide();
			$(".successMessage").show();
			setTimeout(function(){ $(".successMessage").hide(); }, 3000);
			
            //~ $('#venture-form-container').html(resp);
            //~ $('#venture-form-container').show();
            //~ $('#venture_list').hide();
            //~ initializeFldsEVA();
            //~ $('#venture-form-container').find('#markIt').trigger('click');
        }
    });
	});
    
})

function manage_venture_address(venture_id, reqFor, address_id) {
    var fdata = {
        reqFor: reqFor,
        venture_id: venture_id,
        address_id: address_id
    }
    $.ajax({
        url: site_url + '/ajax/manage_venture_address',
        type: 'POST',
        data: fdata,
        dataType: 'html',
        async: false,
        error: function() {
            maketoast('danger', 'Error', 'Server error please try after some time');
        },
        success: function(resp) {
            $('#venture-form-container').html(resp);
            $('#venture-form-container').show();
            $('#venture_list').hide();
            initializeFldsEVA();
            $('#venture-form-container').find('#markIt').trigger('click');
        }
    });
}

function get_latlong(source) {
    var parent = $(this).parent().parent();
    // Pass address to get geolocation 
    var fdata = {
        address1: $('#venture-form-container').find('#f_address').val(),
        city: $('#venture-form-container').find('#f_city').val(),
        country_id: $('#venture-form-container').find('#f_country_id').val(),
        country: $('#venture-form-container').find("#f_country_id :selected").text(),
        zone_id: $('#venture-form-container').find('#f_zone_id').val(),
        zip: $('#venture-form-container').find('#f_zip').val()
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
            //$('#venture-form-container').hide('slow');
            $('#venture-map-container').show('slow');
            markPointer(resp, resp[0].zoom);
        }
    });
}

/**
 * Function to display pointer on Google Map
 * @returns {undefined}
 */
function markPointer(geoAddresses, zoom) {
	alert("ddd");
//var geoAddresses = jQuery.parseJSON($('#geoAddress').val());
//geoAddresses = jQuery.parseJSON('[{"name":"Deschutes","southwest":{"lat":43.6102319,"lng":-122.0026749},"northeast":{"lat":44.393437,"lng":-119.8963652}},{"name":"Deschutes","southwest":{"lat":23.6102319,"lng":-122.0026749},"northeast":{"lat":24.393437,"lng":-119.8963652}}]');
    function initialize() {
        var mapOptions = {
            zoom: zoom,
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
                radius: eval($('#venture-form-container').find('#f_coverage').val() * 1000)
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

function initializeFldsEVA() {
    $('#venture-form-container').find('#markIt').click(function() {
        get_latlong();
    });

    $('#venture-form-container').find('#saveVAdr').click(function() {
        if ($('#venture-form-container').find("#frmEVA").valid()) {

            var reqFor = 'addAdr';
            if ($('#venture-form-container').find('#f_address_id').val() !== '') {
                reqFor = 'updateForm';
            }
            var fdata = {
                reqFor: reqFor,
                updateBy: 'addressId',
                address_id: $('#venture-form-container').find('#f_address_id').val(),
                formData: {
                    venture_id: $('#venture-form-container').find('#f_id').val(),
                    id: $('#venture-form-container').find('#f_address_id').val(),
                    address: $('#venture-form-container').find('#f_address').val(),
                    city: $('#venture-form-container').find('#f_city').val(),
                    zip: $('#venture-form-container').find('#f_zip').val(),
                    country_id: $('#venture-form-container').find('#f_country_id').val(),
                    coverage_area: $('#venture-form-container').find('#f_coverage').val(),
                    latitude: $('#venture-form-container').find('#f_lat').val(),
                    longitude: $('#venture-form-container').find('#f_long').val()
                }
            }
            $.ajax({
                url: site_url + '/ajax/manage_venture_address',
                type: 'POST',
                data: fdata,
                dataType: 'json',
                async: false,
                error: function() {
                    maketoast('danger', 'Error', 'Server error please try after some time');
                },
                success: function(resp) {
                    if (resp.status === 'success') {
                        maketoast('success', 'Success', 'Venture updated successfully');
                    }
                    $('#venture-form-container').hide('slow');
                    $('#venture-map-container').hide('slow');
                    $('#venture_list').show();
                }
            });
        }
    })
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
                markPointer(resp, 11);
            }
        });
    })
}
