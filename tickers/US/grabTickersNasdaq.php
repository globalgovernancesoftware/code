<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
//include 'parseComp2.php';
//include "extractTable.php";
require_once '../connection/mysqliConnect.php';

$listNasdaq=["http://www.nasdaq.com/screening/companies-by-name.aspx?letter=0&exchange=nasdaq&render=download","http://www.nasdaq.com/screening/companies-by-name.aspx?letter=0&exchange=nyse&render=download","http://www.nasdaq.com/screening/companies-by-name.aspx?letter=0&exchange=amex&render=download"];
$namefiles=["Nasdaq","NYSE","AMEX"];



$countList=count($namefiles);
for($i=0;$i<$countList;$i++)
{
	$values="";
	$tick=explode("\n",@file_get_contents($listNasdaq[$i]));
	$data=array();
	foreach($tick as $t)
	{
		$data[]=explode('","',$t);
	}
	$countD=count($data);
	for($j=1;$j<$countD;$j++)
	{
		for($k=0;$k<count($data[$j]);$k++)
		{
			if($k==0 || $k==7)
			{
				$data[$j][$k]=str_replace(" ","",str_replace('"', '', $data[$j][$k]));
			}
			elseif($k==3)
			{
				if(substr($data[$j][$k],-1)=="K")
				{
					$data[$j][$k]=(str_replace("$","",$data[$j][$k])*1000);
				}				
				elseif(substr($data[$j][$k],-1)=="M")
				{
					$data[$j][$k]=(str_replace("$","",$data[$j][$k])*1000000);
				}
				elseif(substr($data[$j][$k],-1)=="B")
				{
					$data[$j][$k]=(str_replace("$","",$data[$j][$k])*1000000000);
				}	
				elseif(substr($data[$j][$k],-1)=="T")
				{
					$data[$j][$k]=(str_replace("$","",$data[$j][$k])*1000000000000);
				}
				elseif($data[$j][$k]=="n/a")
				{
					$data[$j][$k]=null;
				}						
				else
				{
					$data[$j][$k]=str_replace("$","",$data[$j][$k]);
				}									
			}
			elseif($k==4)
			{
				if($data[$j][$k]=="n/a")
				{
					$data[$j][$k]=null;
				}						
			}
			elseif($k==1)
			{
					$data[$j][$k]=str_replace('&quot;','', $data[$j][$k]);						
			}			
			//$data[$j][$k]=mysqli_Real_Escape_String($con,$data[$j][$k]);
		}
	}
	$subQuery="";
	for($j=1;$j<$countD-1;$j++)
	{
		$subQuery.=" select CONVERT('".$data[$j][0]."' USING utf8) as symbolN, CONVERT('".$data[$j][1]."' USING utf8) as nameN,
		CONVERT('".$data[$j][2]."' USING utf8) as lastSaleN, CONVERT('".$data[$j][3]."' USING utf8) as marketcapN,
		CONVERT('".$data[$j][4]."' USING utf8) as ipoYearN, CONVERT('".$data[$j][5]."' USING utf8) as sectorN,
		CONVERT('".$data[$j][6]."' USING utf8) as industryN, CONVERT('".$data[$j][7]."' USING utf8) as urlN, CONVERT('".$namefiles[$i]."' USING utf8) as sourceN
		union ";

	}	


		$query="insert into nsMine.usTickers (symbol,
		name,
		lastSale,
		marketcap,
		ipoYear,
		sector,
		industry,
		url,
		source) select * from ( ".substr($subQuery,0,-6)." ) as tFinal
		on duplicate key update
		 lastSale=tFinal.lastSaleN,
		 marketcap=tFinal.marketcapN,
		 ipoYear=tFinal.ipoYearN,
		 sector=tFinal.sectorN,
		 industry=tFinal.industryN,
		 url=tFinal.urlN;";
		$queryIt=mysqli_query($con,$query) or (die(mysqli_Error($con).$query));
}

?>