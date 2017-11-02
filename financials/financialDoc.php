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
		$selectDb=mysqli_select_db($con,'ggsCompData');


$var3=0;

$query='select cikE, dateFiledE, if(left(right(dateFiledE,4),2)="01" or left(right(dateFiledE,4),2)="02" or left(right(dateFiledE,4),2)="03","QTR1",
if(left(right(dateFiledE,4),2)="04" or left(right(dateFiledE,4),2)="05" or left(right(dateFiledE,4),2)="06","QTR2",
if(left(right(dateFiledE,4),2)="07" or left(right(dateFiledE,4),2)="08" or left(right(dateFiledE,4),2)="09","QTR3",
"QTR4")))  as quarter, left(dateFiledE,4) as year, concat(left(right(filedNamedE,24),20),"\\\",left(right(filedNamedE,24),20),"-xbrl.zip") as fileName from edgarDb.edgarTb where (formTypeE like "%10-K%" or formTypeE like "%10-Q%") and dateFiledE>20130101 
order by dateFiledE, formTypeE;';

$queryIt=mysqli_query($con,$query);


while($row=mysqli_Fetch_array($queryIt))
{
	$data[]=$row;
	$fN[]=$row['fileName'];
	$pathFile[]="Z:\\".$row['year']."\\".$row['quarter']."\\".$row['dateFiledE']."\\".$row['fileName'];
	$docNameConcat[]=$row['cikE']."-".$row['fileName'];
}

//$dir="/xampp/htdocs/phpFunctions/CompData/Def14A1y/";

$countDoc=0;
foreach ($pathFile as $filenames) 
{	
	$zip = zip_open($filenames);

	if($zip)
	{
		while ($zip_entry = @zip_read($zip))
		{
			if(substr(zip_entry_name($zip_entry), -8)!="_cal.xml" && substr(zip_entry_name($zip_entry), -8)!="_def.xml" && substr(zip_entry_name($zip_entry), -8)!="_lab.xml" && substr(zip_entry_name($zip_entry), -8)!="_pre.xml" && substr(zip_entry_name($zip_entry), -4)!=".xsd")
			{
				if (zip_entry_open($zip, $zip_entry,"r"))
				{

					$contents =zip_entry_read($zip_entry,zip_entry_filesize($zip_entry));
					$fileZN=$filenames."\\".zip_entry_name($zip_entry);
					echo $fileZN."\n";
					//echo $contents;
					zip_entry_close($zip_entry);
				}
			
			}
		}
	}
	if(!is_int($zip))
	{
		$cik="";		
		$TradingSymbol="";
		//echo $contents;
		try { @$xml = new SimpleXMLElement($contents); } catch (Exception $e) { echo $e; }
		
		//var_dump($xml);
		$xml->registerXPathNamespace('', "");
		$xml->registerXPathNamespace('us-gaap', "http://fasb.org/us-gaap/2014-01-31");
		$xml->registerXPathNamespace('xbrli', "http://www.xbrl.org/2003/instance");

		$data=array();
		foreach($xml->xpath('//dei:EntityCentralIndexKey') as $item) 
		{
		   // $data['EntityCentralIndexKey']['values'][]=(String) $item[0];
		   // $data['EntityCentralIndexKey']['contextRef'][]=(String)$item->attributes()->contextRef;
		    $cik=(String) $item[0];
		}			
		foreach($xml->xpath('//dei:TradingSymbol') as $item) 
		{

		   // $data['TradingSymbol']['values'][]=(String) $item[0];
		   // $data['TradingSymbol']['contextRef'][]=(String)$item->attributes()->contextRef;
		    $TradingSymbol=(String) $item[0];

		}	
		foreach($xml->xpath('//dei:EntityCommonStockSharesOutstanding') as $item) 
		{
		    $data['EntityCommonStockSharesOutstanding']['values'][]=(String) $item[0];
		    $data['EntityCommonStockSharesOutstanding']['contextRef'][]=(String)$item->attributes()->contextRef;
		}
		foreach($xml->xpath('//us-gaap:Revenues') as $item) 
		{
		    $data['Revenues']['values'][]=(String) $item[0];
		    $data['Revenues']['contextRef'][]=(String)$item->attributes()->contextRef;
		}
		if(!isset($data['Revenues']))
		{
			foreach($xml->xpath('//us-gaap:SalesRevenueNet') as $item) 
			{
			    $data['SalesRevenueNet']['values'][]=(String) $item[0];
			    $data['SalesRevenueNet']['contextRef'][]=(String)$item->attributes()->contextRef;
			}
		}		
		foreach($xml->xpath('//us-gaap:AllocatedShareBasedCompensationExpense') as $item) 
		{
		    $data['AllocatedShareBasedCompensationExpense']['values'][]=(String) $item[0];
		    $data['AllocatedShareBasedCompensationExpense']['contextRef'][]=(String)$item->attributes()->contextRef;
		}
		foreach($xml->xpath('//us-gaap:ResearchAndDevelopmentExpense') as $item) 
		{
		    $data['ResearchAndDevelopmentExpense']['values'][]=(String) $item[0];
		    $data['ResearchAndDevelopmentExpense']['contextRef'][]=(String)$item->attributes()->contextRef;
		}
		foreach($xml->xpath('//us-gaap:NetIncomeLoss') as $item) 
		{
		    $data['NetIncomeLoss']['values'][]=(String) $item[0];
		    $data['NetIncomeLoss']['contextRef'][]=(String)$item->attributes()->contextRef;
		}			
		foreach($xml->xpath('//us-gaap:EarningsPerShareBasic') as $item) 
		{
		    $data['EarningsPerShareBasic']['values'][]=(String) $item[0];
		    $data['EarningsPerShareBasic']['contextRef'][]=(String)$item->attributes()->contextRef;
		}	
		foreach($xml->xpath('//us-gaap:EarningsPerShareDiluted') as $item) 
		{
		    $data['EarningsPerShareDiluted']['values'][]=(String) $item[0];
		    $data['EarningsPerShareDiluted']['contextRef'][]=(String)$item->attributes()->contextRef;
		}
		foreach($xml->xpath('//us-gaap:EarningsPerShareBasicAndDiluted') as $item) 
		{
		    $data['EarningsPerShareBasicAndDiluted']['values'][]=(String) $item[0];
		    $data['EarningsPerShareBasicAndDiluted']['contextRef'][]=(String)$item->attributes()->contextRef;
		}				
		foreach($xml->xpath('//us-gaap:Assets') as $item) 
		{
		    $data['Assets']['values'][]=(String) $item[0];
		    $data['Assets']['contextRef'][]=(String)$item->attributes()->contextRef;
		}	
		foreach($xml->xpath('//us-gaap:CashAndCashEquivalentsAtCarryingValue') as $item) 
		{
		    $data['CashAndCashEquivalentsAtCarryingValue']['values'][]=(String) $item[0];
		    $data['CashAndCashEquivalentsAtCarryingValue']['contextRef'][]=(String)$item->attributes()->contextRef;
		}	
		foreach($xml->xpath('//us-gaap:LongTermDebt') as $item) 
		{
		    $data['LongTermDebt']['values'][]=(String) $item[0];
		    $data['LongTermDebt']['contextRef'][]=(String)$item->attributes()->contextRef;
		}			
		foreach($xml->xpath('//us-gaap:LongTermDebtAndCapitalLeaseObligations') as $item) 
		{
		    $data['LongTermDebtAndCapitalLeaseObligations']['values'][]=(String) $item[0];
		    $data['LongTermDebtAndCapitalLeaseObligations']['contextRef'][]=(String)$item->attributes()->contextRef;
		}
		foreach($xml->xpath('//us-gaap:CapitalLeaseObligationsNoncurrent') as $item) 
		{
		    $data['CapitalLeaseObligationsNoncurrent']['values'][]=(String) $item[0];
		    $data['CapitalLeaseObligationsNoncurrent']['contextRef'][]=(String)$item->attributes()->contextRef;
		}
		foreach($xml->xpath('//us-gaap:LongTermDebtNoncurrent') as $item) 
		{
		    $data['LongTermDebtNoncurrent']['values'][]=(String) $item[0];
		    $data['LongTermDebtNoncurrent']['contextRef'][]=(String)$item->attributes()->contextRef;
		}		
		foreach($xml->xpath('//xbrli:context') as $item) 
		{
			$c['contextRef'][]=(String)	$item->attributes()->id;					
		    $c['startDate'][]=str_replace("\r","",str_replace("\n","",(String) $item->period->startDate));
		    $c['endDate'][]=str_replace("\r","",str_replace("\n","",(String) $item->period->endDate));
	   		$c['instant'][]=str_replace("\r","",str_replace("\n","",(String) $item->period->instant));
	   		$finalTotal=(String) $item->period->instant+(String) $item->period->endDate+(String) $item->period->instant;		    
		}
		if($finalTotal==0)
		{
			foreach($xml->xpath('//xbrli:context') as $item) 
			{	
				
				$c['contextRef'][]=(String)	$item->attributes()->id;					
			    $c['startDate'][]=str_replace("\r","",str_replace("\n","",@(String) $item->children('xbrli', true)->period->children('xbrli', true)->startDate));
			    $c['endDate'][]=str_replace("\r","",str_replace("\n","",@(String) $item->children('xbrli', true)->period->children('xbrli', true)->endDate));
			    $c['instant'][]=str_replace("\r","",str_replace("\n","",@(String) $item->children('xbrli', true)->period->children('xbrli', true)->instant));		    
			}			
		}
		foreach($data as $key=>$d)
		{

			$j=0;
			foreach($d['contextRef'] as $finalD)
			{
				for($i=0;$i<count($c['contextRef']);$i++)
				{
					if($c['contextRef'][$i]==$finalD)
					{
						$data[$key]['start'][$j]=str_replace("\r","",str_replace("\n","",str_replace("-","",$c['startDate'][$i])));
						$data[$key]['end'][$j]=str_replace("\r","",str_replace("\n","",str_replace("-","",$c['endDate'][$i])));
						$data[$key]['instant'][$j]=str_replace("\r","",str_replace("\n","",str_replace("-","",$c['instant'][$i])));
					}
				}
				$j++;
			}
		}
		$insVal="";

		foreach($data as $key=>$d)
		{
			$j=0;
			foreach($d['values'] as $val)
			{
				$insVal.="('".$cik."','".$TradingSymbol."','".str_replace("\r","",str_replace("\n","",$key))."','".str_replace("\r","",str_replace("\n","",$val))."','".$d['start'][$j]."','".$d['end'][$j]."','".$d['instant'][$j]."', '".$pathFile[$countDoc]."'),";
				$j++;
			}

		}
	
		if(strlen($insVal))
		{	
			$query="insert into ggsFinance.mineXBRL (cik,ticker, tag, val, startDate,endDate, instantDate, source) values ".substr($insVal,0,-1).";";
			//echo $query."\n";
			mysqli_query($con,$query) or die(mysqli_Error($con));
		}	
		unset($data);
		unset($c);
	
		
/*
		

		try {
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $xmlURL);
		    curl_setopt($ch, CURLOPT_HEADER, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		        'Content-type: text/xml'
		    ));
		    $content = curl_exec($ch);
		    $error   = curl_error($ch);
		    curl_close($ch);
		    $obj = new SimpleXMLElement($content);
		    echo "<pre>";
		    var_dump($obj);
		    echo "</pre>"; 
		}
		catch(Exception $e){
		    var_dump($e);exit;
		}
		zip_close($zip);*/		
	}

		
$countDoc++;
echo $countDoc."\n";
}
	/*
	}
	$zip = new ZipArchive; 
	if (zip_open($filenames)) 
	{ 
		for($i = 0; $i < $zip->numFiles; $i++) 
		{   
			if(substr($zip->getNameIndex($i), -8)!="_cal.xml" && substr($zip->getNameIndex($i), -8)!="_def.xml" && substr($zip->getNameIndex($i), -8)!="_lab.xml" && substr($zip->getNameIndex($i), -8)!="_pre.xml" && substr($zip->getNameIndex($i), -4)!=".xsd")
			{
				$fileZippedName=$zip->getNameIndex($i); 		
				$zip->extractTo('test');
				$data=file_get_contents()		
			}

		} 
	}
	$finalName=$filenames."\\trbd-20111231.xml";
	echo $finalName."\n";
	$matches = file_get_contents($finalName);
	if(count($matches)>0)
	{

		print_r($matches);

	}
	$xml="";

	//$xml = file_get_contents($doc);

*/
?>
