<?php
//require('/xampp/htdocs/phpFunctions/NPX/Html2Text.php');

$path='/xampp/htdocs/phpFunctions/NPX/npxSample/';
$file='npx811-21922_17.txt';
/*
$handle=fopen($path."npx811-21922_17.txt","r");

if ($handle) {
    while (($line = fgets($handle)) !== false) {
	echo $line."<br>";
    }
} else {
    // error opening the file.
} 
fclose($handle);
*/


$npxC=file_get_contents($path.$file);
$row=explode("\n",$npxC);
$isVote="";
$k=0;
$start="";
$fundId=0;
$fundIds=0;
$compId=0;
$resNr=0;

//Delimiter
$headerDelim="#     Proposal                                Mgt Rec   Vote Cast    Sponsor";
$resIdPos=strpos($headerDelim," Proposal");
$proposalIdPos=strpos($headerDelim," Mgt Rec");
$mgmtRecIdPos=strpos($headerDelim," Vote Cast");
$voteCastIdPos=strpos($headerDelim," Sponsor");



for($i=0;$i<count($row);$i++)
{
	//Check for new fund and if Votes
	if(strpos($row[$i],"====")!==false)
	{
	$fundId=$fundIds;	
		$checkFund=trim(str_Replace("=","",trim($row[$i])));


		//Check 2nd line and 3rd line to confirm their is votes
		if(strlen(trim($row[$i+1]))==0 && strlen(trim($row[$i+2]))==0)
		{
			$isVote=1;
		}
		else
		{
			$isVote=2;
		}

		if(strlen($checkFund)>0 && substr($checkFund,0,7)!="END NPX" && $isVote==1)
		{
			$fundName[]=$checkFund;
			$compId=0;
			$fundIds++;	
		}	

	}

	//Check if 2nd line empty and 3rd line empty

	if($isVote=="1")
	{
		//Check for header
		if($row[$i]=="#     Proposal                                Mgt Rec   Vote Cast    Sponsor")
		{
			$start=1;

		}elseif(strlen(trim($row[$i]))==0)
		{
			$start=0;
			
		}		

		//Push resId, res, Votes, Mgmt Rec and Sponsor
		if($start==1)
		{
		
			//Check if New Resolution
			if(trim(substr($row[$i], 0,1))!="" && trim(substr($row[$i], 0,1))!="#")
			{

				$resId[$fundId][$compId-1][$resNr]=substr($row[$i],0,$resIdPos);				
				$proposal[$fundId][$compId-1][$resNr]=substr($row[$i],$resIdPos+1,$proposalIdPos-$resIdPos-1);
				$mgmtRec[$fundId][$compId-1][$resNr]=substr($row[$i],$proposalIdPos+1,$mgmtRecIdPos-$proposalIdPos-1);
				$voteCast[$fundId][$compId-1][$resNr]=substr($row[$i],$mgmtRecIdPos+1,$voteCastIdPos-$mgmtRecIdPos-1);		
				$sponsor[$fundId][$compId-1][$resNr]=substr($row[$i],$voteCastIdPos+1);	
				$resNr++;
			}
			//Push to previous Resolution
			elseif(trim(substr($row[$i], 0,1))=="" && trim(substr($row[$i], 0,1))!="#" && trim($row[$i]))
			{
				if(strpos($row[$i]," Card)")!==false)
				{
				}
				else
				{
					$proposal[$fundId][$compId-1][$resNr-1]=trim(trim($proposal[$fundId][$compId-1][$resNr-1])." ".trim(substr($row[$i],$resIdPos+1,$proposalIdPos-$resIdPos-1)));
						
				}
			}

		}

 
		if($start==0)
		{
			$resNr=0;
			if(strlen(trim($row[$i]))>0)
			{
				
				
				//Get Company Name
				if(strpos($row[$i-2],"----")!==false || strpos($row[$i-3],"====")!==false)
				{
					$compName[$fundId][]=$row[$i];
					$compId++;

				}

				//Get Ticker and Security ID
				if(strpos($row[$i],"Ticker:")!==false && strpos($row[$i],"Security ID:")!==false)
				{
					$rowTick=explode("Security ID:",$row[$i]);
					$tickerName[$fundId][]=trim(str_replace("Ticker:","",$rowTick[0]));
					$secId[$fundId][]=trim($rowTick[1]);	
					unset($rowTick);	
							
				}


				//Get Meeting Date and Meeting Type
				if(strpos($row[$i],"Meeting Date:")!==false && strpos($row[$i],"Meeting Type:")!==false)
				{
					$rowMeet=explode("Meeting Type:",$row[$i]);
					$meetDate[$fundId][]=trim(str_replace("Meeting Date:","",$rowMeet[0]));
					$meetType[$fundId][]=trim($rowMeet[1]);	
					unset($rowMeet);
						
				}

				//Get Record Date
				if(strpos($row[$i],"Record Date:")!==false)
				{
					$recDate[$fundId][]=trim(str_replace("Record Date:","",$row[$i]));
						
				}

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
			echo "<td>".$recDate[$i][$j]."</td>";						
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