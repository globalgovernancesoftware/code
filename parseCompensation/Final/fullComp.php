<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
include 'simple_html_dom.php';
include 'parseComp2.php';
include "extractTable.php";
include 'mysqliConnect.php';
			//Mysql
		//$conn =mysql_connect("novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com","novaUser","Tyrose1214","ids") or die(mysql_error());
		if (mysqli_connect_errno($con))
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		$selectDb=mysqli_select_db($con,'ggsCompData');


$var3=0;

$query='select cikE, dateFiledE, if(left(right(dateFiledE,4),2)="01" or left(right(dateFiledE,4),2)="02" or left(right(dateFiledE,4),2)="03","QTR1",
if(left(right(dateFiledE,4),2)="04" or left(right(dateFiledE,4),2)="05" or left(right(dateFiledE,4),2)="06","QTR2",
if(left(right(dateFiledE,4),2)="07" or left(right(dateFiledE,4),2)="08" or left(right(dateFiledE,4),2)="09","QTR3",
"QTR4")))  as quarter, left(dateFiledE,4) as year, concat(left(right(filedNamedE,24),20),".nc") as fileName from edgarDb.edgarTb where formTypeE like "%DEF 14A%" and dateFiledE>20150101

order by dateFiledE, formTypeE;';

$queryIt=mysqli_query($con,$query);


while($row=mysqli_Fetch_array($queryIt))
{
	$data[]=$row;
	$pathFile[]="Z:\\edgar\\".$row['year']."\\".$row['quarter']."\\".$row['dateFiledE']."\\".$row['fileName'];
	$docNameConcat[]=$row['cikE']."-".$row['fileName'];
}

//$dir="/xampp/htdocs/phpFunctions/CompData/Def14A1y/";

$countDoc=0;
foreach ($pathFile as $filenames) 
{
	//$fileee=substr($filename, strlen($dir));
	//$docs="Def14A1y/".substr($filename, strlen($dir));
	$tables=extractTables($filenames);
	//echo $fileee;

	//If no Tables	
	if(count($tables)==0)
	{	
	$query1="insert into docComp (docNameC, problemC) values ('".$docNameConcat[$var3]."', 'No tables');";
	$runIt1=mysqli_query($con,$query1);	
	}
	foreach($tables as $table)
	{

		$cell=parseTable($table);
		if(count($cell)>1)
		{

		//$good: 0=> wrong, 1=>good, 2=>no totals
		//$problem

		//Check headers
			//Check for Total and validity
		$good=0;
		$problem="";
		$possibleHead=array("name","year","salary","bonus","stock","option","incentive","pension","other","total");

		if(trim(end(Array_values($cell[0])))=="")
		{
			$good=0;
			$problem="Last Column Empty";	
		}elseif(strpos(strtolower(end(Array_values($cell[0]))),"total")!==false)
		{
			$good=1;
		}elseif(strpos(strtolower(end(Array_values($cell[0]))),"total")==false)
		{
			$good=2;
		}

			//Check which head is there
		$i=0;
		$nameCol="";
		$yearCol="";
		$salaryCol="";
		$bonusCol="";
		$stockCol="";
		$optionCol="";
		$incentiveCol="";
		$pensionCol="";
		$otherCol="";
		$totalCol="";
		$isThere=array();

		foreach($possibleHead as $head)
		{	
			$j=0;
			foreach($cell[0] as $cells)
			{
				if(strpos(strtolower($cells),$head)!==false)
				{
					$isThere[]=$i;
					if($head=="name"){$nameCol=$j;}
					if($head=="year"){$yearCol=$j;}
					if($head=="salary"){$salaryCol=$j;}
					if($head=="bonus"){$bonusCol=$j;}
					if($head=="stock"){$stockCol=$j;}
					if($head=="option"){$optionCol=$j;}
					if($head=="incentive"){$incentiveCol=$j;}
					if($head=="pension"){$pensionCol=$j;}
					if($head=="other"){$otherCol=$j;}							
					if($head=="total"){$totalCol=$j;}		
				}
				if(strpos(strtolower($cells),"fiscal")!==false)
				{
					$yearCol=$j;
				}				
				$j++;
			}	
			$i++;
		}
		if(!isset($nameCol))
		{
			continue;
		}
		if(!isset($yearCol))
		{
			continue;
		}
		$avHead=array($nameCol, $yearCol, $salaryCol, $bonusCol, $stockCol, $optionCol, $incentiveCol, $pensionCol, $otherCol, $totalCol);
		$matchingHeader=$j;

		//Check Years
			//Check Validity of Year
		for($i=1;$i<count($cell);$i++)
		{	
			if(!isset($cell[$i][$yearCol]))
			{
				$good=0;
				$problem="Undefined Index";				
			}
			elseif(strpos(trim($cell[$i][$yearCol]),"20")!==false || strpos(trim($cell[$i][$yearCol]),"19")!==false )
			{

			}	else
			{
				$good=0;
				$problem="Year Column doesnt have only years";		
			}
		}

			//Check Years && Name
				//Find exec to be excluded

		if($good>0)
		{
			$tbExcluded=array();
			for($i=0;$i<count($cell);$i++)
			{
				if($i<count($cell)-1)
				{	
					if($cell[$i][$nameCol]==$cell[$i+1][$nameCol] && trim($cell[$i][$yearCol])<=trim($cell[$i+1][$yearCol]))
					{
					$tbExcluded[]=$cell[$i][$nameCol];
					}
				}
			}

					//Find rows of exec
			$rowTbEx=array();
			for($i=0;$i<count($cell);$i++)
			{
				foreach($tbExcluded as $exec)
				{	
					if(strpos($cell[$i][$nameCol],$exec)!==false)
					{
						$rowTbEx[]=$i;
					}
				}
			}


			//Check Totals
			$tbRemovedTot=array();
			$valTotal=array();	
			$toBePushedAsTot=array();	
			if($good==1)
			{
				//Find Empty Totals

				for($i=1;$i<count($cell);$i++)
				{	
				$val=0;	
					for($j=$yearCol+1;$j<$totalCol;$j++)
					{
						if(is_numeric(trim(str_replace(",", "", str_replace(" ","",$cell[$i][$j])))))
						{
								$val+=trim(str_replace(",", "", str_replace(" ","",$cell[$i][$j])));
						}
					}
					if(isset($cell[$i][$totalCol]))
					{
						if(is_numeric(trim(str_replace(",", "", str_replace(" ","",$cell[$i][$totalCol])))))
						{

						}
						elseif($val!=trim(str_replace(",", "", str_replace(" ","",$cell[$i][$totalCol]))))
						{
							$tbRemovedTot[]=$i;
							$valTotal[]=$val;
						}
					}else
					{
						$good=0;
					}
				}
				//Check If Previous Total work
					//Find last Number
				$i=0;

				foreach($tbRemovedTot as $tbCh)
				{
					for($j=$yearCol+1;$j<$totalCol;$j++)
					{
						if(trim(str_replace(",", "", str_replace(" ","",$cell[$tbCh][$j])))==($valTotal[$i]/2))
						{
							$toBePushedAsTot[]=array($tbCh,$j);
						}
					}
					$i++;	
				}
					//Check that everything was pushed or good = 0

				if($i !=count($toBePushedAsTot))
				{
					$good=0;
					$problem="All Totals dont match when missing Cells";

				}
			}
					//Check if no missing cells when no Total
			elseif($good==2)
			{
				for($i=1;$i<count($cell);$i++)
				{	
					for($j=$yearCol+1;$j<count($cell[$i]);$j++)
					{
						if(trim($cell[$i][$j])=="")
						{
							$good=0;
							$problem="Missing Cells when no Totals";
						}
					}
				}

			}

			//CLEAN IT BEFORE PUSH IT
				//Clean Format
			for($i=1;$i<count($cell);$i++)
			{
				for($j=$yearCol+1;$j<$totalCol+1;$j++)
				{	
					if(isset($cell[$i][$j]))
					{
						if(is_numeric(trim(str_replace(",", "", str_replace(" ","",$cell[$i][$j])))))
						{
							$cell[$i][$j]=trim(str_replace(",", "", str_replace(" ","",$cell[$i][$j])));
						}else
						{
							$cell[$i][$j]=0;
						}
					}else
					{
						$good=0;
					}
				}
			}

				//Keep only totals if needed
			foreach($toBePushedAsTot as $tbpat)
			{
				$total=$cell[$tbpat[0]][$tbpat[1]];
					for($j=$yearCol+1;$j<$totalCol;$j++)
					{	

						$cell[$tbpat[0]][$j]=0;
						$cell[$tbpat[0]][$totalCol]=$total;
					}
			}

				//Remove Rows
			foreach($rowTbEx as $rows)
			{
				unset($cell[$rows]);
			}


		}

		if($problem=="")
		{
			$problem="Good";
		}



				//docComp input
		$query1="insert into docComp (docNameC, problemC) values ('".$docNameConcat[$var3]."', '".$problem."');";
		$runIt1=mysqli_query($con,$query1);


		if($good>0)
		{

			$cols="";
				//mainComp input
					//cols available

			foreach($isThere as $available)
			{
				$cols.=", ".$possibleHead[$available]."C";
			}

					//values available
			$cols=substr($cols, 2);

			$z=0;
			$values="";
			$values2="";

			for($i=1;$i<count($cell);$i++)
			{	$values="";
				foreach($avHead as $val)
				{
					if($val!="" )
					{
						$values.=", ".$cell[$i][$val];
						$z++;	
					}elseif(strlen($val)>0)
					{
						$values="'".$cell[$i][$val]."'";
					}
				}
				$values2.="(".$values.", '".$docNameConcat[$var3]."'), ";
			}

			$values2=substr($values2,0,-2);

			$query2="insert into mainComp (".$cols.", sourceC) values ".$values2.";" or die(mysql_error());

			$runIt2=mysqli_query($con,$query2);


			//TABLE
			/*
			echo "<table>";
			foreach($cell as $cells)
			{	
					echo "<tr>";
					for($j=0;$j<count($cells);$j++)
					{
							echo "<td>".$cells[$j]."</td>";
					}
					echo "</tr>";
			}
			echo "</table>";
			*/			

		}

		unset($cell);

		}
	}	
	$var3++;
			echo $var3 . "\n";
}

?>