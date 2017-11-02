<?php
header('Content-Type: text/html; charset=utf-8');
//SET PATH=%PATH%;C:\xampp\php
ini_set('memory_limit', '-1');

ini_set('max_execution_time', 0);

include('simplehtmldom_1_5/simple_html_dom.php');

$host="novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com"; // Host name 
$username="novaUser"; // Mysql username 
$password="Tyrose1214"; // Mysql password 

// Connect to server and select databse.
//$con=mysqli_connect("$host", "$username", "$password")or die("cannot connect"); 

$con = mysqli_init();
mysqli_options($con, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
$con->ssl_set(null, null, 'rds-ca-2015-root.pem', null, null);
mysqli_real_connect($con,"$host", "$username", "$password", NULL, 3306, NULL, MYSQLI_CLIENT_SSL)or die("cannot connect"); 
mysqli_set_charset($con, "utf8");

$days=60;
$curTime=date('Y-m-d', time());

$i=0;
echo $curTime;

for($d=0;$d<$days;$d++)
{
	$dateVar=date('Y-m-d', strtotime($curTime. ' + '.$d.' days'));	
	$url="http://web.tmxmoney.com/earnings_cal.php?market=All&date=".$dateVar;
	echo $d."\n";
	$html=file_get_html($url);

	foreach($html->find('[class=caltable]',0)->find('tr') as $data)
	{

		//var_Dump($data);
		$j=0;
		foreach($data->find('td') as $tr)
		{
			if($j==7)
			{
				$t=trim(strip_tags($tr->innertext));
				$t2=explode("(",$t);
				$var[$i][$j]=mysqli_real_escape_string($con,trim($t2[0]));
				$j++;
				$var[$i][$j]=mysqli_real_escape_string($con,str_replace(")","",trim($t2[1])));
				$j++;				
			}
			else
			{
				$var[$i][$j]=mysqli_real_escape_string($con,trim(strip_tags($tr->innertext)));
				$j++;				
			}
			if($j==10)
			{
				$var[$i][$j]=mysqli_real_escape_string($con,str_replace("-","",$dateVar));				
			}
		}

		$i++;
	}

	unset($html);

}

$val="";
for($d=0;$d<$days;$d++)
{	
	$dateVar=date('Y-m-d', strtotime($curTime. ' + '.$d.' days'));	
	$val.="earnDate='".str_replace("-","",$dateVar)."' or ";

}

$val=substr($val,0,-4);
$query="delete novaEarnings.tEarn.* from novaEarnings.tEarn where ".$val.";";
$queryIt=mysqli_query($con,$query);

$values="";
$z=0;
$query="insert into novaEarnings.tEarn (ticker,compName,period,estNr,timeE,estEps,eps,surpriseP,surpriseN,earnDate,dt) values ";
$val="";

foreach($var as $v)
{
	$val=implode("','",$v);
	$val=str_replace(",'More'","", $val);
	$values.="('".$val."', now()),";
	
	if($z%1000==0)
	{
		$fullQ=$query.substr($values,0,-1);
		$queryIt=mysqli_query($con,$fullQ) or die(mysqli_error($con));
		$values="";
		$fullQ="";
	}
	$z++;
}


if(strlen($values)>15)
{
	$fullQ=$query.substr($values,0,-1);
	$queryIt=mysqli_query($con,$fullQ) or die(mysqli_error($con));		
}

?>