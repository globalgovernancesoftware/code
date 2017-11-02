<?php 
ini_set('memory_limit', '-1');
header('Content-Type: text/html; charset=UTF-8');

$path='/xampp/htdocs/phpFunctions/NPX/npxSample/';
$file='BRD_0150000917469.txt';
$npxC=file_get_Contents($path.$file);
$npx=strip_tags($npxC);
$row=explode("\n",$npx);
$start=333;
$fundIds=0;
$dirList=0;

//Delimiter
$headerDelim="Prop.# Proposal                                                  Proposal      Proposal Vote                  For/Against";
$headerDelim2="                                                                 Type                                         Management";
$resIdPos=strlen("Prop.# ");
$resolutionIdPos=strlen("Prop.# Proposal                                                  ");
$sponsorIdPos=strlen("Prop.# Proposal                                                  Proposal      ");
$voteCastIdPos=strlen("Prop.# Proposal                                                  Proposal      Proposal Vote                  ");


for($i=0;$i<count($row);$i++)
{
	//Check Funds Name
	if(isset($row[$i-2]) && isset($row[$i-3]))
	{	
		if(strpos($row[$i-2],"----------")!==false && strpos($row[$i],"----------")!==false && strlen(trim($row[$i-3]))>0)
		{
			$fundId=$fundIds;			
			$checkFund=trim($row[$i-3]);			
			$fundName[]=$row[$i-3];
			$compId=0;
			$fundIds++;				
		}
	}

	//Check
	if(isset($row[$i+2]) && isset($row[$i+1]))
	{		
		if($row[$i]==$headerDelim)
		{
			$start=1;
		}
		elseif(strlen(trim($row[$i]).trim($row[$i+1]).trim($row[$i+2]))==0)
		{
			$start=0;		
		}
	}		

	if($start==0)
	{
			$resNr=0;		
		if(strpos($row[$i-2],"----------")!==false && strpos($row[$i],"----------")!==false)
		{
			$rowCompName=explode(" Agenda Number:",$row[$i-1]);
			$compName[$fundId][]=trim($rowCompName[0]);
			$compId++;
			unset($rowCompName);

			$secId[$fundId][]=trim(str_replace("Security:","",$row[$i+1]));
			$meetType[$fundId][]=trim(str_replace("Meeting Type:","",$row[$i+2]));
			$meetDate[$fundId][]=trim(str_replace("Meeting Date:","",$row[$i+3]));
			$tickerName[$fundId][]=trim(str_replace("Ticker:","",$row[$i+4]));
				
		}
		//if(strpos($row[$i-3],"-------------")!==false && strpos($row[$i-9],"----------")!==false ){echo $row[$i-4];}
	}

	//Push resId, res, Votes, Mgmt Rec and Sponsor
	if($start==1)
	{
		//Check if Director is listed or in group
		if(strpos($row[$i],".     DIRECTOR")!==false)
		{
			$dirList=1;
			$rId=substr($row[$i],0,strlen($row[$i])-strlen(".     DIRECTOR"));
			$cdLet=97;
		}elseif(trim($row[$i])=="")
		{
			$dirList=0;
			$cdLet=0;
		}

		//Check if New Resolution
		if($dirList==1)
		{
			if(strpos($row[$i],".     DIRECTOR")!==false)
			{
			}
			else
			{
				$resId[$fundId][$compId-1][$resNr]=$rId.strtoupper(chr("0".$cdLet)).".";		
				$proposal[$fundId][$compId-1][$resNr]="ELECTION OF DIRECTOR: ".trim(substr($row[$i],$resIdPos,$resolutionIdPos-$resIdPos));
				$sponsor[$fundId][$compId-1][$resNr]=trim(substr($row[$i],$resolutionIdPos,$sponsorIdPos-$resolutionIdPos));
				$voteCast[$fundId][$compId-1][$resNr]=substr($row[$i],$sponsorIdPos,$voteCastIdPos-$sponsorIdPos);		
				$mgmtRec[$fundId][$compId-1][$resNr]=substr($row[$i],$voteCastIdPos);	
				$cdLet++;
				$resNr++;							
			}
		}
		elseif($row[$i]!=$headerDelim && trim($row[$i])!="" && $row[$i]!=$headerDelim2 && trim(substr($row[$i], 0,1))!="")
		{

			$resId[$fundId][$compId-1][$resNr]=trim(substr($row[$i],0,$resIdPos));				
			$proposal[$fundId][$compId-1][$resNr]=trim(substr($row[$i],$resIdPos,$resolutionIdPos-$resIdPos));
			$sponsor[$fundId][$compId-1][$resNr]=trim(substr($row[$i],$resolutionIdPos,$sponsorIdPos-$resolutionIdPos));
			$voteCast[$fundId][$compId-1][$resNr]=substr($row[$i],$sponsorIdPos,$voteCastIdPos-$sponsorIdPos);		
			$mgmtRec[$fundId][$compId-1][$resNr]=substr($row[$i],$voteCastIdPos);

			$resNr++;
		}
		//Push to previous Resolution
		elseif(trim(substr($row[$i], 0,1))==""  && $row[$i]!=$headerDelim2 && trim($row[$i]))
		{
			if(strpos($row[$i]," Card)")!==false)
			{
			}
			else
			{
				$proposal[$fundId][$compId-1][$resNr-1]=trim(trim($proposal[$fundId][$compId-1][$resNr-1])." ".trim(substr($row[$i],$resIdPos,$resolutionIdPos-$resIdPos)));
					
			}			
		}

	}
}
echo "<table>";
$i=0;
foreach($resId as $res)
{
	$j=0;
	foreach($res as $re)
	{	
		$k=0;

		foreach($re as $r)
		{
			echo "<tr>";
			echo "<td>".$fundName[$i]."</td>";
			echo "<td>".$compName[$i][$j]."</td>";
			echo "<td>".$tickerName[$i][$j]."</td>";
			echo "<td>".$meetDate[$i][$j]."</td>";
			echo "<td>".$meetType[$i][$j]."</td>";
								
			echo "<td>".$compName[$i][$j]."</td>";																	
			echo "<td>".$r."</td>";
			echo "<td>".$proposal[$i][$j][$k]."</td>";
			echo "<td>".$mgmtRec[$i][$j][$k]."</td>";
			echo "<td>".$voteCast[$i][$j][$k]."</td>";
			echo "<td>".$sponsor[$i][$j][$k]."</td>";										
			echo "</tr>";
			$k++;
		}

		$j++;
	}
	$i++;
}
echo "</table>";

?>