<?php
include 'mysqliConnect.php';

$api="AIzaSyAnbf3JJQUH5GBLbMeLv2F5NLvlBoccsO0";
$folder="geoCodeDwnl";
$min=1;
$max=100;

$query="select id, replace(replace(trim(uniqueAddress),' ','+'),'++','+') as address from novaGeo.geoQueue where status=2;";
$queryIt=mysqli_query($con,$query) or die(mysqli_error($con));

while($row=mysqli_fetch_array($queryIt))
{
	$mainId[]=$row['id'];
	$address[]=$row['address'];
}


//IF STATUS = 2
if($statusType==2)
{
	for($i=0;$i<count($address);$i++)
	{
		$finAddress="";
		$newAddressA=explode("+",$address[$i]);

		for($j=3; $j<count($newAddressA);$j++)
		{
			$finAddress.=$newAddressA[$j]."+";
		}
		if(strlen($finAddress)>3)
		{
			$finalAddress[]=substr($finAddress,0,-1);
			$finalId[]=$mainId[$i];
		}

	}	
}
else
{
	$finalId=$mainId;
	$finalAddress=$address;
}


if(isset($finalId))
{

	for($i=0;$i<count($finalId);$i++)
	{

		$apiQuery="https://maps.googleapis.com/maps/api/geocode/json?address=".$finalAddress[$i]."&key=".$api;
		echo $apiQuery."\n";
		$data=@file_get_contents($apiQuery);
			//$data=false;
		if($data===false)
		{
			sleep(120);
			$i=$i-1;
		}
		else
		{
			file_put_contents($folder."/".$finalId[$i].".json", $data);
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