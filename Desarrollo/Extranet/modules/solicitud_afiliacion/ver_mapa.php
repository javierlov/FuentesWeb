<html>
	<head>
		<script type="text/javascript" src="/modules/solicitud_afiliacion/js/jquery.min.js"></script>
		<script type="text/javascript" src="/modules/solicitud_afiliacion/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRxC6Y4f-j6nECyHWigtBATtJyXyha-XU&libraries=adsense&sensor=true&language=es"></script>

		<style>
			* {margin:0; padding:0;}
			#map_canvas {height:100px; width:100%;}
		</style>
	</head>

	<body>
		<div id="map_canvas"></div>

		<script type='text/javascript'>
			var geocoder;
			var map = null;
			var infoWindow = null;

			function openInfoWindow(marker) {
				var markerLatLng = marker.getPosition();
				infoWindow.setContent([
					'<strong>La posicion del marcador es:</strong><br/>',
					markerLatLng.lat(),
					', ',
					markerLatLng.lng(),
					'<br/>Arrástrame para actualizar la posición.'
				].join(''));
//				infoWindow.open(map, marker);

				with (window.parent.document) {
					getElementById('latitud').value = markerLatLng.lat();
					getElementById('longitud').value = markerLatLng.lng();
				}
			}

			function initialize() {
				// Si no tiene cargada la latitud o la longitud, muestro en el mapa el domicilio cargado..
				if ((window.parent.document.getElementById('latitud').value == '') || (window.parent.document.getElementById('longitud').value == '')) {
					geocoder = new google.maps.Geocoder();
					geocoder.geocode({'address': '<?= $_REQUEST["d"]?>'}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							map.setCenter(results[0].geometry.location);
							var marker = new google.maps.Marker({
						position: myLatlng,
						draggable: true,
						map: map,
						title:"Ejemplo marcador arrastrable",
								position: results[0].geometry.location
							});
						}
						else
							alert('Geocode was not successful for the following reason: ' + status);
					
						google.maps.event.addListener(marker, 'dragend', function(){openInfoWindow(marker);});
						google.maps.event.addListener(marker, 'click', function(){openInfoWindow(marker);});
					});
				}

				var myLatlng = new google.maps.LatLng(window.parent.document.getElementById('latitud').value, window.parent.document.getElementById('longitud').value);
				var myOptions = {
					zoom: 13,
					center: myLatlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}

				map = new google.maps.Map($("#map_canvas").get(0), myOptions);

				infoWindow = new google.maps.InfoWindow();

				var marker = new google.maps.Marker({
					position: myLatlng,
					draggable: true,
					map: map,
					title:"Ejemplo marcador arrastrable"
				});

				google.maps.event.addListener(marker, 'dragend', function(){openInfoWindow(marker);});
				google.maps.event.addListener(marker, 'click', function(){openInfoWindow(marker);});
			}

			$(document).ready(function() {
				initialize();
				document.getElementById('map_canvas').style.height = window.innerHeight + 'px';
			});
		</script>
	</body>
</html>