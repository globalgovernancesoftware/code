<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
//include 'parseComp2.php';
//include "extractTable.php";
require_once '../connection/mysqliConnect.php';
include '../connection/simple_html_dom.php';

$query="select ID_BB_SEC_NUM_DES,FEED_SOURCE,ID_BB_GLOBAL from (SELECT * FROM bsym.master where FEED_SOURCE in ('US') and SECURITY_TYP in ('Common Stock','REIT','Equity WRT') ) as t  where (length(ID_BB_SEC_NUM_DES)<7 or ID_BB_SEC_NUM_DES  like '%WS%') order by ID_BB_SEC_NUM_DES;";

$queryIt=mysqli_query($con,$query);

while($row=mysqli_fetch_Array($queryIt))
{
	//CONVERT US TICKERS

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
		$bbTicker[]=$row['0'];

	$data[]=$row;
}

$countTicker=count($data);

//$countTicker=10;
$query="";

for($i=0;$i<$countTicker;$i++)
{
	$url='http://finance.yahoo.com/q/sec?s='.$yTicker[$i].'+SEC+Filings';
	$html = @file_get_html($url);

	if(!empty($html))
	{
		$eles = @$html->find('strong');

		foreach($eles as $e) 
		{
		    if(strpos($e->innertext, 'View All Filings on EDGAR Online') !== false) 
		    {
	    		$href=$e->parent()->href;
	    		$cik=substr($href,53);
				echo $i."/".count($data)."\n";
	    		break;
		    }
		    else
		    {
		    	$cik="";
		    }
		}

		$query.=" ('".$bbTicker[$i]."','".str_PAD($cik,10,'0',STR_PAD_LEFT)."','".$yTicker[$i]."','".$uniqueId[$i]."'),";


		echo $i."\n";
		$queryFinal="insert ignore into nsMine.mainTickCIK (ticker,mainId,yahooTicker,bbId) values ".substr($query,0,-1)." on duplicate key update ticker='".$bbTicker[$i]."', bbId='".$uniqueId[$i]."', yahooTicker='".$yTicker[$i]."';";
		$queryIt=mysqli_query($con,$queryFinal) or (die(mysqli_error($con)));		
		$query="";


	}
}


?>