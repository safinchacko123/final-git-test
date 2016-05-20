<script type="text/javascript" src="<?php echo site_url('/application/themes/default/assets/js/jquery-ui.min.js');?>"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCu2f5nkkLPiX4gg-fId8vas2STZn4oudA&sensor=false&libraries=geometry"></script>
<script src="<?php echo site_url('/application/themes/default/assets/js/jquery.validate.js');?>" type="text/javascript"></script>
<script type="text/javascript">
//var image = 'http://www.boardliving.com/images/logo_point.png'; 
//var image = ''; 
    var LatLongArr = [];
    var map;
    var geocoder;
// Create a meausure object to store our markers, MVCArrays, lines and polygons
    var measure = {
        mvcLine: new google.maps.MVCArray(),
        mvcPolygon: new google.maps.MVCArray(),
        mvcMarkers: new google.maps.MVCArray(),
        line: null,
        polygon: null
    };

    $(document).ready(function() {
        $("#frmSpot").validate();
        $("#date").datepicker({maxDate: 0});
        /* Code to display map*/
        geocoder = new google.maps.Geocoder();  // For Geocoder
        var latlng = new google.maps.LatLng(37.09024, -95.712891);
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: new google.maps.LatLng(37.09024, -95.712891),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });


        //draggableCursor: "crosshair" // Make the map cursor a crosshair so the user thinks they should click something




        $("#path").change(function() {
            google.maps.event.addListener(map, "click", function(evt) {
                // When the map is clicked, pass the LatLng obect to the measureAdd function
                measureAdd(evt.latLng);
                // Code to add the lat lang in hidden field
                $("#latLang").val(evt.latLng);
                var tempLL = evt.latLng;
                //var tempLL1 = tempLL.replace("(", "");
                //var tempLL2 = tempLL1.replace(")", "");
                LatLongArr.push(tempLL);
                $("#latlongarray").val(JSON.stringify(LatLongArr));
            });
        });
        /* End of code*/

    });

    function measureAdd(latLng) {
        // Add a draggable marker to the map where the user clicked
        var marker = new google.maps.Marker({
            map: map,
            position: latLng,
            draggable: true,
            raiseOnDrag: false,
            title: "Drag me to change shape"
                    //icon: new google.maps.MarkerImage(image, new google.maps.Size(17, 17), new google.maps.Point(0, 0), new google.maps.Point(6, 6))
        });

        // Add this LatLng to our line and polygon MVCArrays
        // Objects added to these MVCArrays automatically update the line and polygon shapes on the map
        measure.mvcLine.push(latLng);
        measure.mvcPolygon.push(latLng);

        // Push this marker to an MVCArray
        // This way later we can loop through the array and remove them when measuring is done
        measure.mvcMarkers.push(marker);

        // Get the index position of the LatLng we just pushed into the MVCArray
        // We'll need this later to update the MVCArray if the user moves the measure vertexes
        var latLngIndex = measure.mvcLine.getLength() - 1;

        // When the user mouses over the measure vertex markers, change shape and color to make it obvious they can be moved
        google.maps.event.addListener(marker, "mouseover", function() {
            marker.setIcon(new google.maps.MarkerImage(image, new google.maps.Size(18, 18), new google.maps.Point(0, 0), new google.maps.Point(8, 8)));
        });

        // Change back to the default marker when the user mouses out
        google.maps.event.addListener(marker, "mouseout", function() {
            marker.setIcon(new google.maps.MarkerImage(image, new google.maps.Size(18, 18), new google.maps.Point(0, 0), new google.maps.Point(6, 6)));
        });

        // When the measure vertex markers are dragged, update the geometry of the line and polygon by resetting the
        //     LatLng at this position
        google.maps.event.addListener(marker, "drag", function(evt) {
            measure.mvcLine.setAt(latLngIndex, evt.latLng);
            measure.mvcPolygon.setAt(latLngIndex, evt.latLng);
        });

        // When dragging has ended and there is more than one vertex, measure length, area.
        google.maps.event.addListener(marker, "dragend", function() {
            $("#latlongarray").val(JSON.stringify(measure.mvcPolygon.b));
            if (measure.mvcLine.getLength() > 1) {
                measureCalc();
            }
        });

        // If there is more than one vertex on the line
        if (measure.mvcLine.getLength() > 1) {

            // If the line hasn't been created yet
            if (!measure.line) {
                // Create the line (google.maps.Polyline)
                measure.line = new google.maps.Polyline({
                    map: map,
                    clickable: false,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    path: measure.mvcLine
                });

            }

            // If there is more than two vertexes for a polygon
            if (measure.mvcPolygon.getLength() > 2) {

                // If the polygon hasn't been created yet
                if (!measure.polygon) {

                    // Create the polygon (google.maps.Polygon)
                    measure.polygon = new google.maps.Polygon({
                        clickable: false,
                        map: map,
                        fillOpacity: 0.25,
                        strokeOpacity: 0,
                        paths: measure.mvcPolygon
                    });
                }
            }
        }

        // If there's more than one vertex, measure length, area.
        if (measure.mvcLine.getLength() > 1) {
            measureCalc();
        }
    }

    function measureCalc() {
        // Use the Google Maps geometry library to measure the length of the line
        var length = google.maps.geometry.spherical.computeLength(measure.line.getPath());
        var length = length * (0.00062137);
        $("#span-length").text(length.toFixed(5));
        $("#lineLength").val(length.toFixed(5));
        // If we have a polygon (>2 vertexes in the mvcPolygon MVCArray)
        if (measure.mvcPolygon.getLength() > 2) {
            // Use the Google Maps geometry library to measure the area of the polygon
            var area = google.maps.geometry.spherical.computeArea(measure.polygon.getPath());
            var area = area * (0.000000386102159);
            $("#span-area").text(area.toFixed(5));
            $("#polyArea").val(area.toFixed(5));
        }
    }


    function measureReset() {

        // If we have a polygon or a line, remove them from the map and set null    
        if (measure.polygon) {
            measure.polygon.setMap(null);
            measure.polygon = null;
        }
        if (measure.line) {
            measure.line.setMap(null);
            measure.line = null
        }

        // Empty the mvcLine and mvcPolygon MVCArrays
        measure.mvcLine.clear();
        measure.mvcPolygon.clear();

        // Loop through the markers MVCArray and remove each from the map, then empty it
        measure.mvcMarkers.forEach(function(elem, index) {
            elem.setMap(null);
        });
        measure.mvcMarkers.clear();
        $("#span-length,#span-area").text(0);

        $("#latLang").val("");
        $("#latlongarray").val("");
        $("#lineLength").val("");
        $("#polyArea").val("");
        LatLongArr.length = 0;
    }

    function codeAddress() {


        google.maps.event.addListener(map, "click", function(evt) {
            // When the map is clicked, pass the LatLng obect to the measureAdd function
            measureAdd(evt.latLng);
            // Code to add the lat lang in hidden field
            $("#latLang").val(evt.latLng);
            var tempLL = evt.latLng;
            //var tempLL1 = tempLL.replace("(", "");
            //var tempLL2 = tempLL1.replace(")", "");
            LatLongArr.push(tempLL);
            $("#latlongarray").val(JSON.stringify(LatLongArr));
        });
        $('#path').click();
        var address = document.getElementById("address").value;

        geocoder.geocode({'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    //icon: image
                });
                map.setZoom(14);
            } else {
                //alert("Geocode was not successful for the following reason: " + status);
            }
        });


        //var address = 'Second Cliff, Scituate, MA, United States';        

    }

    /* function clrTxt(field){
     if (field.defaultValue == field.value) field.value = '';
     else if (field.value == '') field.value = field.defaultValue;
     
     }*/
</script>

<ul class="logentry_frm">  

    <li style="margin:20px 0px;">
        Click to mark the location<input type="radio" name="path" id="path" onChange="selectMarker();" value="1">
    </li>
    <li>
        <a style="text-align:center" href="javascript:measureReset();">Reset</a>
    </li>
    <li>
        Total Line Length (Red Line) : <span id="span-length">0</span> miles
    </li>

    <li style="margin:20px 0px;">
        <div id="map" style="width: 950px; height: 500px"></div> 
    </li>
</ul>
<ul class="logentry_frm">
    <li class="btn">
        <label>&nbsp;</label>		 
        <input type="submit" class="btn clearright smallest" id="btnSubmit" name="btnSubmit" value="Save" />
        <input type="hidden" id="latLang" name="latLang" value="">
        <input type="hidden" id="latlongarray" name="latlongarray" value="">
        <input type="hidden" id="lineLength" name="lineLength" value="">
        <input type="hidden" id="polyArea" name="polyArea" value="">

    </li>
</ul>