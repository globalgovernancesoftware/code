<?php


ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
include 'simple_html_dom.php';
include 'parseComp2.php';
include "extractTable.php";


	$docs="htmlSample/htmlSample1.html";
	$tables=extractTables($docs);

	foreach($tables as $table)
	{
		$cell=parseTable($table);
	}
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


		//check if problem
		if($problem=="")
		{
			$problem="Good";
		}
		echo $problem;

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
		}
?>