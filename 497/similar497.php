<?php



ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');



include 'mysqliConnectM3.php';



$query="select * from (SELECT source,name,street1,street2,city,zipCode FROM novaOwnership.masterhead where SsignatureDate>20140301) as t group by name;";
$queryIt=mysqli_query($con,$query);

$i=0;

while($row=mysqli_fetch_array($queryIt))
{
	$source[]=$row['source'];
	$name[]=$row['name'];
	$street1[]=$row['street1'];
	$street2[]=$row['street2'];
	$city[]=$row['city'];
	$zipCode[]=$row['zipCode'];
	$nameClean[]=strtolower(trim(str_replace(".", "", str_replace(",", "", $row['name']))));
	$finalName[]=strtolower(trim(str_replace(".", "", str_replace(",", "", $row['name'])))).$row['street1'].$row['street2'].$row['city'].$row['zipCode'];
}


$query="select * from novaADV.advID;";
$queryIt=mysqli_query($con,$query);
$i=0;
while($row=mysqli_fetch_array($queryIt))
{
	$orgNr[]=$row['orgNr'];
	$secNr[]=$row['secNr'];
	$primName[]=$row['primName'];
	$legName[]=$row['legName'];
	$primNameClean[]=strtolower(trim(str_replace(".", "", str_replace(",", "", $row['primName']))));
	$legNameClean[]=strtolower(trim(str_replace(".", "", str_replace(",", "", $row['legName']))));
	$finalPrimName[]=strtolower(trim(str_replace(".", "", str_replace(",", "", $row['primName'])))).$row['advStreet1'].$row['advStreet2'].$row['advCity'].$row['advPostal'];
	$finalLegName[]=strtolower(trim(str_replace(".", "", str_replace(",", "", $row['legName'])))).$row['advStreet1'].$row['advStreet2'].$row['advCity'].$row['advPostal'];
}

$keyEq=-2;
$z=0;
$y=0;
$linked=array();

for($i=0;$i<count($name);$i++)
{
	$keyEq=array_search($nameClean[$i],$primNameClean);		
	if($keyEq>=1)
	{
		//echo $primNameClean[$keyEq]." => Primary\n";
		$linked[$z]=array();
		$linked[$z]['13f']=$source[$i];
		$linked[$z]['secNr']=$secNr[$keyEq];
		$linked[$z]['name13f']=$name[$i];
		$linked[$z]['nameAdv']=$primName[$keyEq];
		$linked[$z]['nameAdvLeg']=$legName[$keyEq];					
		unset($orgNr[$keyEq]);
		unset($secNr[$keyEq]);
		unset($primName[$keyEq]);
		unset($legName[$keyEq]);		
		unset($primNameClean[$keyEq]);
		unset($legNameClean[$keyEq]);
		unset($finalLegName[$keyEq]);
		unset($source[$i]);
		unset($street1[$i]);
		unset($street2[$i]);
		unset($city[$i]);	
		unset($zipCode[$i]);												
		unset($nameClean[$i]);
		unset($finalName[$i]);
		$z++;
	}
	else
	{	
		$keyEq=array_search($nameClean[$i],$legNameClean);		
		if($keyEq>=1)
		{
			//echo $legNameClean[$keyEq]." => Legal\n";
			$linked[$z]=array();
			$linked[$z]['13f']=$source[$i];
			$linked[$z]['secNr']=$secNr[$keyEq];
			$linked[$z]['name13f']=$name[$i];
			$linked[$z]['nameAdv']=$primName[$keyEq];
			$linked[$z]['nameAdvLeg']=$legName[$keyEq];				
			unset($orgNr[$keyEq]);
			unset($secNr[$keyEq]);
			unset($primName[$keyEq]);
			unset($legName[$keyEq]);		
			unset($primNameClean[$keyEq]);
			unset($legNameClean[$keyEq]);
			unset($finalLegName[$keyEq]);
			unset($source[$i]);
			unset($street1[$i]);
			unset($street2[$i]);
			unset($city[$i]);	
			unset($zipCode[$i]);												
			unset($nameClean[$i]);
			unset($finalName[$i]);			
			$z++;
		}
		else
		{
			//echo "Third Type\n";
			$y++;
		}
	}

}

$w=0;

$divider=1000;
$eqTotalCount=substr(count($linked)/$divider,0,1);
echo $eqTotalCount;


for($i=0;$i<=$eqTotalCount;$i++)
{

	$begin=$i*$divider;
	if($i!=$eqTotalCount)
	{
		$end=($i+1)*$divider;
	}
	else
	{
		$end=count($linked);
	}

	$queryVal="";
	echo $begin. " => ".$end;
	for($j=$begin;$j<$end;$j++)
	{

		$queryVal.='("'.mysqli_real_escape_string($con,$linked[$j]['13f']).'",
			"'.mysqli_real_escape_string($con,$linked[$j]['secNr']).'",
			"'.mysqli_real_escape_string($con,$linked[$j]['name13f']).'",
			"'.mysqli_real_escape_string($con,$linked[$j]['nameAdv']).'",
			"'.mysqli_real_escape_string($con,$linked[$j]['nameAdvLeg']).'"),';
	}

	$query="insert into novaADV.advLink values ".substr($queryVal,0,-1);
	
	$queryIt=mysqli_query($con,$query) or die(mysqli_Error($con));
	//echo $query;
}


/*
//Clean Arrays
$source=array_Values($source);
$nameClean=array_Values($nameClean);
$street1=array_values($street1);
$street2=array_values($street2);
$city=array_values($city);
$zipCode=array_values($zipCode);
$finalName=array_values($finalName);


$orgNr=array_values($orgNr);
$secNr=array_values($secNr);
$primName=array_values($primName);
$legName=array_values($legName);
$primNameClean=array_values($primNameClean);
$legNameClean=array_values($legNameClean);
$finalPrimName=array_values($finalPrimName);
$finalLegName=array_values($finalLegName);


echo  count($source)."\n";
echo  $z."\n";
echo  $y."\n";
$w=0;

for($i=0;$i<count($source);$i++)
{
	$pastSt=0;
	$nameLen=strlen($finalName[$i]);
	$spotted=0;
	$pN=0;
	$lN=0;

	for($j=0;$j<count($orgNr);$j++)
	{
		$curSt=similar_text($finalName[$i],$finalPrimName[$j]);
		if($curSt>$pastSt)
		{
			$pastSt=$curSt;
			$spotted=$j;
			$pN=1;
		}
	}

	for($j=0;$j<count($orgNr);$j++)
	{
		$curSt=similar_text($finalName[$i],$finalLegName[$j]);
		if($curSt>$pastSt)
		{
			$pastSt=$curSt;
			$spotted=$j;
			$lN=1;
		}
	}

	$firstName=explode(" ",$finalName[$i]);
	$firstName2=explode(" ",$finalPrimName[$spotted]);
	echo $firstName[0]."\n";
	echo $firstName2[0]."\n";
	if($firstName[0]==$firstName2[0])
	{
		if($spotted>0 && $pN==1)
		{	
			$linked[$z]['13f']=$source[$i];
			$linked[$z]['adv']=$secNr[$spotted];			
			echo $finalName[$i]." ===== ".$finalPrimName[$spotted]."\n";
			$z++;
		}
		else if($spotted>0 && $lN==1)
		{
			$linked[$z]['13f']=$source[$i];
			$linked[$z]['adv']=$secNr[$spotted];				
			echo $finalName[$i]." ===== ".$finalLegName[$spotted]."\n";
			$z++;
		}
	}

	$w++;	

}
*/
echo count($linked)."\n";

?>







