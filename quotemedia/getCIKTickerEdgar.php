<?php
ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
include '../connection/simple_html_dom.php';
//include 'parseComp2.php';
//include "extractTable.php";
include '../connection/mysqliConnect.php';

$webmasterId='102498';

$url="http://rankandfiled.com/static/export/cik_ticker.csv";

$file=file($url);
$data = [];
foreach ($file as $line) {
    $data[] = explode("|",str_getcsv($line)[0]);
}

$rowCount=count($data);

$count=0;
for($i=1;$i<$rowCount;$i++)
{
	if(isset($data[$i]) && isset($data[$i-1]))
	{
		if($data[$i][1]==$data[$i-1][1])
		{
			unset($data[$i-1]);
			unset($data[$i]);
		}		
	}

}
$finalData=array_values($data);

$preSelect="";
for($i=1;$i<count($finalData);$i++)
{
	if($i%2000==0)
	{
		//
		$preSelect=substr($preSelect, 0,-6);
		$query="UPDATE nsMine.quoteMediaTickers inner join (".$preSelect.") as t on ticker=symbol set edgarId=cik";
		mysqli_query($con,$query) or die(mysqli_error($con));

		$preSelect="";
		$preSelect.="SELECT LPAD('".$finalData[$i][0]."',10,'0') as cik, '".$finalData[$i][1]."' as ticker UNION ";
		echo $i."\n";
	}
	else
	{

		$preSelect.="SELECT LPAD('".$finalData[$i][0]."',10,'0') as cik, '".$finalData[$i][1]."' as ticker UNION ";		
	}
}
$preSelect=substr($preSelect, 0,-6);
$query="UPDATE nsMine.quoteMediaTickers inner join (".$preSelect.") as t on ticker=symbol set edgarId=cik";
mysqli_query($con,$query) or die(mysqli_error($con));
/*
update companyFiling 
left join (select regulatoryId as regId,symbol from (select * from companyFiling where source='sedar') as t
	left join ( select * from quoteMediaTickers where exSymb='tsx.xml' or exSymb='tsv.xml') as t2 on longname=companyName
	where symbol is not null
	group by regulatoryId
) as t5 on regId=regulatoryId 
SET qmTickerCA=symbol
where regId=regulatoryId and source ='sedar' and regId is null;
*/
?>