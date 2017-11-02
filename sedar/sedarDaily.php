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
$yearMissing=2016;
$monthMissing=03;
$dayMissing=29;

for($pageNr=1;$pageNr<18;$pageNr++)
{
	$html = file_get_html('http://sedar.com/FindCompanyDocuments.do?lang=EN&page_no='.$pageNr.'&company_search=All+%28or+type+a+name%29&document_selection=0&industry_group=A&FromDate='.$dayMissing.'&FromMonth='.$monthMissing.'&FromYear='.$yearMissing.'&ToDate='.$dayMissing.'&ToMonth='.$monthMissing.'&ToYear='.$yearMissing.'&Variable=Issuer');

	$filing=array();
	$link=array(); 
	$date=array();
	$ids=array();
	$sqlPartial="";
	$sqlString="";
	$i=0;
	$month="";
		
	// Parse all links 
	foreach($html->find('td ]') as $td)
	{
		$td=trim(strip_tags($td));
		$td=trim(strip_tags($td));
		if(substr($td,strlen($td)-4,3)=='201' && strlen($td)==11)
		{

		$year=substr($td,strlen($td)-4,4);
		$month=substr($td,strlen($td)-11,3);
		$day=substr($td,strlen($td)-7,2);

		}
		if(substr($td,strlen($td)-4,3)=='201' && strlen($td)==10)
		{

		$year=substr($td,strlen($td)-4,4);
		$month=substr($td,strlen($td)-10,3);
		$day='0'.substr($td,strlen($td)-6,2);

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

	if(isset($year) && isset($date))
	{
		$newFilingDate = $year.$month.$day;
		echo $newFilingDate;
		/*if(intval($dateLast)>=intval($newFilingDate))
		{
		echo "old";
		}
		else

		{
		*/
		// Create DOM from URL or file


		$filing=array();
		$link=array(); 
		$date=array();
		$ids=array();
		$sqlPartial="";
		$sqlString="";
		$i=0;
			
		// Parse all links 
		foreach($html->find('form') as $forms)
		{
			if (fnmatch("*GetFile*",$forms->action))
			{
				  $filingunform=strip_tags($forms->last_child());
				  $filingunform=str_replace("'","",$filingunform);
			      $filing[]=trim($filingunform);
			      $link[]=trim($forms->action);
			      $date[]=$newFilingDate;
			      $idsext=substr($forms->action,strpos($forms->action,'&issuerNo=')+10,8);
			      $ids[]=$idsext;
			      $i=$i+1;
			}
		   
		}
		  echo $i;  
		//Create Query

		for($j=0; $j<=$i-1; $j++)
		{
		$sqlPartial= "('".$link[$j]."', '".$filing[$j]."', '".$date[$j]."', '".$ids[$j]."')";
		$sqlString=$sqlString.$sqlPartial.", ";

		}
		$sqlString=substr($sqlString,0,-2);

		$sqlFullString="INSERT INTO sedarf.sedarAdditional (linkS, typeS, dateS, idsS) VALUES ".$sqlString.";";


		//echo $sqlFullString;		  
		//$selected = mysqli_select_db("sedarf") or die("Could not select sedarf");
		mysqli_query($con,$sqlFullString) or die(mysqli_error($con));		  



}

//}	
}

?>
