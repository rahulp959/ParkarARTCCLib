<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0; padding: 0 }
  #map_canvas { height: 100% }
</style>
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://artcc.aircharts.org/includes/overlayShow.js"></script>
<script type="text/javascript" src="http://artcc.aircharts.org/includes/overlayOptions.js"></script>
<script type="text/javascript">
function initialize() {
    var myLatLng = new google.maps.LatLng(41.66904, -81.09311);
    var myOptions = {
      zoom: 4,
      panControl: false,
      zoomControl: true,
      scaleControl: false,
      streetViewControl: false,            
      center: myLatLng,
      mapTypeId: google.maps.MapTypeId.SATELLITE
    };
	var map = new google.maps.Map(document.getElementById("map_canvas"),
      myOptions);
	var artccLayer = new google.maps.KmlLayer('http://artcc.aircharts.org/centers/kzob.kml',
		{preserveViewport: true});
	artccLayer.setMap(map);
    var image = new google.maps.MarkerImage('http://maps.google.com/mapfiles/kml/pal2/icon48.png');
	var depImage = new google.maps.MarkerImage('http://maps.google.com/mapfiles/ms/micons/blue-dot.png');
    var arrImage = new google.maps.MarkerImage('http://maps.google.com/mapfiles/ms/micons/green-dot.png');
<?php
include("inc/config.php");
include("inc/PZOB.php");
$ZOB = new PZOB();
$id = $ZOB->db_safe($_REQUEST['id']);
$ZOB->db_build($db);
$ZOB->db_query($db, $res, "SELECT * FROM pilots_online WHERE callsign='$id' LIMIT 1");
$row = mysql_fetch_assoc($res);
$dep = $row['icao1'];
$dest = $row['icao2'];
$lat = $row['lat'];
$lon = $row['lon'];
$ZOB->db_query($db, $dpres, "SELECT * FROM airports WHERE icao='$dep'");
$ZOB->db_query($db, $dtres, "SELECT * FROM airports WHERE icao='$dest'");
$dprow = mysql_fetch_array($dpres);
$dtrow = mysql_fetch_array($dtres);
$dplatln = $dprow['lat'] . ", " . $dprow['lon'];
$dtlatln = $dtrow['lat'] . ", " . $dtrow['lon'];
?>
	var flightpath=[new google.maps.LatLng(<?=$dplatln?>), new google.maps.LatLng(<?=$dtlatln?>)];
	var pathoverlay = new google.maps.Polyline({
		geodesic: true,
		path: flightpath,
		strokeColor: '#ff0000',
    	strokeOpacity: 1.0,
    	strokeWeight: 2
	});
	pathoverlay.setMap(map);
	var dplatln = new google.maps.LatLng(<?=$dplatln?>);
	var dtlatln = new google.maps.LatLng(<?=$dtlatln?>);
	var depimage = new google.maps.Marker({
		position: dplatln,
		map: map,
		icon: depImage,
		title: '<?=$dep . " -- " . $dprow['airportname'];?>'
	});
	var destimage = new google.maps.Marker({
		position: dtlatln,
		map: map,
		icon: arrImage,
		title: '<?=$dest . " -- " . $dtrow['airportname'];?>'
	});
	var planepos = new google.maps.LatLng(<?=$lat?>, <?=$lon?>);
	var planeimage = new google.maps.Marker({
		position: planepos,
		map: map,
		icon: image,
		title: '<?=$id?>'
	});
	
	var bounds = new google.maps.LatLngBounds();                                                                                                     
	bounds.extend(dplatln);
	bounds.extend(dtlatln);
	map.fitBounds(bounds);
	return map;
}      
</script>
</head>
<body>
  <div id="map_canvas" style="width:100%; height:100%"></div>
  <script type="text/javascript">
	var map = initialize();
	toggleNEXRAD();
	</script>
</body>
</html>            

