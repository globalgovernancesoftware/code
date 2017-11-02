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

/*

SELECT * FROM (
SELECT * FROM (
SELECT 20170103 as existingDates UNION SELECT 20170104 UNION SELECT 20170105 UNION SELECT 20170106 UNION SELECT 20170109 UNION SELECT 20170110 UNION SELECT 20170111 UNION SELECT 20170112 UNION SELECT 20170113 UNION SELECT 20170116 UNION SELECT 20170117 UNION SELECT 20170118 UNION SELECT 20170119 UNION SELECT 20170120 UNION SELECT 20170123 UNION SELECT 20170124 UNION SELECT 20170125 UNION SELECT 20170126 UNION SELECT 20170127 UNION SELECT 20170130 UNION SELECT 20170131 UNION SELECT 20170201 UNION SELECT 20170202 UNION SELECT 20170203 UNION SELECT 20170206 UNION SELECT 20170207 UNION SELECT 20170208 UNION SELECT 20170209 UNION SELECT 20170210 UNION SELECT 20170213 UNION SELECT 20170214 UNION SELECT 20170215 UNION SELECT 20170216 UNION SELECT 20170217 UNION SELECT 20170220 UNION SELECT 20170221 UNION SELECT 20170222 UNION SELECT 20170223 UNION SELECT 20170224 UNION SELECT 20170227 UNION SELECT 20170228 UNION SELECT 20170301 UNION SELECT 20170302 UNION SELECT 20170303 UNION SELECT 20170306 UNION SELECT 20170307 UNION SELECT 20170308 UNION SELECT 20170309 UNION SELECT 20170310 UNION SELECT 20170313 UNION SELECT 20170314 UNION SELECT 20170315 UNION SELECT 20170316 UNION SELECT 20170317 UNION SELECT 20170320
) as t
left join (select dateS from sedarf.sedarfilings group by dateS) as t2 on t.existingDates=t2.dateS) as t3
where dateS is null;



INSERT INTO sedarf.sedarfilings 
SELECT * FROM sedarf.sedarAdditional;

*/

$yearMissing=2017;
$monthMissing=04;
$dayMissing=27;

for($pageNr=1;$pageNr<25;$pageNr++)
{
	echo $pageNr;
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
		//Create Query

		for($j=0; $j<=$i-1; $j++)
		{
		$sqlPartial= "('".$link[$j]."', '".$filing[$j]."', '".$date[$j]."', '".$ids[$j]."')";
		$sqlString=$sqlString.$sqlPartial.", ";

		}
		$sqlString=substr($sqlString,0,-2);

		if(strlen($sqlString)>0)
		{		
			$sqlFullString="INSERT INTO sedarf.sedarAdditional (linkS, typeS, dateS, idsS) VALUES ".$sqlString.";";
			mysqli_query($con,$sqlFullString) or die(mysqli_error($sqlString));
		}

		//echo $sqlFullString;		  
		//$selected = mysqli_select_db("sedarf") or die("Could not select sedarf");		  



	}

}

?>
