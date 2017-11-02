<?php

$cell=array();

	//Remove Empties
		//Rows
	function removeEmpties($cellule)
	{
		$cell=$cellule;
		unset($cellule);
		$emptyRows=array();
		for($i=0;$i<count($cell);$i++)
		{
			$countEmpty=0;
			for($j=0;$j<count($cell[$i]);$j++)
			{
					if(isset($cell[$i][$j]))
					{
					if(trim($cell[$i][$j])=="")
					{
						$countEmpty++;
					}
					}
			}
			if($countEmpty==count($cell[$i]))
			{
				$emptyRows[]=$i;
			}
		}


			//Unset Rows
		for($i=0;$i<count($emptyRows);$i++)
		{
			unset($cell[$emptyRows[$i]]);
		}


			//Reformat 0 to k the Rows
		$i=0;
		$cleanCell=array();
		foreach($cell as $cells)
		{
		$cleanCell[$i]=$cells;
		$i++;
		}

		$cell=$cleanCell;


			//Sort array
		for($i=0;$i<count($cell);$i++)
		{
			ksort($cell[$i]);
		}

			//Identified Filled Cells
		$filled=array();
		$max=0;
		for($i=0;$i<count($cell);$i++)
		{
			for($j=0;$j<count($cell[$i]);$j++)
			{	
				if(isset($cell[$i][$j]))
				{
				if(strlen(trim($cell[$i][$j]))>0)
				{
					$filled[]=$j;
				}
				if($j>$max)
				{
					$max=$j+1;
				}
				}
			}
		}
		$filled=array_unique($filled);


			//Unset Columns

		for($i=0;$i<count($cell);$i++)
		{
			for($j=0;$j<$max+10;$j++)
			{
				if(!in_array($j, $filled))
				{
					unset($cell[$i][$j]);
				}
			}
		}
		return $cell;
	};




function parseTable($doc){
$html = new simple_html_dom();

$html->load("<html>".$doc."</html>");
$i=0;
$cellStrip=array();
$element=array();
$newElement=array();
//EXTRACT CELLS TABLE (Keep BRs and Ps)
foreach($html->find('tr') as $trs)
{
	$row[]=$trs;
	$j=0;
	foreach($trs->find('th') as $tds)
	{
		$cell[$i][]=$tds; //
		$cellStrip[$i][$j]=strip_tags($cell[$i][$j],"<br><p><div><sup>");
		$j++;
	}
	$j=0;	
	foreach($trs->find('td') as $tds)
	{
		$cell[$i][]=$tds; //
		$cellStrip[$i][$j]=strip_tags($cell[$i][$j],"<br><p><div><sup>");
		$j++;
	}
$i++;
}

	//ReNumber Array
$i=0;
foreach($cellStrip as $cells)
{
	$j=0;	
	foreach($cells as $cellss)
	{

		$cellStriped[$i][$j]=	$cellss;

		$j++;
	}
	$i++;
}
unset($cellStrip);
$cellStrip=array();
if(isset($cellStriped))
{

$cellStrip=$cellStriped;
unset($cellStriped);
}

//DEALING WITH BRs and Ps
$element=array();
	//Explode for BR & P
for($i=0;$i<count($cellStrip);$i++)
{
	for($j=0;$j<count($cellStrip[$i]);$j++)
	{	

		if(strpos(strtolower($cellStrip[$i][$j]),"annual compensation")!==false)
		{
			unset($cellStrip[$i]);
			break;
		}else
		{
		$cellStrip[$i][$j]=preg_replace("/<p[^>]*?>/", "", $cellStrip[$i][$j]);
		$cellStrip[$i][$j]=preg_replace("/<div[^>]*?>/", "", $cellStrip[$i][$j]);
		$cellStrip[$i][$j]=preg_replace("/(<sup[^>]*>)(.*?)(<\/sup>)/i", "", $cellStrip[$i][$j]);				
		$cellStrip[$i][$j] = str_Replace("</p>","<br>",$cellStrip[$i][$j]);	
		$cellStrip[$i][$j] = str_Replace("</div>","<br>",$cellStrip[$i][$j]);				
		$element[$i][$j] = explode("<br>", $cellStrip[$i][$j]);
		}
	}
}



	//Max BRs to Push Exploded Cell in newElement
for($i=0;$i<count($element);$i++)
{
	$tho=array();
	for($j=0;$j<count($element[$i]);$j++)
	{
			$tho[]=count($element[$i][$j]);
	}
	$mas[]=max($tho);
}




	//Push Exploded Cells in newElement
$k=0;
$i=0;
$t=0;
foreach($element as $elementi)
{
	$j=0;
	foreach($elementi as $elementj)
	{
		$k=0;
		foreach($elementj as $elementl)
		{
			$newElement[$i+$k][$j]=$elementl;
		$k++;
		}
	$j++;
	}
	$q=$mas[$t];
	$t++;
	$i=$i+$q;
}
$cell=array();

$cell=$newElement;


//REFILLING EMPTIES

	//DEFINE MAX TDs to refill Empty Cells
$t=array();
for($i=0;$i<count($cell);$i++)
{
	$t[]=count($cell[$i]);
}
if(Count($t)>0)
{
$maxCol=max($t);
}

	//REFILL MISSING TDs
for($i=0;$i<count($cell);$i++)
{
	for($j=0;$j<$maxCol;$j++)
	{
		if(!isset($cell[$i][$j]))
		{
			$cell[$i][$j]="";
		}	
	}
}


//REFILL FIRST EMPTY CELL IF EQUAL 20*
for($i=0;$i<count($cell);$i++)
{
	if(strpos($cell[$i][0],"20")!==false)
	{	
		$cell[$i][count($cell[$i])]="";
		$maxiR=count($cell[$i])-1;	
		for($j=$maxiR;$j>0;$j--)
		{
			$cell[$i][$j]=$cell[$i][$j-1];
		}
		$cell[$i][0]="";
	}else
	{
		$cell[$i][count($cell[$i])]="";
	}
}

//TRIM CELLS

for($i=0;$i<count($cell);$i++)
{
	for($j=0;$j<count($cell[$i]);$j++)
	{
		$cell[$i][$j]=trim($cell[$i][$j]);
	}

}

//FIND First ROW 20*
$x=0;
for($i=0;$i<count($cell);$i++)
{
	for($j=0;$j<count($cell[$i]);$j++)
	{
		if(strpos($cell[$i][$j],"20")!==false)
		{
			$firstYR=$i;

			$x=1;
			$maxYear=$cell[$i][$j];
		}
		if($x==1)
			{break;}
	}
	if($x==1)
		{break;}
}

if(isset($firstYR))
{
	//REMOVE &nbsp; AND (.) AND (..) AND ( AND ) AND $
	for($i=0;$i<count($cell);$i++)
	{
		for($j=0;$j<count($cell[$i]);$j++)
		{
			$cell[$i][$j]=str_Replace("&nbsp;"," ",$cell[$i][$j]);
			$cell[$i][$j]=str_Replace("&#160;"," ",$cell[$i][$j]);			
			$cell[$i][$j]=trim($cell[$i][$j]);
			$cell[$i][$j]=preg_replace("/(\(.\))/","",$cell[$i][$j]);
			$cell[$i][$j]=preg_replace("/(\(..\))/","",$cell[$i][$j]);	
			if(strlen(trim($cell[$i][$j]))<4)
			{
			$cell[$i][$j]=preg_replace("/(\(..)/","",$cell[$i][$j]);
			$cell[$i][$j]=preg_replace("/(\(.)/","",$cell[$i][$j]);	
			}								
			$cell[$i][$j]=preg_replace("/[.\)]/","",$cell[$i][$j]);
			if(strlen(trim($cell[$i][$j]))>=4)
			{
			$cell[$i][$j]=preg_replace("/[\(.]/","-",$cell[$i][$j]);		
			}else
			{
			$cell[$i][$j]=preg_replace("/[\(.]/","",$cell[$i][$j]);					
			}
			$cell[$i][$j]=str_replace("$","",$cell[$i][$j]);
		}
	}


	//DEALING WITH HEADERS

		//Regroup Header
	for($i=1;$i<$firstYR;$i++)
	{
		
		for($j=0;$j<count($cell[$i]);$j++)
		{
			$cell[0][$j].=" ".$cell[$i][$j];
			$cell[$i][$j]="";
		}
	}


		//Count non-Empty RowHeader
	$tHead=0;
	for($i=0;$i<$firstYR;$i++)
	{
		for($j=0;$j<count($cell[$i]);$j++)
		{
			if($cell[$i][$j]!="")
			{
			$tHead++;
			}
		}
	}



	//DEALING WITH NAMES

		//Find Name Columns
	$x=0;
	for($i=$firstYR;$i<count($cell);$i++)
	{
		for($j=0;$j<count($cell[$i]);$j++)
		{
			if(strlen($cell[$i][$j])>5)
			{
				$execC=$j;
				$x=1;
			}
			if($x==1)
			{break;}		
		}
		if($x==1)
		{break;}	
	}

		//Reorder Array per Key
	for($i=0;$i<count($cell);$i++)
	{
	ksort($cell[$i]);
	}
	
		//Remove Empty Columns after Exec

	for($i=$firstYR;$i<count($cell);$i++)
	{	
		$j=0;
		foreach($cell[$i] as $cells)
		{
			if(strlen($cells)==0 && $j>$execC)
			{
				unset($cell[$i][$j]);

			}
			$j++;
		}
	}


		//Fix Data column & Fill the others

	$countRestCol=count($cell[$firstYR]);
	for($i=$firstYR;$i<count($cell);$i++)
	{	$j=0;
		foreach($cell[$i] as $cells)
		{
			if($j>$execC  && $j<$countRestCol)
			{
				$cell[$i][$j]=$cells;

			}else
			{

			}
		$j++;
		}
		for($k=$j;$k<$maxCol;$k++)
		{
			$cell[$i][$k]="";
		}
	}


	//FIND First ROW 20*
	$x=0;
	for($i=0;$i<count($cell);$i++)
	{
		for($j=0;$j<count($cell[$i]);$j++)
		{
			if(strpos($cell[$i][$j],"20")!==false)
			{
				$firstYC=$j;

				$x=1;

			}
			if($x==1)
				{break;}
		}
		if($x==1)
			{break;}
	}

		//Regroup Name & Title
	$find=0;
	for($i=$firstYR;$i<count($cell);$i++)
	{
		if(trim($cell[$i][$firstYC])==Trim($maxYear))
		{
			$exec[]=$i;
			$find=$i;
		}else
		{
			$cell[$find][$execC].=" ".$cell[$i][$execC];
			$cell[$i][$execC]="";
		}
	}




	//Remove Empty headers
	$k=0;
	for($j=0;$j<count($cell[0]);$j++)
	{

		if(strlen(trim($cell[0][$j]))>0)
		{
			$cell[0][$k]=$cell[0][$j];
			$k++;
		}

	}
	for($j=$k;$j<count($cell[0]);$j++)
	{
			$cell[0][$j]="";
	}



	
	//Run Function removeEmpties
	$cell=removeEmpties($cell);

	//Exception push if all cells under header are 'decale'
	$deca=array();
	$decale=array();
	if(Trim($cell[1][1])=="")
		{
		for($j=0;$j<count($cell[1]);$j++)
		{
			if(trim($cell[1][$j])=="")
			{
				$deca[]=$j;
			}
		}

		foreach($deca as $decal)
		{
			$dec=0;
			for($i=1;$i<Count($cell);$i++)
			{
				if(trim($cell[$i][$decal])=="")
				{
					$dec++;
				}			
			}
			$decale[]=$dec;
		}

		$t=0;
		foreach($decale as $decal)
		{
			if($decal==count($cell)-1)
			{
				for($i=1;$i<count($cell);$i++)
				{
					for($j=0;$j<count($cell[$i])-1;$j++)
					{
						$cell[$i][$j]=$cell[$i][$j+1];
					}
					$cell[$i][count($cell[0])-1-$t]="";
				}
			$t++;
			}
		}
	}


	//exception 2: Add Year header if not present
		$t=1;
	for($j=0;$j<Count($cell[0]);$j++)
	{
		if(strpos(strtolower($cell[0][$j]), "year")!==false || strpos(strtolower($cell[0][$j]), "fiscal")!==false)
		{
			$t=0;
		}
	}

	if($t==1)
	{

		for($j=count($cell[0])-1;$j>1;$j--)
		{
			$cell[0][$j]=$cell[0][$j-1];
		}
		$cell[0][1]="Year";
	}
	//Fill Names
	for($i=0;$i<count($cell);$i++)
	{
		if(trim($cell[$i][0])=="")
		{
			$cell[$i][0]=$cell[$i-1][0];
		}
	}

	
	//Run Function removeEmpties
	$cell=removeEmpties($cell);


}
unset($html);
unset($element);
unset($newElement);
unset($cellStrip);
unset($filled);
unset($emptyRows);
unset($exec);
return $cell;
};

?>