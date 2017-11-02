<?php
include 'mysqli_connect.php';
//Create table

$db="novaGoogle";
$table="googlePeoImg";

$query="create table if not exists ".$db.".".$table." (id int, subId int, 
	title varchar(400), 
	htmlTitle varchar(400), 
	link varchar(400), 
	displayLink varchar(400), 
	snippet varchar(400), htmlSnippet varchar(400), 
	mime varchar(400), 
	contextLink varchar(400), 
	height varchar(30), 
	width varchar(30), 
	byteSize varchar(30), 
	thumbnailLink varchar(400), 
	thumbnailHeight varchar(30), 
	thumbnailWidth varchar(30), 
	dt datetime default current_timestamp);";

mysqli_query($con,$query) or die(mysqli_error($con));


$folder="../../googleFullResults/googleResults";
$dir=array_values(array_diff(scandir($folder), array('.', '..')));


for($i=0;$i<count($dir);$i++)
{
	$file=file_get_contents($folder."/".$dir[$i]);
	$arr=json_decode($file);
	if(Isset($arr->items))
	{
		for($j=0;$j<count($arr->items);$j++)
		{
			$id[]=str_replace(".txt", "", $dir[$i]);
			$sub_id[]=mysqli_real_escape_string($con,$j);
			$title[]=mysqli_real_escape_string($con,$arr->items[$j]->title);
			$htmlTitle[]=mysqli_real_escape_string($con,$arr->items[$j]->htmlTitle);
			$link[]=mysqli_real_escape_string($con,$arr->items[$j]->link);
			$displayLink[]=mysqli_real_escape_string($con,$arr->items[$j]->displayLink);
			$snippet[]=mysqli_real_escape_string($con,$arr->items[$j]->snippet);
			$htmlSnippet[]=mysqli_real_escape_string($con,$arr->items[$j]->htmlSnippet);
			$mime[]=mysqli_real_escape_string($con,$arr->items[$j]->mime);

			$contextLink[]=mysqli_real_escape_string($con,$arr->items[$j]->image->contextLink);
			$height[]=mysqli_real_escape_string($con,$arr->items[$j]->image->height);
			$width[]=mysqli_real_escape_string($con,$arr->items[$j]->image->width);
			$byteSize[]=mysqli_real_escape_string($con,$arr->items[$j]->image->byteSize);
			$thumbnailLink[]=mysqli_real_escape_string($con,$arr->items[$j]->image->thumbnailLink);
			$thumbnailHeight[]=mysqli_real_escape_string($con,$arr->items[$j]->image->thumbnailHeight);
			$thumbnailWidth[]=mysqli_real_escape_string($con,$arr->items[$j]->image->thumbnailWidth);
		}	
	}
	unset($arr);
}

$values="";

$query="insert into ".$db.".".$table." values ";
for($i=0;$i<count($id);$i++)
{
	$values.="('".$id[$i]."',
		'".$sub_id[$i]."',
		'".$title[$i]."',
		'".$htmlTitle[$i]."',
		'".$link[$i]."',
		'".$displayLink[$i]."',
		'".$snippet[$i]."',
		'".$htmlSnippet[$i]."',
		'".$mime[$i]."',
		'".$contextLink[$i]."',
		'".$height[$i]."',
		'".$width[$i]."',
		'".$byteSize[$i]."',
		'".$thumbnailLink[$i]."',
		'".$thumbnailHeight[$i]."',		
		'".$thumbnailWidth[$i]."',
		now()),";

	if($i%3000==0 && $i!=0)
	{
		mysqli_query($con,$query.substr($values,0,-1)) or die(mysqli_error($con));
		$values="";
		echo $i."\n";
	}
}

if(strlen($values)>0)
{
	mysqli_query($con,$query.substr($values,0,-1)) or die(mysqli_error($con));	
}


?>