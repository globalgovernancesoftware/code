<?php
ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
include '../connection/simple_html_dom.php';
//include 'parseComp2.php';
//include "extractTable.php";
include '../connection/mysqliConnect.php';

$webmasterId='102498';

$url="http://app.quotemedia.com/data/getSymbols.xml?webmasterId=".$webmasterId."&exgroup=";

$exSymb=array("nsd","nye","amx","tsx","tsv","otc","oto");

print_r($exSymb);

for($i=0;$i<count($exSymb);$i++)
{
	echo $exSymb[$i]."\n";
	$file=$url.$exSymb[$i];
	$xml=simplexml_load_string(file_get_contents($file),'SimpleXMLElement',LIBXML_NOCDATA);

	$values="";
	$count=0;
	foreach($xml->lookupdata as $company)
	{
		if($count%500==0 && $count>0)
		{
			$query="INSERT IGNORE INTO nsMine.quoteMediaTickers (symbol,exchange,filename,longname,shortname,instrumenttype,sectype,isocfi) VALUES ".substr($values,0,-1)."
			ON DUPLICATE KEY update exchange=VALUES(exchange), filename=VALUES(filename), longname=VALUES(longname), shortname=VALUES(shortname), instrumenttype=VALUES(instrumenttype), sectype=VALUES(sectype), isocfi=VALUES(isocfi)";			
		$values="";
			$values.='("'.mysqli_real_escape_string($con,$company->key->symbol).'","'.mysqli_real_escape_string($con,$company->key->exchange).'","'.mysqli_real_escape_string($con,$exSymb[$i]).'","'.mysqli_real_escape_string($con,$company->equityinfo->longname).'","'.mysqli_real_escape_string($con,$company->equityinfo->shortname).'","'.mysqli_real_escape_string($con,$company->equityinfo->instrumenttype).'","'.mysqli_real_escape_string($con,$company->equityinfo->sectype).'","'.mysqli_real_escape_string($con,$company->equityinfo->isocfi).'"),';
			mysqli_query($con,$query) or die(mysqli_error($con));
		}
		else
		{
			$values.='("'.mysqli_real_escape_string($con,$company->key->symbol).'","'.mysqli_real_escape_string($con,$company->key->exchange).'","'.mysqli_real_escape_string($con,$exSymb[$i]).'","'.mysqli_real_escape_string($con,$company->equityinfo->longname).'","'.mysqli_real_escape_string($con,$company->equityinfo->shortname).'","'.mysqli_real_escape_string($con,$company->equityinfo->instrumenttype).'","'.mysqli_real_escape_string($con,$company->equityinfo->sectype).'","'.mysqli_real_escape_string($con,$company->equityinfo->isocfi).'"),';
		}
		//echo $company->key->symbol;

		$count++;
	}
	if(strlen($values)>0)
	{
		$query="INSERT IGNORE INTO nsMine.quoteMediaTickers (symbol,exchange,filename,longname,shortname,instrumenttype,sectype,isocfi) VALUES ".substr($values,0,-1)."
		ON DUPLICATE KEY update exchange=VALUES(exchange), filename=VALUES(filename), longname=VALUES(longname), shortname=VALUES(shortname), instrumenttype=VALUES(instrumenttype), sectype=VALUES(sectype), isocfi=VALUES(isocfi)";
		mysqli_query($con,$query) or die(mysqli_error($con));
		
	}	
}


/*
update nsMine.companyFiling 
inner join (select regulatoryId as regId,symbol from (select * from nsMine.companyFiling where source='sedar') as t
	left join ( select * from nsMine.quoteMediaTickers where filename='tsx' or filename='tsv') as t2 on longname=companyName
	where symbol is not null
	group by regulatoryId
) as t5 on regId=regulatoryId 
SET qmTickerCA=symbol
where regId=regulatoryId and source ='sedar';

update nsMine.quoteMediaTickers
inner join (SELECT mainId,concat(ticker,":CA") as combinedTicker FROM nsMine.mainTickCIK where length(mainId)=8 and ticker<>"") as t2
on combinedTicker=symbol
set sedarId=mainId
where sedarId is null;


*/
?>