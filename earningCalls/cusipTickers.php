<?php
$host="novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com"; // Host name 
$username="webserverUser"; // Mysql username 
$password="6wTqNkGK794s"; // Mysql password 

// Connect to server and select databse.
//$con=mysqli_connect("$host", "$username", "$password")or die("cannot connect"); 

$con = mysqli_init();
mysqli_options($con, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
$con->ssl_set(null, null, 'rds-ca-2015-root.pem', null, null);
mysqli_real_connect($con,"$host", "$username", "$password", NULL, 3306, NULL, MYSQLI_CLIENT_SSL)or die("cannot connect"); 
mysqli_set_charset($con, "utf8");

$query="select * from (
select * from (
select compName as company, cusip as cu from ids.cusip13fUC ) as t2
left join ids.masterCusipW on masterCusipW.cusip=t2.cu) as t
where isnull(cusip);";

$queryIt=mysqli_query($con,$query);

while($row=mysqli_fetch_array($queryIt))
{
	$cusipA[]=$row['cu'];
}

for($i=0;$i<count($cusipA);$i++)
{
	$html=file_get_contents("https://quotes.fidelity.com/mmnet/SymLookup.phtml?reqforlookup=REQUESTFORLOOKUP&productid=mmnet&isLoggedIn=mmnet&rows=50&for=stock&by=cusip&criteria=".$cusipA[$i]."&submit=Search");


	$hT=explode("\r\n",$html);
	$compName="";
	$ticker="";
	foreach($hT as $links)
	{
		if(strpos( $links,'<tr><td height="20" nowrap><font class="smallfont">')!==false)
		{
			$length=strlen('<tr><td height="20" nowrap><font class="smallfont">');
			$compName=trim(strip_tags($links));
		}
		if(strpos( $links,'<td align="center" width="20%"><font>')!==false)
		{
			$ticker=trim(strip_tags($links));
		}	
	}


	$values="('".mysqli_real_escape_string($con,$compName)."','".mysqli_real_escape_string($con,$ticker)."','".mysqli_real_escape_string($con,$cusipA[$i])."')";

	unset($html);	


	mysqli_query($con,"insert into ids.cusip13fC (compName, ticker, cusip) values ".$values);
	echo $i."\n";

}



?>