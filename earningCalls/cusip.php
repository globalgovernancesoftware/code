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

$websitePos=['0','1','2','3','4','5','6','7','8','9','letters'];
print_r($websitePos);
$i=0;


//for($d=0;$d<count($websitePos);$d++)
for($d=0;$d<11;$d++)
{	
	$nrOfPages=0;
	$nrOfPageParse=0;
	$url="http://canadianwarrants.com/cusip/".$websitePos[$d].".htm";
	$html=file_get_html($url);

	//Check last pages
	if(is_object($html))
	{
		if(is_object($html->find('[class=last]',0)))
		{

			$nrOfPages=$html->find('[class=last]',0)->parent->find('a',0)->attr['href'];	
			echo $nrOfPages."<BR>";

		}
		else
		{
			$nrOfPages=0;
		}
		$nrOfPageParse=substr($nrOfPages, -5);
		$nrOfPageParse=substr($nrOfPageParse, 0,1);
		if($nrOfPageParse=="s")
		{
			$nrOfPageParse=0;
		}
		echo $nrOfPageParse."<BR>";
		
		$t=0;
		foreach($html->find('table') as $table)
		{
			if(strpos($table->attr['style'],"collapse;width:426pt")!==false)
			{
				foreach($table->find('tr') as $row)
				{
					$compName[]=mysqli_real_escape_string($con,html_entity_decode(trim(strip_tags($row->find('td',0)->innertext))));
					$ticker[]=mysqli_real_escape_string($con,html_entity_decode(strip_tags($row->find('td',1)->innertext)));
					$cusip[]=mysqli_real_escape_string($con,html_entity_decode(str_replace(" ","",trim(strip_tags($row->find('td',2)->innertext)))));
				}
			}
			if(strpos($table->attr['style'],"collapse; width: 426pt")!==false)
			{
				foreach($table->find('tr') as $row)
				{
					$compName[]=mysqli_real_escape_string($con,html_entity_decode(trim(strip_tags($row->find('td',0)->innertext))));
					$ticker[]=mysqli_real_escape_string($con,html_entity_decode(strip_tags($row->find('td',1)->innertext)));
					$cusip[]=mysqli_real_escape_string($con,html_entity_decode(str_replace(" ","",trim(strip_tags($row->find('td',2)->innertext)))));
				}
			}											
		}

		for($i=1;$i<=$nrOfPageParse;$i++)
		{

			unset($html);
			$url="http://canadianwarrants.com/cusip/".$websitePos[$d].$i.".htm";
			$html=file_get_html($url);
			if(is_object($html))
			{
				foreach($html->find('table') as $table)
				{
					if(strpos($table->attr['style'],"collapse;width:426pt")!==false)
					{
						foreach($table->find('tr') as $row)
						{
							$compName[]=mysqli_real_escape_string($con,html_entity_decode(trim(strip_tags($row->find('td',0)->innertext))));
							$ticker[]=mysqli_real_escape_string($con,html_entity_decode(strip_tags($row->find('td',1)->innertext)));
							$cusip[]=mysqli_real_escape_string($con,html_entity_decode(str_replace(" ","",trim(strip_tags($row->find('td',2)->innertext)))));
						}
					}
					if(strpos($table->attr['style'],"collapse; width: 426pt")!==false)
					{
						foreach($table->find('tr') as $row)
						{
							$compName[]=mysqli_real_escape_string($con,html_entity_decode(trim(strip_tags($row->find('td',0)->innertext))));
							$ticker[]=mysqli_real_escape_string($con,html_entity_decode(strip_tags($row->find('td',1)->innertext)));
							$cusip[]=mysqli_real_escape_string($con,html_entity_decode(str_replace(" ","",trim(strip_tags($row->find('td',2)->innertext)))));
						}
					}							
				}	

			}
		}
	}
	unset($html);		
}

$values="";
for($i=0;$i<count($compName);$i++)
{
	$values.='("'.$compName[$i].'","'.$ticker[$i].'","'.$cusip[$i].'", now()),';
}
$query="insert into nova13fDb.caCusip values ".substr($values,0,-1);
mysqli_query($con,"delete nova13fDb.caCusip.* from nova13fDb.caCusip;") or die(mysqli_Error($con));
mysqli_query($con,$query) or die(mysqli_Error($con));
?>
