
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

    $('.add_venture').click(function() {
        var fdata = {
            updateCase: 'venAdd',
            venture_id: $(this).attr('rel')
        }
        $.ajax({
            url: site_url + '/ajax/add_venture',
            type: 'POST',
            data: fdata,
            dataType: 'html',
            async: false,
            error: function() {
                //alert('Server error please try after some time')
            },
            success: function(resp) {
                $('#venture-form-container').html(resp);
                $('#venture-form-container').show('slow');
                $('#venture_list').hide();
                initializeFldVA(resp);
            }
        });
    });

    function initializeFldVA() {
        $('#venture-form-container').find('#btnStep3').click(function() {
            
            if($('#f_venture_logo').val()!='') {
                var ext = $('#f_venture_logo').val().split('.').pop().toLowerCase();
                if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
                    //$('#err_venture_logo').text('Please select valid image file.');
                    $("#err_venture_logo").remove();
                    $("#f_venture_logo").after("<p class='error' id='err_venture_logo'>Please select valid image file.</p>");
                    return false;
                }
            }
            
            if ($('#venture-form-container').find("#frmAddVen").valid()) {     
                
                $('#loading').css('visibility','visible');
                //$('#loading').show();
                
                var file = document.getElementById('f_venture_logo').files[0]; //Files[0] = 1st file
                var reader = new FileReader();
                var fileName,dataImage;
                reader.readAsDataURL(file);
                reader.onload = function(event) {
                    dataImage   = event.target.result;
                    fileName    = document.getElementById('f_venture_logo').files[0].name;
                    
                //Edited 23/03/2016
                var selectedCuisine = new Array();
                $('input[name="cuisine_id[]"]:checked').each(function() {
                    selectedCuisine.push(this.value);
                });

                var selectedStartTime = new Array();
                $('input[name="start_time[]"]').each(function() {
                    selectedStartTime.push(this.value);
                });
                var selectedEndTime = new Array();
                $('input[name="end_time[]"]').each(function() {
                    selectedEndTime.push(this.value);
                });
                
                var selectedDays = new Array();
                var daydata = new Array();
                i=0;
                $("select.example").each(function(){                    
                    $('option:selected',this).each(function(){
                        var val = $(this).val();
                        var daydata = new Array({
                                    'daydata':val,
                                    'starttime':selectedStartTime[i],
                                    'endtime':selectedEndTime[i]
                        }); 
                        selectedDays.push(daydata);           
                    });
                    
                    i++;
                });
                
                var fdata = {
                    updateCase: 'venAddSave',
                    id: $('#venture-form-container').find('#f_id').val(),
                    firstname: $('#venture-form-container').find('#f_firstname').val(),
                    lastname: $('#venture-form-container').find('#f_lastname').val(),
                    email: $('#venture-form-container').find('#f_email').val(),
                    phone: $('#venture-form-container').find('#f_phone').val(),
                    company: $('#venture-form-container').find('#f_company').val(),
                    //Edited
                    ventureaddress_line1: $('#venture-form-container').find('#f_line1').val(),
                    ventureaddress_line2: $('#venture-form-container').find('#f_line2').val(),
                    venturecity: $('#venture-form-container').find('#f_city').val(),
                    venturestate: $('#venture-form-container').find('#f_state').val(),
                    venturecountry: $('#venture-form-container').find('#f_country').val(),
                    venturezipcode: $('#venture-form-container').find('#f_zipcode').val(),
                    license_no: $('#venture-form-container').find('#f_license').val(),
                    //Edited 23/03/2016
                    cuisine_id: selectedCuisine,
                    days: selectedDays,
                    startTime: selectedStartTime,
                    endTime: selectedEndTime,
                    minimum_delivery_amount: $('#venture-form-container').find('#f_minimum_delivery_amount').val(),
                    delivery_fee: $('#venture-form-container').find('#f_delivery_fee').val(),
                    avg_delivery_time: $('#venture-form-container').find('#f_avg_delivery_time').val(),
                        payment_method: $('#venture-form-container').find('#f_payment_method').val(),
                        venture_logo:fileName,
                        dataimage:dataImage
                }
                $.ajax({
                    url: site_url + '/ajax/add_venture',
                    type: 'POST',
                    data: fdata,
                    dataType: 'json',
                    async: false,
                    error: function() {
                        maketoast('danger', 'Error', 'Server error please try after some time');
                        $('#loading').css('visibility','hidden');
                    },
                    success: function(resp) {
                        if (resp.status === 'success') {
                            location.reload();
//                            maketoast('success', 'Success', 'New venture added successfully');
//                            manage_venture_address();
                        $('#venture-form-container').hide('slow');
                        $('#venture_list').show();
                        } else if(resp.err != ''){
                            maketoast('danger', 'Error', resp.err);
                    }
                    $('#loading').css('visibility','hidden');
                    }
                });
                    
                };
            }
        })

        $('#venture-form-container').find('#backStep2').click(function() {
            $('#venture-form-container').hide('hide');
            $('#venture_list').show();
        })
    }

    $('.edit_venture').click(function() {
        var fdata = {
            updateCase: 'venGet',
            venture_id: $(this).attr('rel')
        }
        $.ajax({
            url: site_url + '/ajax/edit_venture',
            type: 'POST',
            data: fdata,
            dataType: 'html',
            async: false,
            error: function() {
                //alert('Server error please try after some time')
            },
            success: function(resp) {
                $('#venture-form-container').html(resp);
                $('#venture-form-container').show('slow');
                $('#venture_list').hide();
                initializeFldEV();
            }
        });
    });

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

    $('.eva').click(function() {
        var fdata = {
            reqFor: 'editForm',
            adr_id: $(this).attr('adrid')
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
    })

    $('.add_address').click(function() {
        var ventId = $(this).attr('rel');
        manage_venture_address(ventId, 'getForm');
    });

    $('.edit_address').click(function() {
        var ventId = '';
        var addressId = $(this).attr('rel');
        manage_venture_address(ventId, 'editForm', addressId);
    });

    $('.delete_address').click(function() {
        var addressId = $(this).attr('rel');
        manage_venture_address('', 'deleteForm', addressId);
    });


    $(".show_list").click(function() {
        $('#venture_list').show('fast');
        $('#venture-form-container').hide('fast');
    });
    $('#backStep2').click(function() {
        $('#venture-form-container').show('fast');
    })
})

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
                updateBy: 'ventureId',
                reqFor: reqFor,
                venture_id: $('#venture-form-container').find('#f_id').val(),
                address_id: $('#venture-form-container').find('#f_address_id').val(),
                formData: {
                    venture_id: $('#venture-form-container').find('#f_id').val(),
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
                        //maketoast('success', 'Success', 'Address updated successfully');
                        location.reload();
                    }
                    //$('#venture-form-container').hide('slow');
                    //$('#venture_list').show();
                }
            });
        }
    })
}

function initializeFldEV() {
    $('#venture-form-container').find('#btnStep3').click(function() {

        if($('#f_venture_logo').val()!='') {
            var ext = $('#f_venture_logo').val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
                //$('#err_venture_logo').text('Please select valid image file.');
                $("#err_venture_logo").remove();
                $( "#venture_logo_note" ).after( "<p class='error' id='err_venture_logo'>Please select valid image file.</p>" );
                return false;
            }
        }
        
        if ($('#venture-form-container').find("#frmEditVen").valid()) {
            
            $('#loading').css('visibility','visible');
            //$('#loading').show();
            if($('#f_venture_logo').val()!='') {
                
                var file = document.getElementById('f_venture_logo').files[0]; //Files[0] = 1st file
                var reader = new FileReader();
                var fileName,dataImage;
                reader.readAsDataURL(file);
                reader.onload = function(event) {
                    dataImage   = event.target.result;
                    fileName    = document.getElementById('f_venture_logo').files[0].name;
                
            var selectedCuisin = new Array();
            $('input[name="cuisin_id[]"]:checked').each(function() {
                selectedCuisin.push(this.value);
            });
            
            var selectedStartTime = new Array();
            $('input[name="start_time[]"]').each(function() {
                selectedStartTime.push(this.value);
            });
            var selectedEndTime = new Array();
            $('input[name="end_time[]"]').each(function() {
                selectedEndTime.push(this.value);
            });
            
            var selectedDays = new Array();
            var daydata = new Array();
            k=0;
            $("select.days").each(function(){                    
                $('option:selected',this).each(function(){
                    var val = $(this).val();
                    var daydata = new Array({
                                'daydata':val,
                                'starttime':selectedStartTime[k],
                                'endtime':selectedEndTime[k]
                    }); 
                    selectedDays.push(daydata);           
                });
                k++;
            });
            
            
            var fdata = {
                updateCase: 'venUpdate',
                id: $('#venture-form-container').find('#f_id').val(),
                firstname: $('#venture-form-container').find('#f_firstname').val(),
                lastname: $('#venture-form-container').find('#f_lastname').val(),
                phone: $('#venture-form-container').find('#f_phone').val(),
                company: $('#venture-form-container').find('#f_company').val(),
                //Edited
                ventureaddress_line1: $('#venture-form-container').find('#f_line1').val(),
                ventureaddress_line2: $('#venture-form-container').find('#f_line2').val(),
                venturecity: $('#venture-form-container').find('#f_city').val(),
                venturestate: $('#venture-form-container').find('#f_state').val(),
                venturecountry: $('#venture-form-container').find('#f_country').val(),
                venturezipcode: $('#venture-form-container').find('#f_zipcode').val(),
                license_no: $('#venture-form-container').find('#f_license').val(),
                //Edited 23/03/2016
                min_delivery_amount: $('#venture-form-container').find('#f_min_delivery_amount').val(),
                delivery_fee: $('#venture-form-container').find('#f_delivery_fee').val(),
                avg_delivery_time: $('#venture-form-container').find('#f_avg_delivery_time').val(),
                payment_type: $('#venture-form-container').find('#f_payment_type').val(),
                cuisin_id:selectedCuisin,
                days: selectedDays,
                startTime: selectedStartTime,
                endTime: selectedEndTime,
                venture_logo:fileName,
                dataimage:dataImage
            }
            $.ajax({
                url: site_url + '/ajax/edit_venture',
                type: 'POST',
                data: fdata,
                dataType: 'json',
                async: false,
                error: function() {
                    maketoast('danger', 'Error', 'Server error please try after some time');
                    $('#loading').css('visibility','hidden');
                },
                success: function(resp) {
                    if (resp.status === 'success') {
                        maketoast('success', 'Success', 'Address updated successfully');
                    $('#venture-form-container').hide('slow');
                    $('#venture_list').show();
                    } else if(resp.err != ''){
                        maketoast('danger', 'Error', resp.err);
                }
                $('#loading').css('visibility','hidden');
                }
            });
        }
            } else {
                var selectedCuisin = new Array();
                $('input[name="cuisin_id[]"]:checked').each(function() {
                    selectedCuisin.push(this.value);
                });

                var selectedStartTime = new Array();
                $('input[name="start_time[]"]').each(function() {
                    selectedStartTime.push(this.value);
                });
                var selectedEndTime = new Array();
                $('input[name="end_time[]"]').each(function() {
                    selectedEndTime.push(this.value);
                });

                var selectedDays = new Array();
                var daydata = new Array();
                k=0;
                $("select.days").each(function(){                    
                    $('option:selected',this).each(function(){
                        var val = $(this).val();
                        var daydata = new Array({
                                    'daydata':val,
                                    'starttime':selectedStartTime[k],
                                    'endtime':selectedEndTime[k]
                        }); 
                        selectedDays.push(daydata);           
                    });
                    k++;
                });            
            
                var fdata = {
                    updateCase: 'venUpdate',
                    id: $('#venture-form-container').find('#f_id').val(),
                    firstname: $('#venture-form-container').find('#f_firstname').val(),
                    lastname: $('#venture-form-container').find('#f_lastname').val(),
                    phone: $('#venture-form-container').find('#f_phone').val(),
                    company: $('#venture-form-container').find('#f_company').val(),
                    //Edited
                    ventureaddress_line1: $('#venture-form-container').find('#f_line1').val(),
                    ventureaddress_line2: $('#venture-form-container').find('#f_line2').val(),
                    venturecity: $('#venture-form-container').find('#f_city').val(),
                    venturestate: $('#venture-form-container').find('#f_state').val(),
                    venturecountry: $('#venture-form-container').find('#f_country').val(),
                    venturezipcode: $('#venture-form-container').find('#f_zipcode').val(),
                    license_no: $('#venture-form-container').find('#f_license').val(),
                    //Edited 23/03/2016
                    min_delivery_amount: $('#venture-form-container').find('#f_min_delivery_amount').val(),
                    delivery_fee: $('#venture-form-container').find('#f_delivery_fee').val(),
                    avg_delivery_time: $('#venture-form-container').find('#f_avg_delivery_time').val(),
                    payment_type: $('#venture-form-container').find('#f_payment_type').val(),
                    cuisin_id:selectedCuisin,
                    days: selectedDays,
                    startTime: selectedStartTime,
                    endTime: selectedEndTime
                }
                $.ajax({
                    url: site_url + '/ajax/edit_venture',
                    type: 'POST',
                    data: fdata,
                    dataType: 'json',
                    async: false,
                    error: function() {
                        maketoast('danger', 'Error', 'Server error please try after some time');
                    },
                    success: function(resp) {
                        if (resp.status === 'success') {
                            maketoast('success', 'Success', 'Address updated successfully');
                            $('#venture-form-container').hide('slow');
                            $('#venture_list').show();
                        } else if(resp.err != ''){
                            maketoast('danger', 'Error', resp.err);
                        }                     
                    }
                });
            }
        }
    })

    $('#venture-form-container').find('#backStep2').click(function() {
        $('#venture-form-container').hide('hide');
        $('#venture_list').show();
    })
}

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

/**
 * Function to display pointer on Google Map
 * @returns {undefined}
 */
function markPointer(geoAddresses, zoom) {
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



/*
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
 $('#venture-form-container').html(resp);
 $('#venture-form-container').show('slow');
 $('#venture-map-container').hide('slow');
 
 $('#venture_list').hide();
 //markPointer(resp);
 }
 });
 });
 */