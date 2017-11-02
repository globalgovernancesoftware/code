<?php

include 'mysqliConnect.php';
mysqli_Select_db($con, "novaGeo");

//Folder WHERE FILES ARE
$folder="geoCodeDwnl";

//SUMMARY

//1-DOWNLOAD DATA
//2-PARSE DATA
//3-REDOWNLOAD DATA WITH NO RESPONSE 



// 1) DOWNLOAD DATA

//$partialTryNumber=0;
function downloadGeoData($fol,$connection,$statusType)
{
	//GLOBAL $partialTryNumber;

	$api="AIzaSyAnbf3JJQUH5GBLbMeLv2F5NLvlBoccsO0";
	$min=1;
	$max=100;

	$query="select id, replace(replace(replace(trim(uniqueAddress),'  ',' '),'  ',''),'  ','') as address from novaGeo.geoQueue where status='".$statusType."';";
	$queryIt=mysqli_query($connection,$query) or die(mysqli_error($connection));

	while($row=mysqli_fetch_array($queryIt))
	{
		$mainId[]=$row['id'];
		$address[]=str_replace("++","+",str_replace("++","+",str_replace(" ","+",str_replace("  ", " ", preg_replace("/[^A-Za-z0-9  \-]/", '', $row['address'])))));
	}

	if(isset($mainId))
	{
		if($statusType==2)
		{
			$finalId=array();
			$finalAddress=array();		
			for($i=0;$i<count($address);$i++)
			{
				$finAddress="";
				echo $address[$i]."\n";
				$newAddressA=explode("+",$address[$i]);
				$countIt=count($newAddressA);
				echo $countIt."\n";

				if($countIt<5)
				{
					$min=2;
				}
				elseif($countIt<10)
				{
					$min=$countIt-3;
				}
				else
				{
					$min=5;
				}
				echo $min."\n";

				for($j=$countIt-$min; $j<$countIt;$j++)
				{
					$finAddress.=$newAddressA[$j]."+";
				}
				echo $finAddress."\n";

				if(strlen($finAddress)>3)
				{
					$finalAddress[]=substr($finAddress,0,-1);
					$finalId[]=$mainId[$i];
				}
			}
			print_r($finalAddress);
			//$partialTryNumber++;
		}	
		elseif($statusType==0)
		{
			$finalId=array();
			$finalAddress=array();
			$finalId=$mainId;
			$finalAddress=$address;
		}

		for($i=0;$i<count($finalId);$i++)
		{
			$apiQuery="https://maps.googleapis.com/maps/api/geocode/json?address=".$finalAddress[$i]."&key=".$api;
			echo $apiQuery."\n";
			$data=@file_get_contents($apiQuery);
				//$data=false;
			if($data===false)
			{
				sleep(10);
				$i=$i-1;
			}
			else
			{
				file_put_contents($fol."/".$finalId[$i].".json", $data);
				$values[]=$finalId[$i];
				$randNr=rand($min,$max)/100;	
				//sleep(1+$randNr);
				echo "Done: ".$finalId[$i]."\n";
			}
		}	
		return "Yes";
	}	
	else
	{
		return "No";
	}
}





// 2)PARSE DATA
	//GLOBAL VARIABLES
	$finalOK=array();

function parseGeoData($fol,$connection){
	global $finalOK;
	$finalOK=array();
	$fields="(adId,street_addressLN,routeLN,intersectionLN,politicalLN,countryLN,administrative_area_level_1LN,administrative_area_level_2LN,administrative_area_level_3LN,administrative_area_level_4LN,administrative_area_level_5LN,colloquial_areaLN,localityLN,wardLN,sublocalityLN,sublocality_level_1LN,sublocality_level_2LN,sublocality_level_3LN,sublocality_level_4LN,sublocality_level_5LN,neighborhoodLN,premiseLN,subpremiseLN,postal_codeLN,natural_featureLN,airportLN,parkLN,point_of_interestLN,floorLN,establishmentLN,parkingLN,post_boxLN,postal_townLN,roomLN,street_numberLN,bus_stationLN,train_stationLN,transit_stationLN,street_addressSN,routeSN,intersectionSN,politicalSN,countrySN,administrative_area_level_1SN,administrative_area_level_2SN,administrative_area_level_3SN,administrative_area_level_4SN,administrative_area_level_5SN,colloquial_areaSN,localitySN,wardSN,sublocalitySN,sublocality_level_1SN,sublocality_level_2SN,sublocality_level_3SN,sublocality_level_4SN,sublocality_level_5SN,neighborhoodSN,premiseSN,subpremiseSN,postal_codeSN,natural_featureSN,airportSN,parkSN,point_of_interestSN,floorSN,establishmentSN,parkingSN,post_boxSN,postal_townSN,roomSN,street_numberSN,bus_stationSN,train_stationSN,transit_stationSN,formatted_address,boundsNELat,boundsNELng,boundsSWLat,boundsSWLng,locationLat,locationLng,locationType,viewportNELat,viewportNELng,viewportSWLat,viewportSWLng,partial_match,place_id,typesPart)";

	$dir=array_values(array_diff(scandir($fol), array('.', '..')));

	$values="";

	$OK=array();
	$ZERO_RESULTS=array();
	$OVER_QUERY_LIMIT=array();
	$REQUEST_DENIED=array();
	$INVALID_REQUEST=array();
	$UNKNOWN_ERROR=array();

	for($i=0;$i<count($dir);$i++)
	{

		//DECLARE ALL VARIABLES 
		$street_addressLN="null";
		$routeLN="null";
		$intersectionLN="null";
		$politicalLN="null";
		$countryLN="null";
		$administrative_area_level_1LN="null";
		$administrative_area_level_2LN="null";
		$administrative_area_level_3LN="null";
		$administrative_area_level_4LN="null";
		$administrative_area_level_5LN="null";
		$colloquial_areaLN="null";
		$localityLN="null";
		$wardLN="null";
		$sublocalityLN="null";
		$sublocality_level_1LN="null";
		$sublocality_level_2LN="null";
		$sublocality_level_3LN="null";
		$sublocality_level_4LN="null";
		$sublocality_level_5LN="null";
		$neighborhoodLN="null";
		$premiseLN="null";
		$subpremiseLN="null";
		$postal_codeLN="null";
		$natural_featureLN="null";
		$airportLN="null";
		$parkLN="null";
		$point_of_interestLN="null";
		$floorLN="null";
		$establishmentLN="null";
		$parkingLN="null";
		$post_boxLN="null";
		$postal_townLN="null";
		$roomLN="null";
		$street_numberLN="null";
		$bus_stationLN="null";
		$train_stationLN="null";
		$transit_stationLN="null";
		$street_addressSN="null";
		$routeSN="null";
		$intersectionSN="null";
		$politicalSN="null";
		$countrySN="null";
		$administrative_area_level_1SN="null";
		$administrative_area_level_2SN="null";
		$administrative_area_level_3SN="null";
		$administrative_area_level_4SN="null";
		$administrative_area_level_5SN="null";
		$colloquial_areaSN="null";
		$localitySN="null";
		$wardSN="null";
		$sublocalitySN="null";
		$sublocality_level_1SN="null";
		$sublocality_level_2SN="null";
		$sublocality_level_3SN="null";
		$sublocality_level_4SN="null";
		$sublocality_level_5SN="null";
		$neighborhoodSN="null";
		$premiseSN="null";
		$subpremiseSN="null";
		$postal_codeSN="null";
		$natural_featureSN="null";
		$airportSN="null";
		$parkSN="null";
		$point_of_interestSN="null";
		$floorSN="null";
		$establishmentSN="null";
		$parkingSN="null";
		$post_boxSN="null";
		$postal_townSN="null";
		$roomSN="null";
		$street_numberSN="null";
		$bus_stationSN="null";
		$train_stationSN="null";
		$transit_stationSN="null";

		//FORMATTED ADDRESS PART
		$formatted_address="null";

		//GEOMETRY PART
		$boundsNELat="null";
		$boundsNELng="null";
		$boundsSWLat="null";
		$boundsSWLng="null";
		$locationLat="null";
		$locationLng="null";
		$locationType="null";
		$viewportNELat="null";
		$viewportNELng="null";
		$viewportSWLat="null";
		$viewportSWLng="null";

		//PARTIAL MATCH PART
		$partial_match="null";

		//PLACE_ID
		$place_id="null";

		//FINAL TYPES PART
		$typesPart="null";

		$file=file_get_contents($fol."/".$dir[$i]);
		$fileName=str_replace(".json", "", $dir[$i]);
		$arr=json_decode($file);
		if(isset($arr->results)  && isset($arr->status))
		{
			if($arr->status=="OK")
			{
				//ADDRESS PART
				$var=$arr->results[0]->address_components;
				$countVar=count($var);
				for($j=0;$j<$countVar;$j++)
				{
					$countSubVar=count($var[$j]->types);
					for($k=0;$k<1;$k++)
					{
						switch($var[$j]->types[$k])
						{
							case  'street_address': $street_addressLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $street_addressSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'route': $routeLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $routeSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'intersection': $intersectionLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $intersectionSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'political': $politicalLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $politicalSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'country': $countryLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $countrySN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'administrative_area_level_1': $administrative_area_level_1LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $administrative_area_level_1SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'administrative_area_level_2': $administrative_area_level_2LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $administrative_area_level_2SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'administrative_area_level_3': $administrative_area_level_3LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $administrative_area_level_3SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'administrative_area_level_4': $administrative_area_level_4LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $administrative_area_level_4SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'administrative_area_level_5': $administrative_area_level_5LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $administrative_area_level_5SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'colloquial_area': $colloquial_areaLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $colloquial_areaSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'locality': $localityLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $localitySN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'ward': $wardLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $wardSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'sublocality': $sublocalityLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $sublocalitySN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'sublocality_level_1': $sublocality_level_1LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $sublocality_level_1SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'sublocality_level_2': $sublocality_level_2LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $sublocality_level_2SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'sublocality_level_3': $sublocality_level_3LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $sublocality_level_3SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'sublocality_level_4': $sublocality_level_4LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $sublocality_level_4SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'sublocality_level_5': $sublocality_level_5LN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $sublocality_level_5SN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'neighborhood': $neighborhoodLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $neighborhoodSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'premise': $premiseLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $premiseSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'subpremise': $subpremiseLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $subpremiseSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'postal_code': $postal_codeLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $postal_codeSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'natural_feature': $natural_featureLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $natural_featureSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'airport': $airportLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $airportSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'park': $parkLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $parkSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'point_of_interest': $point_of_interestLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $point_of_interestSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'floor': $floorLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $floorSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'establishment': $establishmentLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $establishmentSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'parking': $parkingLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $parkingSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'post_box': $post_boxLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $post_boxSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'postal_town': $postal_townLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $postal_townSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'room': $roomLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $roomSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'street_number': $street_numberLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $street_numberSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'bus_station': $bus_stationLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $bus_stationSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'train_station': $train_stationLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $train_stationSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;
							case  'transit_station': $transit_stationLN='"'.mysqli_real_escape_string($connection,trim($var[$j]->long_name)).'"'; $transit_stationSN='"'.mysqli_real_escape_string($connection,trim($var[$j]->short_name)).'"';break;

						}
					}
				}

				//FORMATTED ADDRESS PART
				$formatted_address=isset($arr->results[0]->formatted_address)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->formatted_address)).'"' : "null";

				//GEOMETRY PART
				$boundsNELat=isset($arr->results[0]->geometry->bounds->northeast->lat)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->bounds->northeast->lat)).'"' : "null" ;
				$boundsNELng=isset($arr->results[0]->geometry->bounds->northeast->lng)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->bounds->northeast->lng)).'"' : "null" ;
				$boundsSWLat=isset($arr->results[0]->geometry->bounds->southwest->lat)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->bounds->southwest->lat)).'"' : "null" ;
				$boundsSWLng=isset($arr->results[0]->geometry->bounds->southwest->lng)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->bounds->southwest->lng)).'"' : "null" ;
				$locationLat=isset($arr->results[0]->geometry->location->lat)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->location->lat)).'"' : "null" ;
				$locationLng=isset($arr->results[0]->geometry->location->lng)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->location->lng)).'"' : "null" ;
				$locationType=isset($arr->results[0]->geometry->location_type)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->location_type)).'"' : "null" ;
				$viewportNELat=isset($arr->results[0]->geometry->viewport->northeast->lat)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->viewport->northeast->lat)).'"' : "null" ;
				$viewportNELng=isset($arr->results[0]->geometry->viewport->northeast->lng)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->viewport->northeast->lng)).'"' : "null" ;
				$viewportSWLat=isset($arr->results[0]->geometry->viewport->southwest->lat)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->viewport->southwest->lat)).'"' : "null" ;
				$viewportSWLng=isset($arr->results[0]->geometry->viewport->southwest->lng)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->geometry->viewport->southwest->lng)).'"' : "null" ;

				//PARTIAL MATCH PART
				$partial_match=isset($arr->results[0]->partial_match)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->partial_match)).'"' : "null" ;

				//PLACE_ID
				$place_id=isset($arr->results[0]->place_id)? '"'.mysqli_real_escape_string($connection,trim($arr->results[0]->place_id )).'"': "null" ;

				//FINAL TYPES PART
				$typesPart='"'.mysqli_real_escape_string($connection,trim(implode(";", $arr->results[0]->types))).'"';	
				

				//MYSQL VALUES
				$values.='('.$fileName.','.$street_addressLN.','.
							$routeLN.','.
							$intersectionLN.','.
							$politicalLN.','.
							$countryLN.','.
							$administrative_area_level_1LN.','.
							$administrative_area_level_2LN.','.
							$administrative_area_level_3LN.','.
							$administrative_area_level_4LN.','.
							$administrative_area_level_5LN.','.
							$colloquial_areaLN.','.
							$localityLN.','.
							$wardLN.','.
							$sublocalityLN.','.
							$sublocality_level_1LN.','.
							$sublocality_level_2LN.','.
							$sublocality_level_3LN.','.
							$sublocality_level_4LN.','.
							$sublocality_level_5LN.','.
							$neighborhoodLN.','.
							$premiseLN.','.
							$subpremiseLN.','.
							$postal_codeLN.','.
							$natural_featureLN.','.
							$airportLN.','.
							$parkLN.','.
							$point_of_interestLN.','.
							$floorLN.','.
							$establishmentLN.','.
							$parkingLN.','.
							$post_boxLN.','.
							$postal_townLN.','.
							$roomLN.','.
							$street_numberLN.','.
							$bus_stationLN.','.
							$train_stationLN.','.
							$transit_stationLN.','.
							$street_addressSN.','.
							$routeSN.','.
							$intersectionSN.','.
							$politicalSN.','.
							$countrySN.','.
							$administrative_area_level_1SN.','.
							$administrative_area_level_2SN.','.
							$administrative_area_level_3SN.','.
							$administrative_area_level_4SN.','.
							$administrative_area_level_5SN.','.
							$colloquial_areaSN.','.
							$localitySN.','.
							$wardSN.','.
							$sublocalitySN.','.
							$sublocality_level_1SN.','.
							$sublocality_level_2SN.','.
							$sublocality_level_3SN.','.
							$sublocality_level_4SN.','.
							$sublocality_level_5SN.','.
							$neighborhoodSN.','.
							$premiseSN.','.
							$subpremiseSN.','.
							$postal_codeSN.','.
							$natural_featureSN.','.
							$airportSN.','.
							$parkSN.','.
							$point_of_interestSN.','.
							$floorSN.','.
							$establishmentSN.','.
							$parkingSN.','.
							$post_boxSN.','.
							$postal_townSN.','.
							$roomSN.','.
							$street_numberSN.','.
							$bus_stationSN.','.
							$train_stationSN.','.
							$transit_stationSN.','.
							$formatted_address.','.
							$boundsNELat.','.
							$boundsNELng.','.
							$boundsSWLat.','.
							$boundsSWLng.','.
							$locationLat.','.
							$locationLng.','.
							$locationType.','.
							$viewportNELat.','.
							$viewportNELng.','.
							$viewportSWLat.','.
							$viewportSWLng.','.
							$partial_match.','.
							$place_id.','.
							$typesPart.'),';
				
				$OK[]=$fileName;
				$finalOK[]=$fileName;
			}
			elseif($arr->status=="ZERO_RESULTS")
			{
				$ZERO_RESULTS[]=$fileName;
			}
			elseif($arr->status=="OVER_QUERY_LIMIT")
			{
				$OVER_QUERY_LIMIT[]=$fileName;		
			}
			elseif($arr->status=="REQUEST_DENIED")
			{
				$REQUEST_DENIED[]=$fileName;			
			}
			elseif($arr->status=="INVALID_REQUEST")
			{
				$INVALID_REQUEST[]=$fileName;		
			}
			elseif($arr->status=="UNKNOWN_ERROR")
			{
				$UNKNOWN_ERROR[]=$fileName;	
			}	
			//Query IT

			if($i%1500==0 && $i!=0)
			{
				if(strlen($values)>100)
				{
					$query="INSERT INTO parsedGeo ".$fields." VALUES ".substr($values,0,-1);
					mysqli_query($connection,$query) or die(mysqli_error($connection));
					$values="";
				}
				if(count($OK)>0)
				{
					$where=implode(',', $OK);
					$where="id in (".$where.")";
					$query="update novaGeo.geoQueue set status=1 where ".$where;
					mysqli_query($connection,$query) or die(mysqli_Error($connection));

					unset($OK);
					$OK=array();
				}
				if(count($ZERO_RESULTS)>0)
				{
					$where=implode(',', $ZERO_RESULTS);
					$where="id in (".$where.")";
					$query="update novaGeo.geoQueue set status=2 where ".$where;	
					mysqli_query($connection,$query) or die(mysqli_Error($connection));

					unset($ZERO_RESULTS);
					$ZERO_RESULTS=array();
				}
				if(count($OVER_QUERY_LIMIT)>0)
				{
					$where=implode(',', $OVER_QUERY_LIMIT);
					$where="id in (".$where.")";
					$query="update novaGeo.geoQueue set status=3 where ".$where;	
					mysqli_query($connection,$query) or die(mysqli_Error($connection));

					unset($OVER_QUERY_LIMIT);
					$OVER_QUERY_LIMIT=array();
				}
				if(count($INVALID_REQUEST)>0)
				{
					$where=implode(',', $INVALID_REQUEST);
					$where="id in (".$where.")";
					$query="update novaGeo.geoQueue set status=4 where ".$where;	
					mysqli_query($connection,$query) or die(mysqli_Error($connection));

					unset($INVALID_REQUEST);
					$INVALID_REQUEST=array();
				}
				if(count($UNKNOWN_ERROR)>0)
				{
					$where=implode(',', $UNKNOWN_ERROR);
					$where="id in (".$where.")";
					$query="update novaGeo.geoQueue set status=5 where ".$where;	
					mysqli_query($connection,$query) or die(mysqli_Error($connection));

					unset($UNKNOWN_ERROR);
					$UNKNOWN_ERROR=array();
				}						
			}									
		}
	}
	//QUERY REST OF IT
	if(strlen($values)>100)
	{
		$query="INSERT INTO parsedGeo ".$fields." VALUES ".substr($values,0,-1);
		mysqli_query($connection,$query) or die(mysqli_error($connection));	
	}

	if(count($OK)>0)
	{
		$where=implode(",", $OK);

		$where="id in (".$where.")";

		$query="update novaGeo.geoQueue set status=1 where ".$where;
		mysqli_query($connection,$query) or die(mysqli_Error($connection));

		unset($OK);
		$OK=array();
	}

	if(count($ZERO_RESULTS)>0)
	{
		$where=implode(',', $ZERO_RESULTS);
		$where="id in (".$where.")";
		$query="update novaGeo.geoQueue set status=2 where ".$where;	
		mysqli_query($connection,$query) or die(mysqli_Error($connection));

		unset($ZERO_RESULTS);
		$ZERO_RESULTS=array();
	}

	if(count($OVER_QUERY_LIMIT)>0)
	{
		$where=implode(',', $OVER_QUERY_LIMIT);
		$where="id in (".$where.")";
		$query="update novaGeo.geoQueue set status=3 where ".$where;	
		mysqli_query($connection,$query) or die(mysqli_Error($connection));

		unset($OVER_QUERY_LIMIT);
		$OVER_QUERY_LIMIT=array();
	}

	if(count($INVALID_REQUEST)>0)
	{
		$where=implode(',', $INVALID_REQUEST);
		$where="id in (".$where.")";
		$query="update novaGeo.geoQueue set status=4 where ".$where;	
		mysqli_query($connection,$query) or die(mysqli_Error($connection));

		unset($INVALID_REQUEST);
		$INVALID_REQUEST=array();
	}

	if(count($UNKNOWN_ERROR)>0)
	{
		$where=implode(',', $UNKNOWN_ERROR);
		$where="id in (".$where.")";
		$query="update novaGeo.geoQueue set status=5 where ".$where;	
		mysqli_query($connection,$query) or die(mysqli_Error($connection));

		unset($UNKNOWN_ERROR);
		$UNKNOWN_ERROR=array();
	}	
}


//ZIP IT

function zipIt($folder,$filesOK)
{
	$zip=new ZipArchive();

	$zipName='geo.zip';
	
	if ($zip->open($zipName, ZipArchive::CREATE) === TRUE) 
	{

		foreach($filesOK as $file)
		{
			$fileWP=str_replace('C:', '',str_replace('\\', '/', realpath(dirname(__FILE__)))).'/'.$folder."/".$file.".json";
			$zip->addFile($fileWP, $file.".json");
		}
		$zip->close();
		foreach($filesOK as $file)
		{
			unlink($folder.'/'.$file.'.json');
		}		
	}
}

//USE FUNCTIONS

//FIRST TRY
$resultDownloaded="Yes";
$resultDownloaded=downloadGeoData($folder,$con,0);
if($resultDownloaded=="Yes")
{
	parseGeoData($folder,$con);
	zipIt($folder,$finalOK);
}

//DO IT AGAIN
$resultDownloaded=downloadGeoData($folder,$con,0);
if($resultDownloaded=="Yes")
{
	parseGeoData($folder,$con);
	zipIt($folder,$finalOK);
}

//REDUCE ADDRESS QUERIED
$resultDownloaded2=downloadGeoData($folder,$con,2);
if($resultDownloaded2=="Yes")
{
	parseGeoData($folder,$con);
	zipIt($folder,$finalOK);
}


/*
//MINIMUM ADDRESS QUERIED
$resultDownloaded2=downloadGeoData($folder,$con,2);
if($resultDownloaded=="Yes")
{
	parseGeoData($folder,$con);
	zipIt($folder,$finalOK);
}
*/

?>