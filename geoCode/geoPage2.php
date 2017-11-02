<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MarkerClusterer v3 Simple Example</title>
    <style>
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
            width: 1400px;
            display:inline-block;
        }

        #map {
            width: 1400px;
            height: 800px;
        }
        #resultsBkdw {
            width: 300px;
            height: 800px;
            display:inline-block;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!--<script src="traffic_accidents.json"></script>-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript" src="markerclusterer.js"></script>
    <script>
    var novaStyle;
    var infowindow;
        function initialize() {
            /* novaStyle=
            [
                {
                    "featureType": "all",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#ffffff"
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "color": "#000000"
                        },
                        {
                            "lightness": 13
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#144b53"
                        },
                        {
                            "lightness": 14
                        },
                        {
                            "weight": 1.4
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#08304b"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#0c4152"
                        },
                        {
                            "lightness": 5
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#0b434f"
                        },
                        {
                            "lightness": 25
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.fill",
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
                            "color": "#0b3d51"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#000000"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#146474"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#021019"
                        }
                    ]
                }
            ];*/

            var novaStyle=[
                    {
                        "stylers": [
                            {
                                "hue": "#ff1a00"
                            },
                            {
                                "invert_lightness": true
                            },
                            {
                                "saturation": -100
                            },
                            {
                                "lightness": 33
                            },
                            {
                                "gamma": 0.5
                            }                           
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#2D333C"
                            }
                        ]
                    },
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [
                              { visibility: "off" }
                        ]
                    } 
                ];
            var center = new google.maps.LatLng(48.1667, -100.1667);

            var map = new google.maps.Map(document.getElementById('map'), {
                mapTypeControlOptions: {
                    mapTypeIds: ['Styled']
                },                
                zoom: 3,
                center: center,
                disableDefaultUI: true, 
                mapTypeId: 'Styled',
                zoomControl: true,

            });

            var opt = {
                "legend": {
                    /*"Fatal" : "#FF0066",
                    "Very serious injuries" : "#FF9933",
                    "Serious injuries" : "#FFFF00" ,
                    "Minor injuries" : "#99FF99",
                    "No injuries" : "#66CCFF",
                    "Not recorded" : "#A5A5A5"*/
                    "Institutions" : "#0072BC",
                    "Retail" : "#FF0066",
                    //"CDN Nobos" : "#0072BC" ,
                    //"US Nobos" : "#FFFF00" ,
                    //"CDN Nobos" : "#99FF99",                    
                }
            };
          
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
            var markers = [];
            var maxIdentified=0;
            var infowindow = new google.maps.InfoWindow();
         
            for (var i = 0; i < masterData.length; i++) {
                if(Number(masterData[i]['shares'])>maxIdentified)
                {
                    maxIdentified=masterData[i]['shares'];
                }
                var investorType = masterData[i]['typeOfInvestor'];
                var typeOfInvestor = "";
                //var accident_lnglat = data.features[i].geometry["coordinates"];
                switch (investorType) {
                    case 'Registered':
                        typeOfInvestor = "Retail";
                        break;
                    case 'CDN Nobos':
                        typeOfInvestor = "Retail";
                        break;
                    case 'US Nobos':
                        typeOfInvestor = "Retail";
                        break;
                    case 'Institutions':
                        typeOfInvestor = "Institutions";
                        break;
                }
                var investorLatLng = new google.maps.LatLng(Number(masterData[i].locationLat),
                      Number(masterData[i].locationLng)); 
               // var accident_LatLng = new google.maps.LatLng(Number(accident_lnglat[1]), Number(accident_lnglat[0]));

               

                var marker = new google.maps.Marker({
                    position: investorLatLng,
                    title: typeOfInvestor,
                    investorType: investorType,
                    shares: masterData[i].shares,
                    max: maxIdentified,
                    investorName: masterData[i].name,
                    formatted_address: masterData[i].formatted_address
                });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(masterData[i]['name']+"<br><strong>"+masterData[i]['typeOfInvestor']+":</strong>&nbsp;"+masterData[i]['shares'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+" shares");
                    infowindow.open(map, marker);
                }
            })(marker, i));            
                markers.push(marker);
            }
            var styledMapType = new google.maps.StyledMapType( novaStyle, { name: 'Styled' });
            map.mapTypes.set('Styled', styledMapType);

            var markerCluster = new MarkerClusterer(map, markers, opt, maxIdentified);

            var infowindow;
            google.maps.event.addListener(markerCluster, 'clusterclick', function(cluster) {
                var zoom = this.map_.getZoom();
                var content = '';

                // Convert lat/long from cluster object to a usable MVCObject
                var info = new google.maps.MVCObject;
                info.set('position', cluster.center_);

                //----
                //Get markers
                var markers = cluster.getMarkers();

                var titles = "";
                var cont = "";
                //Get all the titles
                for(var i = 0; i < markers.length; i++) 
                {
                    titles += markers[i]['investorName'] + "\n";
                    cont += markers[i]['investorName']+"<BR><strong>"+markers[i]['investorType']+":</strong>&nbsp;"+markers[i]['shares'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+" shares<BR><BR>";
                } 
                if(zoom==20 )
                {
                    console.log(titles);
                    if(infowindow)
                    {
                        infowindow.close();
                    }
                    infowindow = new google.maps.InfoWindow();
                    infowindow.setContent(cont); //set infowindow content to titles
                    infowindow.open(map, info);                      
                 
                }

            });
            closeInfoWindow = function() {
                    infowindow.close();
            };

            google.maps.event.addListener(map, 'zoom_changed', closeInfoWindow);            
        
        }

        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(initialize);

    </script>

</head>
<body>
<h3>NovaShare Shareholder World Map</h3>

<div id="map-container">
    <div id="map"></div>
</div>
<div id="resultsBkdw">
Thomas
</div>
</body>
</html>