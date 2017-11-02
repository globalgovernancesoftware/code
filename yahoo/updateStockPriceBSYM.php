<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
//include 'parseComp2.php';
//include "extractTable.php";
require_once '../connection/mysqliConnect.php';

$query="SELECT ticker,bbId  FROM nsMine.yahooSp group by ticker;";

$queryIt=mysqli_query($con,$query);

while($row=mysqli_fetch_Array($queryIt))
{
	$yTicker[]=$row['0'];
	$uniqueId[]=$row['1'];			
}

$query="SELECT max(spDate) as mDate FROM nsMine.yahooSp;";

$queryIt=mysqli_query($con,$query);


while($row=mysqli_fetch_array($queryIt))
{
	$date=$row['mDate'];
}

$dateP=date('Ymd',strtotime('-4 day',strtotime($date)));

$yearY=substr($dateP, 0,4);
$monthY=intval(substr($dateP,4,2))-1;
$dayY=intval(substr($dateP,6,2));

$countTicker=count($yTicker);
for($i=0;$i<$countTicker;$i++)
{
	$stockPrices=array();
	$finalData="";

	$ticker=$yTicker[$i];
	$stockPrices=explode("\n",@file_get_contents("http://real-chart.finance.yahoo.com/table.csv?s=".$ticker."&a=".$monthY."&b=".$dayY."&c=".$yearY."&g=d&ignore=.csv"));
	$countData=count($stockPrices);		

	if($countData>1)
	{
		for($j=1;$j<$countData-1;$j++)
		{
			$finalData.="('".$ticker."',".str_replace("-", "", $stockPrices[$j]).",'".$uniqueId[$i]."'),";
		}	
		$query="insert ignore into nsMine.yahooSp values ".substr($finalData,0,-1);
		$queryIt=mysqli_query($con,$query) ;
		
	}
	if($i%100==0)
	{
		echo $i."\n";
	}	
}

?>