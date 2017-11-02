<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('memory_limit', '-1');


//$file="http://www.sec.gov/Archives/edgar/data/891190/000093247115001044/0000932471-15-001044.txt";
//$file="http://www.sec.gov/Archives/edgar/data/736054/000093247114006749/0000932471-14-006749.txt";
//$file="http://www.sec.gov/Archives/edgar/data/932471/000093247115005679/0000932471-15-005679.txt";
//$file="http://www.sec.gov/Archives/edgar/data/930667/000119312514357189/0001193125-14-357189.txt";
//$file="0001193125-14-424619.txt";
$file="0001193125-15-204088.txt";
$html = file_get_contents($file);

	$t='&nbsp;'."Thomas Hingel";
$string = html_entity_decode($t);

$string =  preg_replace("/\&nbsp;/",'',$t);
	echo $string;
	$lines=explode("\n",$html);
	$i=0;
	$fNR=0;
	$tableB=0;
	$tableE=0;
	$rowB=0;
	$shares=1;
	$compName=3;
	$value=6;
	$colNr=0;
	$fundName="";
	$curFundName="";
	$rowNr=0;
	//print_R($lines);
	foreach($lines as $line)
	{
		//echo $line;
		//Find Funds
		if(strpos($line,'<P STYLE="margin-top:0pt; margin-bottom:0pt; font-size:10pt; font-family:Times New Roman"><B>')!==false && $lines[$i-1]!="")
		{
					$curFundName=html_entity_decode(trim(strip_tags($lines[$i])));		
		}

		//Find Common Stocks
		if(strpos($line,"Common Stocks (")!==false && strpos($line,"Total Common Stocks")===false)
		{
			$tableB=1;
			$rowB=1;
			$dataF['fundName'][]=$curFundName;		
		
			$fNR++;
		}

		//Find Common Stocks END
		if(strpos($line,"Total Common Stocks")!==false)
		{
			$tableB=0;
			$rowNr=0;
		}


		if($tableB==1)
		{
			
			if(strpos($line,"<TR")!==false)
			{
				$rowB=1;
				$rowNr++;
			}
		
			if($rowB==1)
			{
				if(strpos($line,"<TD")!==false)
				{		
					if(strlen(trim(preg_replace("/\&nbsp;/",'',strip_tags($line))))>0  && trim(preg_replace("/\&nbsp;/",'',strip_tags($line)))!=="$")
					{
						$data[$fNR][$rowNr][$colNr]=trim(preg_replace("/\&nbsp;/",'',strip_tags($line)));
						$colNr++;						
					}								
				}
			}
			if(strpos($line,"</TR")!==false)
			{
				$rowB=0;
				$colNr=0;
			}	
		}
		$i++;
	}


	//Reorder Data
	$i=0;
	$j=0;
	$k=0;
	foreach($data as $fundDt)
	{
		$j=0;
		foreach($fundDt as $row)
		{
			$k=0;
			foreach($row as $col)
			{
				$dt[$i][$j][$k]=$col;
				$k++;
			}
			$j++;
		}
		$i++;
	}

	//Keep only holdings
	for($i=0;$i<count($dt);$i++)
	{
		$counted=count($dt[$i]);
		for($j=0;$j<$counted;$j++)
		{
			if(count($dt[$i][$j])!=3)
			{
				unset($dt[$i][$j]);
			}
		}
		$dt[$i]=array_values($dt[$i]);
	}
	print_r($dt);
?>
