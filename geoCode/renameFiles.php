<?php
//rename files
$folder="geoCodeDwnl";

$dir=array_values(array_diff(scandir($folder), array('.', '..')));
$i=1;
foreach($dir as $files)
{
	rename($folder."/".$files, $folder."/".$i.".json");
	$i++;
}


?>