<?php
include 'mysqliConnect.php';

$query="select id, filedNamedE from novaPeople.novaPMDocs limit 1;";
$queryIt=mysqli_query($con,$query);
while($row=mysqli_fetch_array($queryIt))
{
	$id[]=$row['id'];
	$filedNamedE[]=$row['filedNamedE'];
}

//VARIABLES

$cik="<OWNER-CIK>";
$sid="<SERIES-ID>";
$sName="<SERIES-NAME>";
$cid="<CLASS-CONTRACT-ID>";
$cName="<CLASS-CONTRACT-NAME>";
$cTicker="<CLASS-CONTRACT-TICKER-SYMBOL>";

$identifiers=array($cik,$sid,$sName,$cid,$cName,$cTicker);

for($i=0;$i<count($identifiers);$i++)
{
	$idenLength[]=strlen($identifiers[$i]);
}

print_R($identifiers);

$data=array();


$html = file("ftp://ftp.sec.gov/".$filedNamedE[0]);

foreach ($html as $lineNumber => $line) {
    switch ($line){
   		case stripos($line,$identifiers[0]) !== false:
     		$data['cik'][]=substr($line,$idenLength[0],strleN($line)-$idenLength[0]);
    		break;
   		case stripos($line,$identifiers[1]) !== false:
     		echo $line;
     		break;
	}

}
print_r($data);
?>