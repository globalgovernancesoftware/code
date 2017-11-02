<?php
ini_set('memory_limit', '-1');
require_once("../connection/simple_html_dom.php");
include '../connection/mysqliConnect.php';
//Check MaxDate

$sqlmaxDate="select * from (select * from (select idsS from sedarf.sedarfilings group by idsS) as t
left join sedarf.listCompanies on idsS=id) as t2 where compName is not null;"; 			  
//$selected = mysqli_select_db("sedarf") or die("Could not select sedarf");
$query2=mysqli_query($con,$sqlmaxDate);

$newComp=array();
while($fresults2=mysqli_fetch_array($query2))
{
	$newComp[]= $fresults2['idsS'];
}


$finalData=array();
$countComp=count($newComp);
for($compNr=0;$compNr<$countComp;$compNr++)
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
	foreach($html->find('.bt') as $ticker)
	{
		if(strip_tags($ticker)=="Stock Symbol:")
		{
			$finalData[$compNr]['ticker']=trim(strip_tags($ticker->next_sibling()));	
			$finalData[$compNr]['id']=$newComp[$compNr];				
		}

	}	
	$query="insert ignore into nsMine.mainTickCIK (mainId,sedarTicker) values ('".$finalData[$compNr]['id']."','".$finalData[$compNr]['ticker']."')
	on duplicate key update sedarTicker='".$finalData[$compNr]['ticker']."'";
	mysqli_query($con,$query);
	if($i%100==0){
		echo $compNr."\n";
	}
}


//Clean Date

//Set not defunct
$query="update mainTickCIK as t
inner join (
select * from sedarf.sedarfilings where dateS>20150101 group by idsS) as t2 on idsS=t.mainId
set defunct=0;";

mysqli_query($con,$query);

//Set  defunct
$query="update mainTickCIK set defunct=1 where defunct is null and length(mainId)=8";

mysqli_query($con,$query);

//Set BBID
$query="update mainTickCIK as t
inner join (
select ID_BB_SEC_NUM_DES,FEED_SOURCE,ID_BB_GLOBAL, SUBSTRING_INDEX(SUBSTRING_INDEX(ID_BB_SEC_NUM_DES,'-',1),'/',1) as mmId from (SELECT * FROM bsym.master where FEED_SOURCE in ('CN') and SECURITY_TYP in ('Common Stock','REIT','Equity WRT') ) as t  where (length(ID_BB_SEC_NUM_DES)<7 or ID_BB_SEC_NUM_DES  like '%WS%') order by ID_BB_SEC_NUM_DES) as t2 on mmId=t.sedarTicker
set bbId=ID_BB_GLOBAL, ticker=ID_BB_SEC_NUM_DES
where length(mainId)=8;";

mysqli_query($con,$query);

//Clean NA

$query='update nsMine.mainTickCIK set bbId=null, ticker="", sedarTicker="" where ticker="NA" and sedarTicker="NA" and mainId<>"00002236";';
mysqli_query($con,$query);
?>
