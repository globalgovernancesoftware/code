<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
//include 'parseComp2.php';
//include "extractTable.php";
require_once '../connection/mysqliConnect.php';

$query="select symbol from nsMine.usTickers group by symbol";
$queryIt=mysqli_query($con,$query);
include '../connection/simple_html_dom.php';

while($row=mysqli_fetch_array($queryIt))
{
	$data[]=$row;
}

for($i=0;$i<count($data);$i++)
{
	if(substr($data[$i]['symbol'],-3)==".CL")
	{
		$data[$i]['symbol']=substr($data[$i]['symbol'],0,-3);
	}
	if(substr(substr($data[$i]['symbol'],-5),0,-1)==".WS.")
	{
		$data[$i]['symbol']=str_replace(".WT.", "-WT", $data[$i]['symbol']);
	}	
	if(substr($data[$i]['symbol'],-3)==".WT")
	{
		$data[$i]['symbol']=str_replace(".WT", "-WT", $data[$i]['symbol']);
	}	
	if(substr(substr($data[$i]['symbol'],-5),0,-1)==".WS.")
	{
		$data[$i]['symbol']=str_replace(".WT.", "-WT", $data[$i]['symbol']);
	}	
	if(substr(substr($data[$i]['symbol'],-2),0,-1)=="^")
	{
		$data[$i]['symbol']=str_replace("^", "-P", $data[$i]['symbol']);
	}	
	if(substr($data[$i]['symbol'],-1)=="^")
	{
		$data[$i]['symbol']=str_replace("^", "-P", $data[$i]['symbol']);
	}		
	if(substr(substr($data[$i]['symbol'],-2),0,-1)==".")
	{
		$data[$i]['symbol']=str_replace(".", "-", $data[$i]['symbol']);
	}							
}

echo count($data)."\n";
$countId=count($data);
for($i=0;$i<$countId;$i++)
{
	$url='http://finance.yahoo.com/q/sec?s='.$data[$i]['symbol'].'+SEC+Filings';
	$html = file_get_html($url);

	$eles = $html->find('strong');
	foreach($eles as $e) 
	{
	    if(strpos($e->innertext, 'View All Filings on EDGAR Online') !== false) 
	    {
    		$href=$e->parent()->href;
    		$data[$i]['cik']=substr($href,53);
			echo $i."/".count($data)."\n";
    		break;
	    }
	    else
	    {
	    	$data[$i]['cik']="";
	    }
	}
}
$query="";
for($i=0;$i<$countId;$i++)
{
	$query.=" select CONVERT('".$data[$i][0]."' USING utf8) as symbolN, CONVERT('".$data[$i]['cik']."' USING utf8) as cikN
	union";

}

$queryFinal="update nsMine.usTickers as mainTable
inner join 
 (".substr($query,0,-5).")  as t on t.symbolN=mainTable.symbol
        
        set mainTable.cik=t.cikN;";

$queryIt=mysqli_query($con,$queryFinal) or (die(mysqlI_error($con)));



?>