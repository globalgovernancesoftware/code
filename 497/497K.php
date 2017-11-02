<?php

$file="0000930413-15-002257.txt";
$link=substr($file,0,20);
$lines = file($file);

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
	        $data['cik']=substr($line,($cikLen-$lineLen));
	        break;

	    case strpos($line, $seriesId):
	        $i++;	    
	        $series[$i]['seriesId']=substr($line,($seriesIdLen-$lineLen));
	        $j=0;
	        break;

	    case strpos($line, $serName):
	        $series[$i]['seriesName']=substr($line,($serNameLen-$lineLen));
	        break;		        

	    case strpos($line, $classId):
	    	$j++;
	        $class[$i][$j]['classId']=substr($line,($classIdLen-$lineLen));
	        break;

	    case strpos($line, $className):
	        $class[$i][$j]['className']=substr($line,($classNameLen-$lineLen));
	        break;

	    case strpos($line, $classTicker):
	        $class[$i][$j]['classTicker']=substr($line,($classTickerLen-$lineLen));
	        break;	
	    case strpos($line, "</SERIES-AND-CLASSES-CONTRACTS-DATA>"):
	        
	        break 2;	

	    default:
	        break;
	}
}


?>