<?php
ini_set('memory_limit', '-1');

ini_set('max_execution_time', 0);
$host="novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com"; // Host name 
$username="novaUser"; // Mysql username 
$password="Tyrose1214"; // Mysql password 

$con=mysqli_connect("$host","$username","$password","novaNSAR") or die("Could not connect");


//SELECT FILINGS
$query="select source from serNSAR group by source;";

$queryIt=mysqli_query($con, $query);

$filings=array();
while($row=mysqli_fetch_array($queryIt))
{
	
	$filings[]=$row['source'];

}
//print_R($filings);
//SELECT FUNDS FOR SERIES and NSAR
echo "<html><body><table>";
for($i=0;$i<count($filings);$i++)
{
	//SELECT FUNDS FOR SERIES
	$seriesName=array();
	$seriesId=array();
	
	$querySeries="select * from (select ownerCik, seriesId, seriesName from serNSAR where source='".$filings[$i]."') as t group by seriesName;";
	$querySeriesIt=mysqli_query($con,$querySeries);

	while($rowSeries=mysqli_fetch_array($querySeriesIt))
	{	
		$seriesName[]=$rowSeries['seriesName'];
		$seriesId[]=$rowSeries['seriesId'];
	}	
	

	//SELECT FUNDS FOR NSAR
	$nsarFund=array();
	$codeSource=array();


	$queryNSAR="select concat(sourceN,mid(midN,5,2)) as m3, ansN as fundName from masterNSAR 
	where typeN='007' and midN like ' C02%' and sourceN='".$filings[$i]."';";
	$queryNSARIt=mysqli_query($con,$queryNSAR);
	
	while($rowF=mysqli_fetch_array($queryNSARIt))
	{	
		$nsarFund[]=$rowF['fundName'];
		$codeSource[]=$rowF['m3'];	
	}		
	
	for($j=0;$j<count($seriesName);$j++)
	{
		$max=0;
		for($k=0;$k<count($nsarFund);$k++)
		{
			//echo $fundName[$k];
			$compare=similar_text(strtoupper($seriesName[$j]), strtoupper($nsarFund[$k]));
			
			if($compare>$max)
			{
				$test=$seriesName[$j]."|".$nsarFund[$k]."|".$filings[$i]."|".$seriesId[$j];
				$max=$compare;
			}
		}
		$t=split("\|", $test);
		echo "<tr><td>".$t[0]."</td><td>".$t[1]."</td><td>".$t[2]."</td><td>".$t[3]."</td></tr>";
		//echo $test."<br>";
	}

}
echo "</table></body></html>";

?>