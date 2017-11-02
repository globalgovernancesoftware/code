<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
//include 'parseComp2.php';
//include "extractTable.php";
require_once '../connection/mysqliConnect.php';

$query="select * from (select ID_BB_SEC_NUM_DES,FEED_SOURCE,ID_BB_GLOBAL from (SELECT * FROM bsym.master where FEED_SOURCE in ('CN','US') and SECURITY_TYP in ('Common Stock','REIT','Equity WRT') ) as t  where (length(ID_BB_SEC_NUM_DES)<7 or ID_BB_SEC_NUM_DES  like '%WS%') order by ID_BB_SEC_NUM_DES) as tFin
left join (select bbId  FROM nsMine.yahooSp group by ticker) as tSubFin on bbId=ID_BB_GLOBAL
where bbId is null;";

$queryIt=mysqli_query($con,$query);

while($row=mysqli_fetch_Array($queryIt))
{
	//CONVERT US TICKERS
	if($row['1']=="US")
	{
		if(strpos($row['0'],"/")!==false)
		{
			if(substr($row['0'],-5)=="/WS/A" || substr($row['0'],-5)=="/WS/B")
			{
				$yTicker[]=str_replace("/WS/","-WT",$row['0']);
			}
			elseif(substr($row['0'],-4)=="/WSA" || substr($row['0'],-3)=="/WS")
			{
				$yTicker[]=str_replace("/WS","-WT",$row['0']);
			}
			else
			{
				$yTicker[]=str_replace("/","-",$row['0']);
			}
		}
		else
		{
			$yTicker[]=$row['0'];
		}
		$country[]=$row['1'];
		$uniqueId[]=$row['2'];
	}
	//CONVERT CDN TICKERS	
	else
	{
		if(strpos($row['0'],"/")!==false)
		{
			$yTicker[]=str_replace("/","-",$row['0']);
		}
		elseif(strpos($row['0'],"-")!==false)
		{
			if(strpos($row['0'],"-W")!==false)
			{
				$yTicker[]=str_replace("-W","-WT",$row['0']);
			}
			else
			{
				$yTicker[]=str_replace("-U","-UN",$row['0']);
			}

		}
		else
		{
			$yTicker[]=$row['0'];
		}
		$country[]=$row['1'];
		$uniqueId[]=$row['2'];			
	}
	$data[]=$row;
}

$countTicker=count($data);
for($i=0;$i<$countTicker;$i++)
{
	$stockPrices=array();
	$finalData="";
	if($country[$i]=="CN")
	{
		$ticker=$yTicker[$i].".V";
		$stockPrices=explode("\n",@file_get_contents("http://real-chart.finance.yahoo.com/table.csv?s=".$ticker."&g=d&ignore=.csv"));
		$countData=count($stockPrices);
		if($countData==1)
		{
			$ticker=$yTicker[$i].".TO";
			$stockPrices=explode("\n",@file_get_contents("http://real-chart.finance.yahoo.com/table.csv?s=".$ticker."&g=d&ignore=.csv"));
			$countData=count($stockPrices);
			if($countData==1)
			{
				$ticker=$yTicker[$i].".CN";
				$stockPrices=explode("\n",@file_get_contents("http://real-chart.finance.yahoo.com/table.csv?s=".$ticker."&g=d&ignore=.csv"));
				$countData=count($stockPrices);
			}			
		}
	}
	else
	{
		$ticker=$yTicker[$i];
		$stockPrices=explode("\n",@file_get_contents("http://real-chart.finance.yahoo.com/table.csv?s=".$ticker."&g=d&ignore=.csv"));
		$countData=count($stockPrices);		
	}

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