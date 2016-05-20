<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>
<script type="text/javascript" src="<?php echo base_url('/application/themes/default/assets/js/jquery-ui.min.js');?>"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCu2f5nkkLPiX4gg-fId8vas2STZn4oudA&sensor=false&libraries=geometry"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/jquery.validate.js');?>" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('.delete_address').click(function() {
            if ($('.delete_address').length > 1)
            {
                if (confirm('<?php echo lang('delete_address_confirmation'); ?>'))
                {
                    $.post("<?php echo site_url('secure/delete_address'); ?>", {id: $(this).attr('rel')},
                    function(data) {
                        $('#address_' + data).remove();
                        $('#address_list .my_account_address').removeClass('address_bg');
                        $('#address_list .my_account_address:even').addClass('address_bg');
                    });
                }
            }
            else
            {
                alert('<?php echo lang('error_must_have_address'); ?>');
            }
        });

        $('.edit_address').click(function() {
            $.post('<?php echo site_url('secure/address_form'); ?>/' + $(this).attr('rel'),
                    function(data) {
                        $('#address-form-container').html(data).modal('show');
                    }
            );
        });

        /**
         * Code added for handling new partner associate with Vendor
         */
        $('.edit_partners').click(function() {
            $.post('<?php echo site_url('secure/partner_form'); ?>/' + $(this).attr('rel'),
                    function(data) {
                        $('#partner-form-container').html(data).modal('show');
                    }
            );
        });

        /**
         * Code to delete partner request handling
         * @param {type} address_id
         * @param {type} type
         * @returns {undefined}
         */
        $('.delete_partner').click(function() {
            if ($('.delete_partner').length > 1)
            {
                if (confirm('Do you realy want to delete partner?'))
                {
                    $.post("<?php echo site_url('secure/delete_partner'); ?>", {id: $(this).attr('rel')},
                    function(data) {
                        $('#partner_' + data).remove();
//                        $('#address_list .my_account_address').removeClass('address_bg');
//                        $('#address_list .my_account_address:even').addClass('address_bg');
                    });
                }
            }
            else
            {
                alert('Error must have partner');
            }
        });

    });


    function set_default(address_id, type)
    {
        $.post('<?php echo site_url('secure/set_default_address') ?>/', {id: address_id, type: type});
    }


</script>


<?php
$company = array('id' => 'company', 'class' => 'span4', 'name' => 'company', 'value' => set_value('company', $customer['company']));
$first = array('id' => 'firstname', 'class' => 'span2', 'name' => 'firstname', 'value' => set_value('firstname', $customer['firstname']));
$last = array('id' => 'lastname', 'class' => 'span2', 'name' => 'lastname', 'value' => set_value('lastname', $customer['lastname']));
$email = array('id' => 'email', 'class' => 'span2', 'name' => 'email', 'value' => set_value('email', $customer['email']));
$phone = array('id' => 'phone', 'class' => 'span2', 'name' => 'phone', 'value' => set_value('phone', $customer['phone']));

$password = array('id' => 'password', 'class' => 'span2', 'name' => 'password', 'value' => '');
$confirm = array('id' => 'confirm', 'class' => 'span2', 'name' => 'confirm', 'value' => '');
?>	
<div class="span4">
    <div class="my-account-box">
        <?php echo form_open('secure/my_account'); ?>
        <fieldset>
            <h2><?php echo lang('account_information'); ?></h2>
            
            <div class="alert alert-success">
                <h5>Access type: <?php echo $role['name']; ?></h5>
            </div>

            <div class="row">
                <div class="span4">
                    <label for="company"><?php echo lang('account_company'); ?></label>
                    <?php echo form_input($company); ?>
                </div>
            </div>
            <div class="row">	
                <div class="span2">
                    <label for="account_firstname"><?php echo lang('account_firstname'); ?></label>
                    <?php echo form_input($first); ?>
                </div>

                <div class="span2">
                    <label for="account_lastname"><?php echo lang('account_lastname'); ?></label>
                    <?php echo form_input($last); ?>
                </div>
            </div>

            <div class="row">
                <div class="span2">
                    <label for="account_email"><?php echo lang('account_email'); ?></label>
                    <?php echo form_input($email); ?>
                </div>

                <div class="span2">
                    <label for="account_phone"><?php echo lang('account_phone'); ?></label>
                    <?php echo form_input($phone); ?>
                </div>
            </div>

            <!--            <div class="row">
                            <div class="span7">
                                <label class="checkbox">
                                    <input type="checkbox" name="email_subscribe" value="1" <?php if ((bool) $customer['email_subscribe']) { ?> checked="checked" <?php } ?>/> <?php echo lang('account_newsletter_subscribe'); ?>
                                </label>
                            </div>
                        </div>-->

            <div class="row">
                <div class="span4">
                    <div style="margin:30px 0px 10px; text-align:center;">
                        <strong><?php echo lang('account_password_instructions'); ?></strong>
                    </div>
                </div>
            </div>

            <div class="row">	
                <div class="span2">
                    <label for="account_password"><?php echo lang('account_password'); ?></label>
                    <?php echo form_password($password); ?>
                </div>

                <div class="span2">
                    <label for="account_confirm"><?php echo lang('account_confirm'); ?></label>
                    <?php echo form_password($confirm); ?>
                </div>
            </div>

            <input type="submit" value="<?php echo lang('form_submit'); ?>" class="btn btn-primary" />

        </fieldset>
        </form>
    </div>
</div>

<?php
if ($role['name'] == 'Vendors') {
    $data['customer_id'] = $customer['id'];
    $this->view('partials/my_account_vendor', $data);
} else {
    ?>
    <div class="span7 pull-right">
        <?php
        if($role['name'] == ""){
        ?>
        <div class="row" style="padding-top:10px;">
            <div class="span4">
                <h2><?php echo lang('address_manager'); ?></h2>
            </div>
            <div class="span3" style="text-align:right;">
                <input type="button" class="btn edit_address" rel="0" value="<?php echo lang('add_address'); ?>"/>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="span7" id='address_list'>
                <?php if (count($addresses) > 0): ?>
                    <table class="table table-bordered table-striped">
                        <?php
                        $c = 1;
                        foreach ($addresses as $a):
                            ?>
                            <tr id="address_<?php echo $a['id']; ?>">
                                <td>
                                    <?php
                                    $b = $a['field_data'];
                                    echo format_address($b, true);
                                    ?>
                                </td>
                                <td>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="btn-group pull-right">
                                                <input type="button" class="btn edit_address" rel="<?php echo $a['id']; ?>" value="<?php echo lang('form_edit'); ?>" />
                                                <input type="button" class="btn btn-danger delete_address" rel="<?php echo $a['id']; ?>" value="<?php echo lang('form_delete'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <div class="pull-right" style="padding-top:10px;">
                                                <input type="radio" name="bill_chk" onclick="set_default(<?php echo $a['id'] ?>, 'bill')" <?php if ($customer['default_billing_address'] == $a['id']) echo 'checked="checked"' ?> /> <?php echo lang('default_billing'); ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="ship_chk" onclick="set_default(<?php echo $a['id'] ?>, 'ship')" <?php if ($customer['default_shipping_address'] == $a['id']) echo 'checked="checked"' ?>/> <?php echo lang('default_shipping'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="span12">
        <div class="page-header">
            <h2><?php echo lang('order_history'); ?></h2>
        </div>
        <?php
        if ($orders):
            echo $orders_pagination;
            ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><?php echo lang('order_date'); ?></th>
                        <th><?php echo lang('order_number'); ?></th>
                        <th><?php echo lang('order_status'); ?></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>
                                <?php
                                $d = format_date($order->ordered_on);

                                $d = explode(' ', $d);
                                echo $d[0] . ' ' . $d[1] . ', ' . $d[3];
                                ?>
                            </td>
                            <td><?php echo $order->order_number; ?></td>
                            <td><?php echo $order->status; ?></td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <?php echo lang('no_order_history'); ?>
        <?php endif; ?>
    </div>
</div>

<div id="address-form-container" class="hide">
</div>

<div id="partner-form-container" class="hide">
</div>

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