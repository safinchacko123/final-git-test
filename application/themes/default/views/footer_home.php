<!--=======footer=================================-->
<footer id="footer">
	<div class="full-width-container block-8">
		<div class="container">
			<div class="row">
				<div class="grid_3">
					<article>
						<h2>Archive</h2>
						<ul>
							<li><a href="#">May 2014</a></li>
							<li><a href="#">April 2014</a></li>
							<li><a href="#">March 2014</a></li>
							<li><a href="#">June 2014</a></li>
							<li><a href="#">July 2014</a></li>
							<li><a href="#">January 2014</a></li>
							<li><a href="#">December 2014</a></li>
						</ul>
					</article>
				</div>
				<div class="grid_3">
					<article>
						<h2>Contacts</h2>
						<div class="element">
							<p>9870 St Vincent Place, <br>Glasgow, DC 45 Fr 45.</p>
							<span class="phone">Freephone:  +1 800 559 6580</span>
						</div>
						<div class="element">
							<p>9870 St Vincent Place, <br>Glasgow, DC 45 Fr 45.</p>
							<span class="phone">Freephone:  +1 800 559 6580</span>
						</div>
						<div class="element">
							<p>9870 St Vincent Place, <br>Glasgow, DC 45 Fr 45.</p>
							<span class="phone">Freephone:  +1 800 559 6580</span>
						</div>
					</article>
				</div>
				<div class="grid_3">
					<article>
						<h2>About</h2>
						<div class="owl-carousel">
							<div class="item">
								<div class="content">Lorem ipsum dolor sit amet cons ectetuer adipiscing elit. Praesent ves tibulum molestie lacus. Aenean nonummy hendrerit mauris. Phasellus porta. Fusce suscipit varius mi.</div>
							</div>
							<div class="item">
								<div class="content">Lorem ipsum dolor sit amet cons ectetuer adipiscing elit. Praesent ves tibulum molestie lacus. Aenean nonummy hendrerit mauris. Phasellus porta. Fusce suscipit varius mi.</div>
							</div>
							<div class="item">
								<div class="content">Lorem ipsum dolor sit amet cons ectetuer adipiscing elit. Praesent ves tibulum molestie lacus. Aenean nonummy hendrerit mauris. Phasellus porta. Fusce suscipit varius mi.</div>
							</div>
						</div>
					</article>
				</div>
				<div class="grid_3">
					<article>
						<h2>Newsletter</h2>
						<div id="newsletter">
							<form id="subscribe-form">
								<div class="success">Your subscription request has been sent!</div>
									<fieldset>
										<label class="email">
											<input type="email" value="" placeholder="Your email address">
											<span class="error">*This is not a valid email address.</span>
										</label>
										<div class="btns"><a href="#" class="subscribe_btn" data-type="submit">go!</a></div>
								</fieldset>
							</form>
						</div>
					</article>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="grid_12 copyright">
					<pre>Food Grocery Pharma © <span id="copyright-year"></span>  •  <a href="#">Privacy Policy</a></pre>
					<div class="social">
						<a href="#"><span class="bd-ra fa fa-google-plus"></span></a>
						<a href="#"><span class="bd-ra fa fa-pinterest "></span></a>
						<a href="#"><span class="bd-ra fa fa-twitter"></span></a>
						<a href="#"><span class="bd-ra fa fa-facebook"></span></a>
						<a href="#"><span class="bd-ra fa fa-instagram"></span></a>
					</div>
					<!--{%FOOTER_LINK} -->
				</div>
			</div>
		</div>
	</div>
</footer>
<script>
	$(document).ready(function() {
		$(".owl-carousel").owlCarousel({
			navigation: true,
			pagination: false,
			singleItem:true,
			navigationText: false
		});
	});
</script>

<script>
	jQuery(function(){
		jQuery('#camera_wrap').camera({
			height: '46.875%',
			thumbnails: false,
			pagination: true,
			fx: 'simpleFade',
			loader: 'none',
			hover: false,
			navigation: false,
			playPause: false,
			minHeight: "370px"
		});
	});
</script>
<script>
	$(window).load(function(){
		$('#subscribe-form').sForm({
			ownerEmail:'#',
			sitename:'sitename.link'
		})
	})
</script>
<script type="text/javascript">
		google_api_map_init();
		function google_api_map_init(){
			var map;
			var coordData = new google.maps.LatLng(parseFloat(41.665030), parseFloat(-87.438081,10)); 
			var markCoord1 = new google.maps.LatLng(parseFloat(41.653402), parseFloat(-87.622756,14));
			var markCoord2 = new google.maps.LatLng(parseFloat(41.602127), parseFloat(-87.892608,14));
			var markCoord3 = new google.maps.LatLng(parseFloat(41.675452), parseFloat(-86.850792,14));
			var markCoord4 = new google.maps.LatLng(parseFloat(41.659051), parseFloat(-86.507644,14));
			var marker; 

			var styleArray = [
				{
					"featureType": "administrative",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#000000"
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "labels.text.stroke",
					"stylers": [
						{
							"color": "#ffffff"
						},
						{
							"weight": "5.0"
						}
					]
				},
				{
					"featureType": "landscape.man_made",
					"elementType": "all",
					"stylers": [
						{
							"color": "#e5d8c9"
						}
					]
				},
				{
					"featureType": "landscape.natural",
					"elementType": "all",
					"stylers": [
						{
							"color": "#e5d8c9"
						}
					]
				},
				{
					"featureType": "landscape.natural.landcover",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "on"
						},
						{
							"hue": "#0bff00"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "on"
						},
						{
							"color": "#ffffff"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "labels.text",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"visibility": "on"
						},
						{
							"color": "#cec3b8"
						}
					]
				},
				{
					"featureType": "road.arterial",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#000000"
						}
					]
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"visibility": "on"
						},
						{
							"color": "#aaaaaa"
						}
					]
				},
				{
					"featureType": "road.local",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#000000"
						},
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "transit",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "all",
					"stylers": [
						{
							"color": "#6dacd6"
						}
					]
				}
			]
			 
			var markerIcon = { 
				url: "images/gmap_marker.png", 
				size: new google.maps.Size(42, 65), 
				origin: new google.maps.Point(0,0), 
				anchor: new google.maps.Point(21, 70) 
			}; 
			function initialize() { 
			  var mapOptions = { 
				zoom: 10, 
				center: coordData, 
				scrollwheel: false, 
				styles: styleArray 
			  } 
 
			  var contentString = "<div></div>"; 
			  var infowindow = new google.maps.InfoWindow({ 
				content: contentString, 
				maxWidth: 200 
			  }); 
			   
			  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions); 
			  marker = new google.maps.Marker({ 
				map:map, 
				position: markCoord1, 
				icon: markerIcon
			  }); 

			  marker1 = new google.maps.Marker({ 
				map:map, 
				position: markCoord2, 
				icon: markerIcon
			  }); 

			   marker2 = new google.maps.Marker({ 
				map:map, 
				position: markCoord3, 
				icon: markerIcon
			  }); 

			   marker3 = new google.maps.Marker({ 
				map:map, 
				position: markCoord4, 
				icon: markerIcon
			  }); 



			google.maps.event.addDomListener(window, 'resize', function() {

			  map.setCenter(coordData);

			  var center = map.getCenter();
			});
		  }

			google.maps.event.addDomListener(window, "load", initialize); 

		  } 

	  </script>
</body>
</html>