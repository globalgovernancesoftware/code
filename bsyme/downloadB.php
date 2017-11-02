<?php

$con = mysqli_connect("127.0.0.1","root","","VR1","3306");
	  
$selected = mysqli_select_db($con,"ids") or die("Could not select sedarf");

$query="select ID_BB_SEC_NUM_DES,FEED_SOURCE from (select * from ids.bsym where FEED_SOURCE='US') as t group by ID_BB_SEC_NUM_DES;";
$queryIt=mysqli_query($con,$query);

//TO BE REDONE 396

$url="http://www.bloomberg.com/markets/api/management/executives/";

while($row=mysqli_fetch_array($queryIt))
{
	$ID_BB_SEC_NUM_DES[]=$row['ID_BB_SEC_NUM_DES'];
	$FEED_SOURCE[]=$row['FEED_SOURCE'];
}
$mm=8200;
for($i=$mm;$i<count($ID_BB_SEC_NUM_DES);$i++)
{
	 //Code to get the file...
	$all_urls[]=$url.$ID_BB_SEC_NUM_DES[$i].":".$FEED_SOURCE[$i];
	$finalID[]=$ID_BB_SEC_NUM_DES[$i];
	$finalSource[]=$FEED_SOURCE[$i];
}

$chunked_urls = array_chunk($all_urls,2); 
$z=$mm;
foreach($chunked_urls as $i => $urls) 
{
    $handles = [];    
    $mh = curl_multi_init();  

    foreach($urls as $url) 
    {
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($mh, $ch);
        $handles[] = $ch;
        $urlLink=$url;
    }

    // execute all queries simultaneously, and continue when all are complete
    $running = null;
    do 
    {
        curl_multi_exec($mh, $running);
    } while ($running);

    foreach($handles as $handle)
    {
    	$uuu="C:/Novashare/Downloads/BSYM/EXEC/US1/".$finalID[$z].".json";
        //file_put_contents("/tmp/output",curl_multi_getcontent($handle),FILE_APPEND);
         $fh = fopen($uuu,"w");
		fwrite($fh,curl_multi_getcontent($handle));

		fclose($fh);
        curl_multi_remove_handle($mh, $handle);       
        $z++; 
    }

    curl_multi_close($mh);

    print "Finished chunk $i\n";
    sleep(1.5);
}


?>