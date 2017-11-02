<?php


$file=file_get_contents('execUS.txt');

$lines=explode("\n", $file);


$api="AIzaSyAnbf3JJQUH5GBLbMeLv2F5NLvlBoccsO0";
$cse="018310063431102106189:hdyurjrisg4";

foreach($lines as $line)
{
	$peop[]=explode("\t",$line);

}
//count($peop)

$min=1;
$max=100;

$folder="../../googleFullResults/googleResults";
$dir=array_values(array_diff(scandir($folder), array('.', '..')));

$maxDay=21000;

$beginDay=count($dir);

for($i=$beginDay;$i<count($peop);$i++)
{
	$searchQ=str_replace("++","+",urlencode(trim(str_replace(","," ",str_replace("."," ",$peop[$i][1])))));
	$query="https://www.googleapis.com/customsearch/v1?key=".$api."&cx=".$cse."&q=".$searchQ."&searchType=image";
	$data=@file_get_contents($query);
		//$data=false;
	if($data===false)
	{
		sleep(120);
		$i=$i-1;
	}
	else
	{
		file_put_contents($folder."/".$peop[$i][0].".txt", $data);

		$randNr=rand($min,$max)/100;	
		sleep(1+$randNr);
		echo "fileNr=".$i." (".($i-$beginDay)."/".($maxDay-$beginDay).")"."\n";
	}

	//echo $query."\n";
}
//$query = 'WATERS%20CORP%20/DE/%20Douglas%20A%20Berthiaume';
//$url = "http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".$query;



/*
$opts = array(
    'socket' => array(
        // IP:PORT use 0 value if you want your operating system to decide
        'bindto' => '70.50.38.54:0',
    )
);

// create the context...
$context = stream_context_create($opts);


$body = file_get_contents($url);
$json = json_decode($body);

for($x=0;$x<count($json->responseData->results);$x++){

echo "<b>Result ".($x+1)."</b>";
echo "<br>URL: ";
echo $json->responseData->results[$x]->url;


}
*/
?>