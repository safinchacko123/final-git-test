<?php if (validation_errors()): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert">×</a>
        <?php echo validation_errors(); ?>
    </div>
<?php endif; ?>

<?php //echo '<pre>';  print_r($deliveryAddress); echo '</pre>'; ?>
<style>
.successMessage {  border: 1px solid #6c9f43;  color: #6c9f43;  font-weight: bold;  margin-top: 12px;  padding: 2px 8px;}
</style>
<script>
var deliveryArea ;


</script>
<script type="text/javascript" src="<?php echo base_url('/application/themes/default/assets/js/jquery-ui.min.js');?>"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing&key=AIzaSyA1bpHeuMyKBpvyOfBovIVGaa7DfF0Rq4o"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/jquery.validate.js');?>" type="text/javascript"></script>
<script src="<?php echo base_url('/application/themes/default/assets/js/venture/gmap.js');?>" type="text/javascript"></script>            
<script>

	function print(txt) { document.getElementById("paths").value += txt  }
	function save_hidden(txt) 
	{
		
		if(document.getElementById("polygon_latlong").value == '')
			document.getElementById("polygon_latlong").value +=  txt ; 
			else
				document.getElementById("polygon_latlong").value += "|"+txt;
	}
      
    var map;
	var geocoder;
	var marker;
	/***********************************************************************************
	* Set Marker info size                                                                *
	***********************************************************************************/
	var infowindow = new google.maps.InfoWindow({
		size: new google.maps.Size(150, 50)
	});
	/***********************************************************************************
	* Set Map  setting perameter                                                               *
	***********************************************************************************/	
    var mapOptions = {
			center: new google.maps.LatLng(<?php echo $ventureAddress->latitude; ?>,<?php echo $ventureAddress->longitude; ?>),
			//center: new google.maps.LatLng('30.733315','76.779418'),
			
			zoom: 14,
			draggable: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	/***********************************************************************************
	* Function for load map
	***********************************************************************************/
	var drawingManager = new google.maps.drawing.DrawingManager({
		drawingMode: google.maps.drawing.OverlayType.POLYGON,
		drawingControl: false,
		drawingControlOptions: {
			position: google.maps.ControlPosition.TOP_CENTER,
			drawingModes: [
				//google.maps.drawing.OverlayType.MARKER,
				//google.maps.drawing.OverlayType.CIRCLE,
				google.maps.drawing.OverlayType.POLYGON,
				//google.maps.drawing.OverlayType.POLYLINE,
				google.maps.drawing.OverlayType.RECTANGLE
			]
		},
		markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
		//polygonOptions: {
		rectangleOptions:{		 
			fillColor: '#ffff00',
			fillOpacity: 1,
			strokeWeight: 5,
			clickable: true,
			editable: true,
			zIndex: 1,
			draggable:true,
		}
	});
		  
		  
	//This variable gets all coordinates of polygone and save them. Finally you should use this array because it contains all latitude and longitude coordinates of polygon.
	var coordinates = [];

	//This variable saves polygon.
	var polygons = [];		  
		  
    function save_coordinates_to_array(polygon)
	{
		//Save polygon to 'polygons[]' array to get its coordinate.
		polygons.push(polygon);
		//This variable gets all bounds of polygon.
		var polygonBounds = polygon.getPath();
		document.getElementById("polygon_latlong").value = '';
		for(var i = 0 ; i < polygonBounds.length ; i++)
		{
			coordinates.push(polygonBounds.getAt(i).lat(), polygonBounds.getAt(i).lng());
			var x = polygonBounds.getAt(i).lat()+','+polygonBounds.getAt(i).lng();
			save_hidden(x);
			//alert(i);
		}   
		console.log(coordinates);
		
		//~ var redCoords = [
			//~ {lat: 28.630110062508894, lng: 77.20075607299805},
			//~ {lat: 28.63395214251842, lng: 77.20968246459961},
			//~ {lat: 28.628226638582973, lng: 77.2115707397461},
			//~ {lat: 28.62807596320892, lng: 77.19989776611328}
		//~ ];
		//~ 
		//~ // Construct a draggable red triangle with geodesic set to true.
			//~ new google.maps.Polygon({
			//~ map: map,
			//~ paths: redCoords,
			//~ // strokeColor: '#FF0000',
			//~ strokeOpacity: 0.8,
			//~ strokeWeight: 2,
			//~ // fillColor: '#FF0000',++
			//~ editable: true,
			//~ fillOpacity: 0.35,
			//~ draggable: true,
			//~ geodesic: true
		//~ });
	}		  
		  
		  
    function initialize() {
		
		/******* Pass the map peramter with Div ID *******/
        geocoder = new google.maps.Geocoder();
        map = new google.maps.Map(document.getElementById("venture-map-container"), mapOptions);
		
		drawingManager.setMap(map);
		  
	
		//This event fires when creation of polygon is completed by user.
		google.maps.event.addDomListener(drawingManager, 'polygoncomplete', function(polygon) {
			
			//console.log(polygon); 
			drawingManager.setMap(null);
			//This line make it possible to edit polygon you have drawed.
			polygon.setEditable(true);

			//Call function to pass polygon as parameter to save its coordinated to an array.
			save_coordinates_to_array(polygon);

			//This event is inside 'polygoncomplete' and fires when you edit the polygon by moving one of its anchors.
			google.maps.event.addListener(polygon.getPath(), 'set_at', function () {
				//alert('changed');
				save_coordinates_to_array(polygon);
			});

			//This event is inside 'polygoncomplete' too and fires when you edit the polygon by moving on one of its anchors.
			google.maps.event.addListener(polygon.getPath(), 'insert_at', function () {
				//alert('also changed');
				save_coordinates_to_array(polygon);
			});
		});
		
		
		//~ var redCoords = [
			//~ {lat: 28.630110062508894, lng: 77.20075607299805},
			//~ {lat: 28.63395214251842, lng: 77.20968246459961},
			//~ {lat: 28.628226638582973, lng: 77.2115707397461},
			//~ {lat: 28.62807596320892, lng: 77.19989776611328}
		//~ ];
//~ 
		//~ // Construct a draggable red triangle with geodesic set to true.
		//~ new google.maps.Polygon({
			//~ map: map,
			//~ paths: redCoords,
			//~ // strokeColor: '#FF0000',
			//~ strokeOpacity: 0.8,
			//~ strokeWeight: 2,
			//~ // fillColor: '#FF0000',++
			//~ editable: true,
			//~ fillOpacity: 0.35,
			//~ draggable: true,
			//~ geodesic: true
		//~ });
	
	}
    
    
    
   


	function set_poligon()
	{
		
		     geocoder = new google.maps.Geocoder();
        map = new google.maps.Map(document.getElementById("venture-map-container"), mapOptions);

        google.maps.event.addListener(map, 'click', function(event) { 
			print("new google.maps.LatLng" + event.latLng + ", ");
			var marker = new google.maps.Marker({ map: map, position: event.latLng });
			
        });
		var pol = [document.getElementById("paths").value];
		 var polygon = new google.maps.Polygon({
			paths: pol
        });
      polygon.setMap(map);
	}
	
	
	//-----------------
	
	function geocodePosition(pos,marker) 
	{

		geocoder.geocode({
			latLng: pos
		}, function(responses) {
			if (responses && responses.length > 0) {
				marker.formatted_address = responses[0].formatted_address;
			} else {
				marker.formatted_address = 'Cannot determine address at this location.';
			}
				console.log(marker);
			infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
			infowindow.open(map, marker);
		});
	}

	function codeAddress() 
	{
		var address = document.getElementById('address').value;
		geocoder.geocode({
			'address': address
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				if (marker) {
					marker.setMap(null);
					if (infowindow) infowindow.close();
				}
				marker = new google.maps.Marker({
					map: map,
					draggable: true,
					position: results[0].geometry.location
				});
				google.maps.event.addListener(marker, 'dragend', function() {
					geocodePosition(marker.getPosition());
				});
				google.maps.event.addListener(marker, 'click', function() {
					if (marker.formatted_address) {
						infowindow.setContent(marker.formatted_address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
					} else {
						infowindow.setContent(address + "<br>coordinates: " + marker.getPosition().toUrlValue(6));
					}
					infowindow.open(map, marker);
				});
				google.maps.event.trigger(marker, 'click');
			} 
			else 
			{
				alert('Geocode was not successful for the following reason: ' + status);
			}
		});
	}
	//-----------------------
    google.maps.event.addDomListener(window, 'load', initialize);
    
</script>

<input type="button" onclick="removeOverlay()" value="remove" />
<div class="span4" style="float: none; margin: 0 auto; width: 100%;">

    <p>
    <h2><?php echo lang('account_information'); ?></h2>
    <p>
    <div class="my-account-box">
        <fieldset>

            <div class="tabbable">
                <ul data-tabs="tabs" class="nav nav-tabs">
                    <?php
                    if ($this->customer) {
                        if ($this->customer['role_id'] == 0) {
                            $roleName = 'CustomerLinks';
                        } else {
                            $roleName = $this->customer['role']['role_name'] . 'Links';
                        }
                        foreach ($this->config->item($roleName) as $link) {
                            ?>
                            <li class="<?php if ($link['menu_name'] == 'Delivery Address') { ?> active <?php } ?>">
                                <?php if ($link['tab']) { ?>
                                                                        <a id="lnkPass" href="<?php echo site_url('secure/my_account?cp=1'); ?>" class="clsTab"><?php echo ucfirst($link['menu_name']); ?></a>
                                <?php } else { ?>
                                    <a id="lnkInfo" href="<?php echo site_url($link['lnk']); ?>" class="clsTab"><?php echo ucfirst($link['menu_name']); ?></a>
                                <?php } ?>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>

            <div class="tab-content" style="padding-top: 10px;">
                <div style="clear: both">
                    <div class="width_100percent" id='venture_list' style="">
                      
						<?php
						$f_id = array('id' => 'f_id', 'style' => 'display:none;', 'name' => 'id', 'value' => set_value('id', $venture_id));
						$f_address_id = array('id' => 'f_address_id', 'style' => 'display:none;', 'name' => 'address_id', 'value' => set_value('id', ''));
						$f_address1 = array('id' => 'f_address', 'class' => 'span12 required', 'name' => 'address1');
						$f_city = array('id' => 'f_city', 'class' => 'span12 required', 'name' => 'city');
						$f_zip = array('id' => 'f_zip', 'maxlength' => '50', 'class' => 'span12', 'name' => 'zip');
						$f_lat = array('id' => 'f_lat', 'maxlength' => '50', 'name' => 'f_lat', 'style' => 'display:none');
						$f_long = array('id' => 'f_long', 'maxlength' => '50', 'name' => 'f_long', 'style' => 'display:none');
						$f_coverage = array('id' => 'f_coverage', 'class' => 'required', 'maxlength' => '50', 'name' => 'f_coverage', 'value' => '5');

						echo form_input($f_id);
						echo form_input($f_address_id);
						?>
						
						<div class="clsMargin addressSection thumbnail" id="my-modal">
							<form method="POST" id="adminForm-deliveryAddress" action="<?php echo site_url('secure/venture_delivery_address') ?>">
							<input type="hidden" id="paths" name="polygon_codinates" value="<?php echo isset($polygon_codinates)?$polygon_codinates:''; ?>" />
							<input type="hidden" id="polygon_latlong" name="polygon_latlong" value="<?php echo isset($polygon_latlong)?$polygon_latlong:''; ?>"  />
								<div class="modal-header">
									<h3><?php echo lang('address_form'); ?></h3>
								</div>
								<div class="modal-body">
									<div class="alert alert-danger hide" id="form-error">
										<a class="close" data-dismiss="alert">×</a>
									</div>
									<?php ?>
									<div class="row-fluid">
										<div class="span12">
											<label><strong><?php echo lang('address'); ?> : <?php  echo $ventureAddress->address; ?> </strong></label>
											
											<label><strong><?php echo lang('address_city'); ?> : <?php echo $ventureAddress->city; ?> </strong></label>
											
											<label><strong><?php echo lang('address_zip'); ?> : <?php echo $ventureAddress->zip; ?> </strong></label>
											<label><p>Select delivery area from map</p></label>
										</div>
									</div>
									
									<?php ?>
								</div>
								<div class="modal-footer">
									<img src="<?php echo site_url('assets/img/form-loader.gif') ?>" id="loader-IndertDeliveryArea" style="display:none" />
									<span class="successMessage" style="display:none">Area saved </span>
									<input  id="saveDeliveryAddress" class="btn btn-primary" type="button" value="Save" />
								</div>
							</form>
						</div>                      
                      
                    </div>

                    <div id="venture-form-container" class="hide">

                    </div>

                    <div id="venture-map-container" style="height: 600px; width: 1100px; clear: both;">

                    </div>
                </div>

        </fieldset>
    </div>
</div>

<!--<div id="venture-form-container" class="hide">

</div>-->
