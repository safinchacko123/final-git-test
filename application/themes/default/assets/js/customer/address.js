
$(document).ready(function() {
    var geoAddresses = jQuery.parseJSON($('#geoAddress').val());
    //geoAddresses = jQuery.parseJSON('[{"name":"Deschutes","southwest":{"lat":43.6102319,"lng":-122.0026749},"northeast":{"lat":44.393437,"lng":-119.8963652}},{"name":"Deschutes","southwest":{"lat":23.6102319,"lng":-122.0026749},"northeast":{"lat":24.393437,"lng":-119.8963652}}]');
    function initialize() {
        var mapOptions = {
            zoom: 3,
            center: new google.maps.LatLng('40.163403', '-94.492187'),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('googleMap'),
            mapOptions);

        $.each(geoAddresses, function(index, val) {
            var position = new google.maps.LatLng(
                val.southwest.lat,
                val.southwest.lng);
            var iconBase = $('#baseUrl').val();
            var marker = new google.maps.Marker({
                position: position,
                map: map,
                title: val.rig_name
                //icon: iconBase + '/img/rigimages/'+val.rig_name+'.PNG'
            });

            marker.setTitle(val.rig_name);
            attachSecretMessage(marker, $('#rigDiv_'+val.rig_id).html());
            $('.rigsection').find('a').attr('target','_blank');
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

    google.maps.event.addDomListener(window, 'load', initialize);
        
    $('#googleMap').click(function(){
        var height = 0;
        $('.rigsection').each( function(){
            $(this).css('height', 'auto');
            height = Math.max(height, $(this).height()) });
        $('.rigsection').height(height);
    })
})