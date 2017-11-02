<?php
ini_set('memory_limit', '-1');
require_once("simple_html_dom.php");
include 'mysqliConnect.php';
//Check MaxDate

$sqlmaxDate="Select max(dateS) as 'dateMax' from sedarf.sedarfilings;"; 			  
//$selected = mysqli_select_db("sedarf") or die("Could not select sedarf");
$query2=mysqli_query($con,$sqlmaxDate);

				
				while($fresults2=mysqli_fetch_array($query2))
				{
				$dateLast= $fresults2['dateMax'];
				}

	echo $dateLast;
// Create DOM from URL or file
//$html = file_get_html('http://sedar.com/new_docs/all_new_pc_filings_en.htm');
$yearMissing=2017;
$monthMissing=03;
$dayMissing=29;

$pageNr=1;
$file = fopen("missingDate2017.csv","w");

for($monthAr=1;$monthAr<13;$monthAr++)
{
	for($dayAr=1;$dayAr<32;$dayAr++)
	{
		$html = file_get_html('http://sedar.com/FindCompanyDocuments.do?lang=EN&page_no='.$pageNr.'&company_search=All+%28or+type+a+name%29&document_selection=0&industry_group=A&FromDate='.$dayAr.'&FromMonth='.$monthAr.'&FromYear='.$yearMissing.'&ToDate='.$dayAr.'&ToMonth='.$monthAr.'&ToYear='.$yearMissing.'&Variable=Issuer');		
	// Parse all links 

		$month="";	
		$founded=0;

		foreach($html->find('td ]') as $td)
		{
			$td=trim(strip_tags($td));
			$td=trim(strip_tags($td));
			if(substr($td,strlen($td)-4,3)=='201' && strlen($td)==11)
			{

				$year=substr($td,strlen($td)-4,4);
				$month=substr($td,strlen($td)-11,3);
				$day=substr($td,strlen($td)-7,2);
				$founded=1;
			}
			if(substr($td,strlen($td)-4,3)=='201' && strlen($td)==10)
			{

				$year=substr($td,strlen($td)-4,4);
				$month=substr($td,strlen($td)-10,3);
				$day='0'.substr($td,strlen($td)-6,2);
				$founded=1;


			}
			if($month=='Dec')
			{
			$month='12';
			}
			if($month=='Jan')
			{
			$month='01';
			}
			if($month=='Feb')
			{
			$month='02';
			}
			if($month=='Mar')
			{
			$month='03';
			}
			if($month=='Apr')
			{
			$month='04';
			}
			if($month=='May')
			{
			$month='05';
			}
			if($month=='Jun')
			{
			$month='06';
			}
			if($month=='Jul')
			{
			$month='07';
			}
			if($month=='Aug')
			{
			$month='08';
			}
			if($month=='Sep')
			{
			$month='09';
			}
			if($month=='Oct')
			{
			$month='10';
			}
			if($month=='Nov')
			{
			$month='11';
			}
				
		}			

		if($founded==1)
		{
			echo $year.$month.$day."\n";
			$arr[]=$year.$month.$day;
			fputcsv($file,$arr);
			unset($arr);
		}
	}

}

?>
