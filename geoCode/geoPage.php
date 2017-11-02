<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>MarkerClusterer v3 Simple Example</title>
    <style >
      body {
        margin: 0;
        padding: 10px 20px 20px;
        font-family: Arial;
        font-size: 16px;
      }
      #map-container {
        padding: 6px;
        border-width: 1px;
        border-style: solid;
        border-color: #ccc #ccc #999 #ccc;
        -webkit-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
        -moz-box-shadow: rgba(64, 64, 64, 0.5) 0 2px 5px;
        box-shadow: rgba(64, 64, 64, 0.1) 0 2px 5px;
        width: 600px;
      }
      #map {
        width: 600px;
        height: 400px;
      }
    </style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script src="mapData.js"></script>
    <script type="text/javascript" src="markerclusterer.js"></script>


    <script>
    function initialize() {
        var center = new google.maps.LatLng(37.4419, -122.1419);

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 3,
          center: center,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
	var masterData;
		$.ajax({
		  async:false,
          url: 'getData.php', 
          type: 'POST',
          dataType:'JSON',
          data: {
                },             
      
          success: function(response){
			//console.log(response)
          	masterData=response;
          },
          error:function(){
            console.log('AJAX request was a failure');
          }
		});
	console.log(masterData);
        var markers = [];
        for (var i = 0; i < masterData; i++) {
          var dataMap = masterData[i];
          var latLng = new google.maps.LatLng(dataMap.locationLat,
              dataMap.locationLng);
          var marker = new google.maps.Marker({
            position: latLng
          });
          markers.push(marker);
        }
        var markerCluster = new MarkerClusterer(map, markers);
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>

  </head>
  <body>
    <div id="map-container"><div id="map"></div></div>

  </body>
</html>