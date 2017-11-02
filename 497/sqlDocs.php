<?php

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

$cik="<OWNER-CIK>";
$seriesId="<SERIES-ID>";
$serName="<SERIES-NAME>";
$classId="<CLASS-CONTRACT-ID>";
$className="<CLASS-CONTRACT-NAME>";
$classTicker="<CLASS-CONTRACT-TICKER-SYMBOL>";
$cikLen=strlen($cik);
$seriesIdLen=strlen($seriesId);
$serNameLen=strlen($serName);
$classIdLen=strlen($classId);
$classNameLen=strlen($className);
$classTickerLen=strlen($classTicker);

include 'mysqliConnect.php';

$query="select id, filedNamedE from novaPeople.novaPMDocs;";
$queryIt=mysqli_query($con,$query);
$i=0;
while($row=mysqli_fetch_array($queryIt))
{
	$id[]=$row['id'];
	$filedNamedE[]=$row['filedNamedE'];
}

for($k=14452;$k<count($id);$k++)
{
	$fold=explode("/",$filedNamedE[$k]);
	$folder= $fold[2];

	//Parse foldername

	$fPart=explode("-",substr($fold[3],0,20));
	$subFolder=intval($fPart[0]).$fPart[1].$fPart[2];
	//$subFolder=preg_replace( $pattern, "",str_replace("-","",str_replace(".txt","",$fold[3])));
	$file=substr($fold[3],0,20).".nc";
	$lines = array();
	$lines = file("../test/497/".$folder."/".$subFolder."/".$file);
	echo $k."\n";


	$data=array();

	$data['file']=$file;
	$data['cik']="";

	$series=array();

	$class=array();

	$i=0;
	$j=0;

	foreach($lines as $line)
	{
		$lineLen=strlen($line);

		switch ($line) 
		{
		    case strpos($line, $cik):
		        $data['cik']=mysqli_real_escape_string($con,substr($line,($cikLen-$lineLen)));
		        break;

		    case strpos($line, $seriesId):
		        $i++;	    
		        $series[$i]['seriesId']=mysqli_real_escape_string($con,substr($line,($seriesIdLen-$lineLen)));
		        $j=0;
		        break;

		    case strpos($line, $serName):
		        $series[$i]['seriesName']=mysqli_real_escape_string($con,substr($line,($serNameLen-$lineLen)));
		        break;		        

		    case strpos($line, $classId):
		    	$j++;
		        $class[$i][$j]['classId']=mysqli_real_escape_string($con,substr($line,($classIdLen-$lineLen)));
		        break;

		    case strpos($line, $className):
		        $class[$i][$j]['className']=mysqli_real_escape_string($con,substr($line,($classNameLen-$lineLen)));
		        break;

		    case strpos($line, $classTicker):
		        $class[$i][$j]['classTicker']=mysqli_real_escape_string($con,substr($line,($classTickerLen-$lineLen)));
		        break;	
		    case strpos($line, "</SERIES-AND-CLASSES-CONTRACTS-DATA>"):
		        
		        break 2;	

		    default:
		        break;
		}
	}
	$t=1;
	//print_r($class);
	foreach($series as $ser)
	{
		
		$query="insert into novaPeople.docSeries values (".$id[$k].",'".$ser['seriesId']."','".$ser['seriesName']."');";
		
		$queryIt=mysqli_query($con,$query) or die(mysqli_error($con));
		
		for($q=1;$q<count($class[$t]);$q++)
		{
			$query="insert into novaPeople.docClass values (".$id[$k].",'".$class[$t][$q]['classId']."','".$class[$t][$q]['className']."','".$class[$t][$q]['classTicker']."');";
		
			$queryIt=mysqli_query($con,$query) or die(mysqli_error($con));
		}
		$t++;
	}	
	unset($data);
	unset($series);
	unset($class);
}
//$file="edgar/data/2110/0001193125-14-171884.txt";

//$val= array_search($file, $filedNamedE);


?>