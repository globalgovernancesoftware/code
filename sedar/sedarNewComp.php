<?php
ini_set('memory_limit', '-1');
require_once("simple_html_dom.php");
include 'mysqliConnect.php';
//Check MaxDate

$sqlmaxDate="select * from (select * from (select idsS from sedarf.sedarfilings group by idsS) as t
left join sedarf.listCompanies on idsS=id) as t2 where compName is null;"; 			  
//$selected = mysqli_select_db("sedarf") or die("Could not select sedarf");
$query2=mysqli_query($con,$sqlmaxDate);

				$newComp=array();
				while($fresults2=mysqli_fetch_array($query2))
				{
					$newComp[]= $fresults2['idsS'];
				}


$finalData=array();
for($compNr=0;$compNr<count($newComp);$compNr++)
{
	$begin='/DisplayProfile.do?lang=EN&issuerType=03&issuerNo=';
	$finalData[$compNr]=array();
	$html = file_get_html('http://sedar.com'.$begin.$newComp[$compNr]);

	$filing=array();
	$link=array(); 
	$date=array();
	$ids=array();
	$sqlPartial="";
	$sqlString="";
	$i=0;
	$month="";
		//var_Dump($html);
	// Parse all links 
	foreach($html->find('TITLE') as $title)
	{
		$finalData[$compNr]['newCompName']=trim(strip_tags($title));	
		$finalData[$compNr]['id']=$newComp[$compNr];	

	}	

	$query="insert into sedarf.listCompanies (compName,id,links) values ('".mysqli_real_escape_string($con,$finalData[$compNr]['newCompName'])."','".$finalData[$compNr]['id']."','".$begin.$finalData[$compNr]['id']."')";

	mysqli_query($con,$query);
}

?>
