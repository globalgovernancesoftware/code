<?php

$folder="../../googleFullResults/googleResults";
$dir=array_values(array_diff(scandir($folder), array('.', '..')));


for($i=0;$i<400;$i++)
{
	$file=file_get_contents($folder."/".$dir[$i]);
	$arr=json_decode($file);
	$img=$arr->items[0]->link;
	echo "<img src='".$img."'></img>";
}




?>