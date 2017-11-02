<?php

//Create table

$db="novaGeo";
$table="geoQueue";

$query1="create table if not exists ".$db.".".$table."  (id bigint auto_increment, uniqueAddress varchar(500), status int(0), primary key (id));";

mysqli_query($con,$query1) or die(mysqli_error($con));

$table="parsedGeo";

$query2="create table if not exists ".$db.".".$table."  (adId bigint(20), street_addressLN varchar(200), routeLN varchar(200), intersectionLN varchar(200), politicalLN varchar(200), countryLN varchar(200), 
administrative_area_level_1LN varchar(200), administrative_area_level_2LN varchar(200), administrative_area_level_3LN varchar(200), administrative_area_level_4LN varchar(200), 
administrative_area_level_5LN varchar(200), colloquial_areaLN varchar(200), localityLN varchar(200), wardLN varchar(200), sublocalityLN varchar(200), 
sublocality_level_1LN varchar(200), sublocality_level_2LN varchar(200), sublocality_level_3LN varchar(200), sublocality_level_4LN varchar(200), 
sublocality_level_5LN varchar(200), neighborhoodLN varchar(200), premiseLN varchar(200), subpremiseLN varchar(200), postal_codeLN varchar(200), 
natural_featureLN varchar(200), airportLN varchar(200), parkLN varchar(200), point_of_interestLN varchar(200), floorLN varchar(200), 
establishmentLN varchar(200), parkingLN varchar(200), post_boxLN varchar(200), postal_townLN varchar(200), roomLN varchar(200), 
street_numberLN varchar(200), bus_stationLN varchar(200), train_stationLN varchar(200), transit_stationLN varchar(200), 
street_addressSN varchar(200), routeSN varchar(200), intersectionSN varchar(200), politicalSN varchar(200), countrySN varchar(200), 
administrative_area_level_1SN varchar(200), administrative_area_level_2SN varchar(200), administrative_area_level_3SN varchar(200), 
administrative_area_level_4SN varchar(200), administrative_area_level_5SN varchar(200), colloquial_areaSN varchar(200), localitySN varchar(200), 
wardSN varchar(200), sublocalitySN varchar(200), sublocality_level_1SN varchar(200), sublocality_level_2SN varchar(200), sublocality_level_3SN varchar(200), 
sublocality_level_4SN varchar(200), sublocality_level_5SN varchar(200), neighborhoodSN varchar(200), premiseSN varchar(200), subpremiseSN varchar(200), 
postal_codeSN varchar(200), natural_featureSN varchar(200), airportSN varchar(200), parkSN varchar(200), point_of_interestSN varchar(200), floorSN varchar(200),
establishmentSN varchar(200), parkingSN varchar(200), post_boxSN varchar(200), postal_townSN varchar(200), roomSN varchar(200), street_numberSN varchar(200), 
bus_stationSN varchar(200), train_stationSN varchar(200), transit_stationSN varchar(200), formatted_address varchar(200), boundsNELat varchar(200), 
boundsNELng varchar(200), boundsSWLat varchar(200), boundsSWLng varchar(200), locationLat varchar(200), locationLng varchar(200), locationType varchar(200), 
viewportNELat varchar(200), viewportNELng varchar(200), viewportSWLat varchar(200), viewportSWLng varchar(200), partial_match varchar(200), place_id varchar(200), 
typesPart varchar(200),  FOREIGN KEY (adId) REFERENCES geoQueue (id));";

mysqli_query($con,$query2) or die(mysqli_error($con));



//$folder="../../googleFullResults/googleResults";
//$dir=array_values(array_diff(scandir($folder), array('.', '..')));

?>