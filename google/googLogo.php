<?php
include 'mysqli_connect.php';

$query="select * from ids.masterCusipW";

$queryIt=mysqli_query($con,$query);

while($row=mysqli_fetch_array($queryIt))
{
	$compName[]=$row['compName'];
	$cusip[]=$row['cusip'];
}


$api="AIzaSyAnbf3JJQUH5GBLbMeLv2F5NLvlBoccsO0";
$cse="018310063431102106189:hdyurjrisg4";

//count($peop)

$min=1;
$max=100;

$folder="../../googleFullResults/googleLogos";
$dir=array_values(array_diff(scandir($folder), array('.', '..')));

$maxCount=count($compName);

$minCount=count($dir);

for($i=$minCount;$i<6000;$i++)
{
	$searchQ=str_replace("++","+",urlencode(trim(str_replace(","," ",str_replace("."," ",$compName[$i]." logo")))));
	$query="https://www.googleapis.com/customsearch/v1?key=".$api."&cx=".$cse."&q=".$searchQ."&searchType=image";
	echo $query."\n";
	/*$data=@file_get_contents($query);
		//$data=false;
	if($data===false)
	{
		sleep(120);
		$i=$i-1;
	}
	else
	{
		file_put_contents($folder."/".$cusip[$i].".txt", $data);

		$randNr=rand($min,$max)/100;	
		sleep(1+$randNr);
		echo "fileNr=".$i." (".($i-$minCount)."/".($maxCount-$minCount).")"."\n";
	}

	//echo $query."\n";
	*/
}

?>