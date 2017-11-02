<?php
include 'mysqliConnect.php';

$api="AIzaSyAnbf3JJQUH5GBLbMeLv2F5NLvlBoccsO0";
$folder="geoCodeDwnl";
$min=1;
$max=100;

$query="select id, replace(replace(trim(uniqueAddress),' ','+'),'++','+') as address from novaGeo.geoQueue where status=0;";
$queryIt=mysqli_query($con,$query) or die(mysqli_error($con));

while($row=mysqli_fetch_array($queryIt))
{
	$mainId[]=$row['id'];
	$address[]=$row['address'];
}

if(isset($mainId))
{

	for($i=0;$i<15;$i++)
	{

		$apiQuery="https://maps.googleapis.com/maps/api/geocode/json?address=".$address[$i]."&key=".$api;
		$data=@file_get_contents($apiQuery);
			//$data=false;
		if($data===false)
		{
			sleep(120);
			$i=$i-1;
		}
		else
		{
			file_put_contents($folder."/".$mainId[$i].".json", $data);
			$values[]=$mainId[$i];
			$randNr=rand($min,$max)/100;	
			sleep(1+$randNr);
			echo "Done: ".$mainId[$i]."\n";
		}
	}	
	/*
	$where=implode(" or id=", $values);
	$where="id=".$where;
	$query="update novaGeo.geoQueue set status=1 where".$where;
	mysqli_query($con,$query) or die(mysqli_Error($con));
	*/
}



?>