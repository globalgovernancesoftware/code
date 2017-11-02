<?php
session_start();
include 'mysqliConnect.php';

$table="create table issuer1.issuerMap (accountId varchar(50), typeOfInvestor varchar(50),
street_addressLN varchar(200),
routeLN varchar(200),
intersectionLN varchar(200),
politicalLN varchar(200),
countryLN varchar(200),
administrative_area_level_1LN varchar(200),
administrative_area_level_2LN varchar(200),
administrative_area_level_3LN varchar(200),
administrative_area_level_4LN varchar(200),
administrative_area_level_5LN varchar(200),
colloquial_areaLN varchar(200),
localityLN varchar(200),
wardLN varchar(200),
sublocalityLN varchar(200),
sublocality_level_1LN varchar(200),
sublocality_level_2LN varchar(200),
sublocality_level_3LN varchar(200),
sublocality_level_4LN varchar(200),
sublocality_level_5LN varchar(200),
neighborhoodLN varchar(200),
premiseLN varchar(200),
subpremiseLN varchar(200),
postal_codeLN varchar(200),
natural_featureLN varchar(200),
airportLN varchar(200),
parkLN varchar(200),
point_of_interestLN varchar(200),
floorLN varchar(200),
establishmentLN varchar(200),
parkingLN varchar(200),
post_boxLN varchar(200),
postal_townLN varchar(200),
roomLN varchar(200),
street_numberLN varchar(200),
bus_stationLN varchar(200),
train_stationLN varchar(200),
transit_stationLN varchar(200),
street_addressSN varchar(200),
routeSN varchar(200),
intersectionSN varchar(200),
politicalSN varchar(200),
countrySN varchar(200),
administrative_area_level_1SN varchar(200),
administrative_area_level_2SN varchar(200),
administrative_area_level_3SN varchar(200),
administrative_area_level_4SN varchar(200),
administrative_area_level_5SN varchar(200),
colloquial_areaSN varchar(200),
localitySN varchar(200),
wardSN varchar(200),
sublocalitySN varchar(200),
sublocality_level_1SN varchar(200),
sublocality_level_2SN varchar(200),
sublocality_level_3SN varchar(200),
sublocality_level_4SN varchar(200),
sublocality_level_5SN varchar(200),
neighborhoodSN varchar(200),
premiseSN varchar(200),
subpremiseSN varchar(200),
postal_codeSN varchar(200),
natural_featureSN varchar(200),
airportSN varchar(200),
parkSN varchar(200),
point_of_interestSN varchar(200),
floorSN varchar(200),
establishmentSN varchar(200),
parkingSN varchar(200),
post_boxSN varchar(200),
postal_townSN varchar(200),
roomSN varchar(200),
street_numberSN varchar(200),
bus_stationSN varchar(200),
train_stationSN varchar(200),
transit_stationSN varchar(200),
formatted_address varchar(200),
boundsNELat varchar(200),
boundsNELng varchar(200),
boundsSWLat varchar(200),
boundsSWLng varchar(200),
locationLat varchar(200),
locationLng varchar(200),
locationType varchar(200),
viewportNELat varchar(200),
viewportNELng varchar(200),
viewportSWLat varchar(200),
viewportSWLng varchar(200),
partial_match varchar(200),
place_id varchar(200),
typesPart varchar(200), primary key (accountId, typeOfInvestor)) CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;";

$_SESSION['userId']=1;
$_SESSION['clientId']=102;
$_SESSION['cusip']='242315109';
//$type="CDN Nobos";
//$type="US Nobos";
$type="Registered";
switch($type)
{
	case "Registered":
		$specTable="issuerReg";
		$specInvType="Registered";
		$idIn="reg";
		$type=0;
		break;
	case "CDN Nobos":
		$specTable="issuerTempCDNNobo";
		$specInvType="CDN Nobos";
		$idIn="cdn";
		$type=0;
		break;	
	case "US Nobos":
		$specTable="issuerTempUSNobo";
		$specInvType="US Nobos";
		$idIn="usn";
		$type=0;
		break;	
	case "Institutions":
		$specTable="issuerCusPeerOwn";
		$specInvType="Institutions";
		$type=1;
		$idIn="ins";

		break;			
}
if(isset($type))
{
	if($type==0)
	{
		$subTable="select * from issuer".$_SESSION['clientId'].".".$specTable." group by accountId";
	}
	else if ($type==1)
	{
		$subTable="select address, accountId  from issuer".$_SESSION['clientId'].".".$specTable." where cusip='".$_SESSION['cusip']."' and address<>''";
	}
}

if(isset($subTable))
{
	$query="insert into issuer".$_SESSION['clientId'].".issuerMap  (accountId,  typeOfInvestor, street_addressLN, routeLN, intersectionLN, politicalLN, countryLN, administrative_area_level_1LN, administrative_area_level_2LN, administrative_area_level_3LN, administrative_area_level_4LN, administrative_area_level_5LN, colloquial_areaLN, localityLN, wardLN, sublocalityLN, sublocality_level_1LN, sublocality_level_2LN, sublocality_level_3LN, sublocality_level_4LN, sublocality_level_5LN, neighborhoodLN, premiseLN, subpremiseLN, postal_codeLN, natural_featureLN, airportLN, parkLN, point_of_interestLN, floorLN, establishmentLN, parkingLN, post_boxLN, postal_townLN, roomLN, street_numberLN, bus_stationLN, train_stationLN, transit_stationLN, street_addressSN, routeSN, intersectionSN, politicalSN, countrySN, administrative_area_level_1SN, administrative_area_level_2SN, administrative_area_level_3SN, administrative_area_level_4SN, administrative_area_level_5SN, colloquial_areaSN, localitySN, wardSN, sublocalitySN, sublocality_level_1SN, sublocality_level_2SN, sublocality_level_3SN, sublocality_level_4SN, sublocality_level_5SN, neighborhoodSN, premiseSN, subpremiseSN, postal_codeSN, natural_featureSN, airportSN, parkSN, point_of_interestSN, floorSN, establishmentSN, parkingSN, post_boxSN, postal_townSN, roomSN, street_numberSN, bus_stationSN, train_stationSN, transit_stationSN, formatted_address, boundsNELat, boundsNELng, boundsSWLat, boundsSWLng, locationLat, locationLng, locationType, viewportNELat, viewportNELng, viewportSWLat, viewportSWLng, partial_match, place_id, typesPart)
	select accountId, '".$specInvType."', street_addressLN, routeLN, intersectionLN, politicalLN, countryLN, administrative_area_level_1LN, administrative_area_level_2LN, administrative_area_level_3LN, administrative_area_level_4LN, administrative_area_level_5LN, colloquial_areaLN, localityLN, wardLN, sublocalityLN, sublocality_level_1LN, sublocality_level_2LN, sublocality_level_3LN, sublocality_level_4LN, sublocality_level_5LN, neighborhoodLN, premiseLN, subpremiseLN, postal_codeLN, natural_featureLN, airportLN, parkLN, point_of_interestLN, floorLN, establishmentLN, parkingLN, post_boxLN, postal_townLN, roomLN, street_numberLN, bus_stationLN, train_stationLN, transit_stationLN, street_addressSN, routeSN, intersectionSN, politicalSN, countrySN, administrative_area_level_1SN, administrative_area_level_2SN, administrative_area_level_3SN, administrative_area_level_4SN, administrative_area_level_5SN, colloquial_areaSN, localitySN, wardSN, sublocalitySN, sublocality_level_1SN, sublocality_level_2SN, sublocality_level_3SN, sublocality_level_4SN, sublocality_level_5SN, neighborhoodSN, premiseSN, subpremiseSN, postal_codeSN, natural_featureSN, airportSN, parkSN, point_of_interestSN, floorSN, establishmentSN, parkingSN, post_boxSN, postal_townSN, roomSN, street_numberSN, bus_stationSN, train_stationSN, transit_stationSN, formatted_address, boundsNELat, boundsNELng, boundsSWLat, boundsSWLng, locationLat, locationLng, locationType, viewportNELat, viewportNELng, viewportSWLat, viewportSWLng, partial_match, place_id, typesPart
	 from (
		select accountId, id from (".$subTable.") as t2
		inner join novaGeo.geoQueue as t on uniqueAddress=address and left(accountNr,3)='".$idIn."'
	)as t3
		inner join novaGeo.parsedGeo  on id=adId
	ON DUPLICATE KEY UPDATE street_addressLN=VALUES(street_addressLN),
		routeLN=VALUES(routeLN),intersectionLN=VALUES(intersectionLN),
		politicalLN=VALUES(politicalLN),countryLN=VALUES(countryLN),
		administrative_area_level_1LN=VALUES(administrative_area_level_1LN),
		administrative_area_level_2LN=VALUES(administrative_area_level_2LN),
		administrative_area_level_3LN=VALUES(administrative_area_level_3LN),
		administrative_area_level_4LN=VALUES(administrative_area_level_4LN),
		administrative_area_level_5LN=VALUES(administrative_area_level_5LN),
		colloquial_areaLN=VALUES(colloquial_areaLN),localityLN=VALUES(localityLN),
		wardLN=VALUES(wardLN),sublocalityLN=VALUES(sublocalityLN),sublocality_level_1LN=VALUES(sublocality_level_1LN),
		sublocality_level_2LN=VALUES(sublocality_level_2LN),sublocality_level_3LN=VALUES(sublocality_level_3LN),
		sublocality_level_4LN=VALUES(sublocality_level_4LN),sublocality_level_5LN=VALUES(sublocality_level_5LN),
		neighborhoodLN=VALUES(neighborhoodLN),premiseLN=VALUES(premiseLN),
		subpremiseLN=VALUES(subpremiseLN),postal_codeLN=VALUES(postal_codeLN),
		natural_featureLN=VALUES(natural_featureLN),airportLN=VALUES(airportLN),
		parkLN=VALUES(parkLN),point_of_interestLN=VALUES(point_of_interestLN),
		floorLN=VALUES(floorLN),establishmentLN=VALUES(establishmentLN),
		parkingLN=VALUES(parkingLN),post_boxLN=VALUES(post_boxLN),
		postal_townLN=VALUES(postal_townLN),roomLN=VALUES(roomLN),
		street_numberLN=VALUES(street_numberLN),bus_stationLN=VALUES(bus_stationLN),
		train_stationLN=VALUES(train_stationLN),transit_stationLN=VALUES(transit_stationLN),
		street_addressSN=VALUES(street_addressSN),routeSN=VALUES(routeSN),intersectionSN=VALUES(intersectionSN),
		politicalSN=VALUES(politicalSN),countrySN=VALUES(countrySN),
		administrative_area_level_1SN=VALUES(administrative_area_level_1SN),
		administrative_area_level_2SN=VALUES(administrative_area_level_2SN),
		administrative_area_level_3SN=VALUES(administrative_area_level_3SN),
		administrative_area_level_4SN=VALUES(administrative_area_level_4SN),
		administrative_area_level_5SN=VALUES(administrative_area_level_5SN),
		colloquial_areaSN=VALUES(colloquial_areaSN),localitySN=VALUES(localitySN),
		wardSN=VALUES(wardSN),sublocalitySN=VALUES(sublocalitySN),
		sublocality_level_1SN=VALUES(sublocality_level_1SN),sublocality_level_2SN=VALUES(sublocality_level_2SN),
		sublocality_level_3SN=VALUES(sublocality_level_3SN),sublocality_level_4SN=VALUES(sublocality_level_4SN),
		sublocality_level_5SN=VALUES(sublocality_level_5SN),neighborhoodSN=VALUES(neighborhoodSN),
		premiseSN=VALUES(premiseSN),subpremiseSN=VALUES(subpremiseSN),
		postal_codeSN=VALUES(postal_codeSN),natural_featureSN=VALUES(natural_featureSN),
		airportSN=VALUES(airportSN),parkSN=VALUES(parkSN),point_of_interestSN=VALUES(point_of_interestSN),
		floorSN=VALUES(floorSN),establishmentSN=VALUES(establishmentSN),parkingSN=VALUES(parkingSN),
		post_boxSN=VALUES(post_boxSN),postal_townSN=VALUES(postal_townSN),roomSN=VALUES(roomSN),
		street_numberSN=VALUES(street_numberSN),bus_stationSN=VALUES(bus_stationSN),train_stationSN=VALUES(train_stationSN),
		transit_stationSN=VALUES(transit_stationSN),formatted_address=VALUES(formatted_address),boundsNELat=VALUES(boundsNELat),
		boundsNELng=VALUES(boundsNELng),boundsSWLat=VALUES(boundsSWLat),boundsSWLng=VALUES(boundsSWLng),
		locationLat=VALUES(locationLat),locationLng=VALUES(locationLng),locationType=VALUES(locationType),
		viewportNELat=VALUES(viewportNELat),viewportNELng=VALUES(viewportNELng),viewportSWLat=VALUES(viewportSWLat),
		viewportSWLng=VALUES(viewportSWLng),partial_match=VALUES(partial_match),place_id=VALUES(place_id),typesPart=VALUES(typesPart);";


	mysqli_query($con,$query) or die(mysqli_error($con));
}


?>