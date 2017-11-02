<?php
session_start();
$_SESSION['clientId']=1;
include 'mysqliConnect.php';

mysqli_Select_db($con, "issuer".$_SESSION['clientId']);

$query="select issuerMap.accountId as accId, shares, name, typeOfInvestor, formatted_address, locationLat, locationLng, counted from issuerMap
inner join (select accountId, sum(holding) as shares, name, count(*) as counted from issuerReg where sourceDt=(select max(sourceDt*1) from issuerReg) group by accountId) as t4 on t4.accountId=issuerMap.accountId and typeOfInvestor='Registered'
union
select issuerMap.accountId, shares, name, typeOfInvestor, formatted_address, locationLat, locationLng, counted from issuerMap
inner join (select accountId, sum(holding) as shares, name, count(*) as counted from issuerTempCDNNobo where sourceDt=(select max(sourceDt*1) from issuerTempCDNNobo) group by accountId) as t5 on t5.accountId=issuerMap.accountId and typeOfInvestor='CDN Nobos'
union
select issuerMap.accountId, shares, name, typeOfInvestor, formatted_address, locationLat, locationLng, counted from issuerMap
inner join (select accountId, sum(holding) as shares, name, count(*) as counted from issuerTempUSNobo where sourceDt=(select max(sourceDt*1) from issuerTempUSNobo) group by accountId) as t6 on t6.accountId=issuerMap.accountId and typeOfInvestor='US Nobos'
union
select issuerMap.accountId, shares, name, typeOfInvestor, formatted_address, locationLat, locationLng, counted from issuerMap
inner join (select accountId, sum(position) as shares, holdName as name, count(*) as counted,cusip from issuerCusPeerOwn where sourceDt=(select max(sourceDt*1) from issuerCusPeerOwn) and cusip='037833100' group by accountId) as t6 on t6.accountId=issuerMap.accountId and typeOfInvestor='Institutions';";

$queryIt=mysqli_query($con,$query) or die(mysqli_error($con));
$min=1000000000000;
$max=0;
while($row=mysqli_fetch_assoc($queryIt))
{
	$data[]=$row;
	/*if($max>$row['shares'])
	{
		$max=$row['shares'];
	}
	elseif($min<$row['shares'])
	{
		$min=$row['shares'];
	}*/
}
// $data['max']=$max;
// $data['min']=$min;

echo utf8_encode(json_encode($data));
?>