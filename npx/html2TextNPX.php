<?php 
ini_set('memory_limit', '-1');
header('Content-Type: text/html; charset=UTF-8');

$path='/xampp/htdocs/phpFunctions/NPX/npxSample/';
$file='dfaidgnpx2014.htm';
$npxC=file_get_Contents($path.$file);
$i=0;
$docN=strip_tags(html_entity_decode($npxC));
$row=explode("\n",$docN);
$isVote="";
$k=0;
$start="";
$fundId=0;
$fundIds=0;
$compId=0;
$resNr=0;


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
//echo "<table>";
$i=0;
$count=0;
$t=0;
$fullArray=array();


$fp = fopen('/xampp/htdocs/phpFunctions/NPX/file.csv', 'w');

foreach($resId as $res)
{
	$j=0;
	foreach($res as $re)
	{	
		$k=0;

		foreach($re as $r)
		{
			/*
			echo "<tr>";
			echo "<td>".$fundName[$i]."</td>";
			echo "<td>".$compName[$i][$j]."</td>";
			echo "<td>".$tickerName[$i][$j]."</td>";
			echo "<td>".$meetDate[$i][$j]."</td>";
			echo "<td>".$meetType[$i][$j]."</td>";
			echo "<td>".$recDate[$i][$j]."</td>";																						
			echo "<td>".$r."</td>";
			echo "<td>".$proposal[$i][$j][$k]."</td>";
			echo "<td>".$mgmtRec[$i][$j][$k]."</td>";
			echo "<td>".$voteCast[$i][$j][$k]."</td>";
			echo "<td>".$sponsor[$i][$j][$k]."</td>";										
			echo "</tr>";*/

			unset($fullArray);
			$fullArray=array($fundName[$i],$compName[$i][$j],$tickerName[$i][$j],$meetDate[$i][$j],$meetType[$i][$j],$recDate[$i][$j],$r,$proposal[$i][$j][$k],
				$mgmtRec[$i][$j][$k],$voteCast[$i][$j][$k],$sponsor[$i][$j][$k],$file);
    		fputcsv($fp, $fullArray);

			$k++;
			if(fmod($count,1000)==0)
			{
				$t++;
			}
			$count++;
		}

		$j++;
	}
	$i++;
}
unset($fundName);
unset($compName);
unset($tickerName);
unset($meetDate);
unset($meetType);
unset($recDate);
unset($resId);
unset($proposal);
unset($mgmtRec);
unset($voteCast);
unset($sponsor);

//Mysql
$conn =mysql_connect("novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com","novaUser","Tyrose1214","ids") or die(mysql_error());
if (mysqli_connect_errno($conn))
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$selectDb=mysql_select_db('npx');
$query=  ' LOAD DATA LOCAL INFILE \''."/xampp/htdocs/phpFunctions/NPX/file.csv".'\' REPLACE INTO TABLE npx1'
. ' FIELDS TERMINATED BY \',\''
. ' ENCLOSED BY \'"\''
. ' ESCAPED BY \'\\\\\''
. ' LINES TERMINATED BY \'\\n\''
. ' (fundName, compName, ticker, meetDate, meetType, recDate, resId, resolution, mgmtRec, vote, sponsor, source)'; 
$runit=mysql_query($query) or die(mysql_error());
echo $query;
//print_R($fullArray);
//echo "</table>";
?>