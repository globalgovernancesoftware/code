<?php
ini_set('memory_limit', '-1');

ini_set('max_execution_time', 0);
$host="novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com"; // Host name 
$username="novaUser"; // Mysql username 
$password="Tyrose1214"; // Mysql password 

$con=mysqli_connect("$host","$username","$password") or die("Could not connect");




$ownerCik='<OWNER-CIK>';
$seriesId='<SERIES-ID>';
$seriesName='<SERIES-NAME>';
$classContractId='<CLASS-CONTRACT-ID>';
$classContractName='<CLASS-CONTRACT-NAME>';
$classContractTickerSymbol='<CLASS-CONTRACT-TICKER-SYMBOL>';
$endSeries='</SERIES-AND-CLASSES-CONTRACTS-DATA>';
$startNSAR='<TYPE>NSAR-';
$docCheck='<DOCUMENT>';

$ownerCikLength=strlen($ownerCik);
$seriesIdLength=strlen($seriesId);
$seriesNameLength=strlen($seriesName);
$classContractIdLength=strlen($classContractId);
$classContractNameLength=strlen($classContractName);
$classContractTickerSymbolLength=strlen($classContractTickerSymbol);
$endSeriesLength=strlen($endSeries);
$docCheckLength=strlen($docCheck);
$startNSARLength=strlen($startNSAR);


//SCAN DIR
$series=array();
$lines=array();
$nsarCore=array();
$dir='D:/Novashare/test/nsar';

$folders=scandir($dir);

foreach($folders as $folder)
{
	if($folder!='.' && $folder!='..')
	{	
	$subfolders=scandir($dir."/".$folder);
		foreach($subfolders as $subfolder)
		{
			if($subfolder!='.' && $subfolder!='..')
			{	
			$files=scandir($dir."/".$folder."/".$subfolder);
			$i=0;
			$j=0;
				foreach($files as $file)
				{
					if(strlen($file)>2)
					{
					$fileName[]=$dir."/".$folder."/".$subfolder."/".$file;
					$files2[]=substr($file,-23,-3).".txt";
					} 
				}
			}
		}
	}
}


//$fileName[0]="D:/Novashare/Test/nsar/1003839/116204414001029/0001162044-14-001029.nc";
//$files2[0]="0001162044-14-001029.nc";
//count($fileName)
for($z=0;$z<count($fileName);$z++)
{
	//PARSE
	//echo $fileName[$z]."\n";
	unset($lines);
	unset($series);
	unset($nsarCore);
	$min=0;
	$max=0;
	ECHO $z."\n";
	$lines = file($fileName[$z], FILE_IGNORE_NEW_LINES);
	
	//$series['cik']=array();
	$i=-1;
	$j=-1;
	$k=0;
	$sta=0;
	$stF=0;

	for($t=0;$t<count($lines);$t++)
	{
		if(substr($lines[$t],0,$ownerCikLength)==$ownerCik)
		{
			$i++;
			$j=-1;
			$series[$i]['cik']=mysqli_real_escape_string($con,substr($lines[$t],$ownerCikLength));
		}
		elseif(substr($lines[$t],0,$seriesIdLength)==$seriesId)
		{
			$series[$i]['seriesId']=mysqli_real_escape_string($con,substr($lines[$t],$seriesIdLength));		
		}
		elseif(substr($lines[$t],0,$seriesNameLength)==$seriesName)
		{
			$series[$i]['seriesName']=mysqli_real_escape_string($con,substr($lines[$t],$seriesNameLength));	
		}
		elseif(substr($lines[$t],0,$classContractIdLength)==$classContractId)
		{
			$j++;
			$series[$i][$j]['classContractId']=mysqli_real_escape_string($con,substr($lines[$t],$classContractIdLength));	
		}
		elseif(substr($lines[$t],0,$classContractNameLength)==$classContractName)
		{
			$series[$i][$j]['classContractName']=mysqli_real_escape_string($con,substr($lines[$t],$classContractNameLength));	
		}
		elseif(substr($lines[$t],0,$classContractTickerSymbolLength)==$classContractTickerSymbol)
		{
			$series[$i][$j]['classContractTickerSymbol']=mysqli_real_escape_string($con,substr($lines[$t],$classContractTickerSymbolLength));	
		}elseif(substr($lines[$t],0,$startNSARLength)==$startNSAR && substr($lines[$t-1],0,$docCheckLength)==$docCheck)
		{
			$sta=1;
		}
		elseif( $sta==1 && (substr($lines[$t],0,6)=="<TEXT>"))
		{
			$stF=$k+1;
		
			break;
		}
		$k++;
		//echo $k;
	}
	$st=0;

	//Check if Fil in good fomat
	if(substr($lines[$stF+5],0,1)==" " && substr($lines[$stF+6],0,1)==" ")
	{
		$pass=1;
	}elseif(substr($lines[$stF+5],0,2)=="  " && substr($lines[$stF+6],0,2)=="  ")
	{
		$pass=2;
	}else
	{
		$pass=0;
	}


	//echo $stF;
	for($l=$stF;$l<count($lines);$l++)
	{
		if(substr($lines[$l],0,6)=='<PAGE>')
		{
			continue;
		}
		elseif($lines[$l]=='</TEXT>')
		{
			break;
		}
		else
		{
			$nsarCore[]=array(mysqli_real_escape_string($con,substr($lines[$l],$pass+0,3)),mysqli_real_escape_string($con,substr($lines[$l],$pass+3,8)),mysqli_real_escape_string($con,substr($lines[$l],$pass+11,200)),$files2[$z]);	
		}
	}



	//MYSQL1

	$multiple=2000;
	$count=count($nsarCore);
	$divide=floor($count/$multiple);
	for($j=0;$j<$divide;$j++)
	{
		$nsarFin="";
		$nsarIm=array();		
		$min=$j*$multiple;
		$max=($j+1)*$multiple;
		$MIValues="";
		for($i=$min;$i<$max;$i++)
		{
			$nsarIm=implode("','",$nsarCore[$i]);
			$nsarIt="('".$nsarIm."'),";
			$nsarFin.=$nsarIt;
		}

		$nsarFin=substr($nsarFin,0,-1);	

		$MISValues="Insert into novaNSAR.masterNSAR Values ".$nsarFin.";";

		$queryIt=mysqli_query($con,$MISValues) or die(mysqli_error($con)."Beg");
		
	}


	$nsarFin="";
	$nsarIm=array();
	$nsarIt='';
	if(!isset($max))
	{
		$max=0;
		echo "small";
	}

	for($i=$max;$i<$count;$i++)
	{
		$nsarIm=implode("','",$nsarCore[$i]);
		$nsarIt="('".$nsarIm."'),";
		$nsarFin.=$nsarIt;
	}
	//echo $nsarFin;
	$nsarFin2=substr($nsarFin,0,-1);	
	//echo $nsarFin;

	$MISValues2="Insert into novaNSAR.masterNSAR Values ".$nsarFin2.";";
	//echo $MISValues2."<BR>";
	$queryIt=mysqli_query($con,$MISValues2) or die(mysqli_error($con)."Fin");



	//MYSQL2
	$i=0;
	$j=0;
	$finValue="";
	for($i=0;$i<count($series);$i++)
	{
		$value1=$series[$i]['cik']."','".$series[$i]['seriesId']."','".$series[$i]['seriesName'];
		if(count($series[$i])==3)
		{
			$value2=$value1."','','','','".$fileName;
			$values="('".$value2."')";
		}
		for($j=0;$j<count($series[$i])-3;$j++)
		{
			if(!isset($series[$i][$j]['classContractTickerSymbol']))
			{
				$series[$i][$j]['classContractTickerSymbol']="";
			}
			$value2=$value1."','".$series[$i][$j]['classContractId']."','".$series[$i][$j]['classContractName']."','".$series[$i][$j]['classContractTickerSymbol']."','".$files2[$z];
			$values="('".$value2."')";
		}
		$finValue.=$values.",";
	}

	$val=substr($finValue,0,-1);
	if(strlen($val)>10)
	{
		$MISValues="Insert into novaNSAR.serNSAR Values ".$val.";";
		$queryIt=mysqli_query($con,$MISValues) or die(mysqli_error($con)."Fin2".$val);
	}


	//echo count($series[0]);
	//print_r($series);
}


?>