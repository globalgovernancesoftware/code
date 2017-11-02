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

	$query="select id, replace(replace(trim(uniqueAddress),' ','+'),'++','+') as address from novaGeo.geoQueue where status=".$statusType.";";
	$queryIt=mysqli_query($connection,$query) or die(mysqli_error($connection));

	while($row=mysqli_fetch_array($queryIt))
	{
		$mainId[]=$row['id'];
		$address[]=$row['address'];
	}

	if(isset($mainId))
	{
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
			//$partialTryNumber++;
		}
		else if($statusType==0)
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


	print_R($finalAddress);

}
downloadGeoData($folder,$con,2);

?>