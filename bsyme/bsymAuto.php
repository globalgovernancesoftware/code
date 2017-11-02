<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
include '../connection/simple_html_dom.php';
//include 'parseComp2.php';
//include "extractTable.php";
include '../connection/mysqliConnect.php';


			//Mysql
		//$conn =mysql_connect("novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com","novaUser","Tyrose1214","ids") or die(mysql_error());
		if (mysqli_connect_errno($con))
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$selectDb=mysqli_select_db($con,'bsym');


$var3=0;


$curDate=date("Ymd");
$curDate=20160502;
$query='select * from urls where mainType="Equity" and type<>"Equity Option";';

$queryIt=mysqli_query($con,$query);


while($row=mysqli_Fetch_array($queryIt))
{
	$links[]=str_replace("20160502",$curDate,$row['link']);
	$names[]=$row['type'];
}

//$dir="/xampp/htdocs/phpFunctions/CompData/Def14A1y/";

$countDoc=0;
foreach ($links as $filenames) 
{	
	file_put_contents("zip/".$names[$countDoc].".zip", fopen($filenames, 'r'));
	
	$zip = zip_open("zip/".$names[$countDoc].".zip");
	$zip = new ZipArchive;
	$res = $zip->open("zip/".$names[$countDoc].".zip");

	if ($res === TRUE) 
	{ 
	  $zip->extractTo('unzipped/');	
	  $zip->close();
	} 
	$dir=array();

	$path=realpath(dirname(__FILE__))."\unzipped\\";
	$dir=scandir("unzipped");
	$dir = array_diff($dir, array('..', '.'));

	if(count($dir)>0)
	{
		foreach($dir as $fileS)
		{
			$newPath=str_replace("\\", "/", $path);

			$query="create temporary table tempMaster like master;";
				mysqli_query($con,$query);
			echo $fileS."\n";

			$query="Load  data local infile '".$newPath.$fileS."'
			INTO TABLE bsym.tempMaster FIELDS TERMINATED BY '|' 
		     OPTIONALLY ENCLOSED BY '\"' 
		     LINES TERMINATED BY '\n'
		     IGNORE 1 LINES";
     		mysqli_query($con,$query) or Die(mysqli_Error($con));


     		$query="insert ignore into master
				select * from tempMaster  
				on duplicate key update 
				NAME=tempMaster.NAME,
				ID_BB_SEC_NUM_DES=tempMaster.ID_BB_SEC_NUM_DES,
				FEED_SOURCE=tempMaster.FEED_SOURCE,
				ID_BB_SEC_NUM_SRC=tempMaster.ID_BB_SEC_NUM_SRC,
				ID_BB_UNIQUE=tempMaster.ID_BB_UNIQUE,
				SECURITY_TYP=tempMaster.SECURITY_TYP,
				MARKET_SECTOR_DES=tempMaster.MARKET_SECTOR_DES,
				ID_BB_GLOBAL=tempMaster.ID_BB_GLOBAL,
				COMPOSITE_ID_BB_GLOBAL=tempMaster.COMPOSITE_ID_BB_GLOBAL,
				FEED_EID1=tempMaster.FEED_EID1,
				FEED_EID2=tempMaster.FEED_EID2,
				FEED_EID3=tempMaster.FEED_EID3,
				FEED_EID4=tempMaster.FEED_EID4,
				FEED_DELAYED_EID1=tempMaster.FEED_DELAYED_EID1,
				Subscription_String_1=tempMaster.Subscription_String_1,
				Subscription_String_2=tempMaster.Subscription_String_2,
				Subscription_String_3=tempMaster.Subscription_String_3;
				";
     		mysqli_query($con,$query) or Die(mysqli_Error($con));

			$query="drop temporary table tempMaster;";
			mysqli_query($con,$query);     		
		}

	}
	foreach($dir as $file){ // iterate files

	    unlink(realpath(dirname(__FILE__))."\unzipped\\".$file);	  	

 // delete file
	}
    unlink(realpath(dirname(__FILE__))."\zip\\".$names[$countDoc].".zip");	  	

$countDoc++;
echo $countDoc."\n";
}

?>
