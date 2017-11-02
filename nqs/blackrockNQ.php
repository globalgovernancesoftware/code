<?php
ini_set('memory_limit', '-1');
ini_set('default_charset', 'utf-8');
//iconv_set_encoding("internal_encoding", "UTF-8");
//$file="http://www.sec.gov/Archives/edgar/data/891190/000093247115001044/0000932471-15-001044.txt";
//$file="http://www.sec.gov/Archives/edgar/data/736054/000093247114006749/0000932471-14-006749.txt";
//$file="http://www.sec.gov/Archives/edgar/data/932471/000093247115005679/0000932471-15-005679.txt";
//$file="http://www.sec.gov/Archives/edgar/data/930667/000119312514357189/0001193125-14-357189.txt";
//$file="0001193125-14-424619.txt";
$file="0001193125-07-042581.txt";
$html = file($file);

//print_R($html);
 $findIT=0;

 $beginning=0;

 $ending=0;

 $firstHt="";

$pattern='<TD VALIGN="bottom" STYLE="BORDER-BOTTOM:2px solid #000000"><FONT STYLE="font-family:Arial Narrow" SIZE="1"><B>Common Stocks</B></FONT></TD>';

$begin=preg_grep("/HTML\>.*/", $html);
if(count($begin)==0)
{
    $begin=preg_grep("/html\>.*/", $html);   
}

//echo $begin;
//echo $end;
$lim=array_keys($begin);
$firstHt="";
/*
for($i=$lim[0];$i<=$lim[1];$i++)
{
    $firstHt.=$html[$i];
}
*/


$firsty=array_slice($html,$lim[0],$lim[1]-$lim[0]);
echo count($firsty);
$firstHt=html_entity_decode(implode("",$firsty),ENT_QUOTES, "UTF-8");

//$html = str_get_html($firstHt);
unset($html);

$pageDom = new DomDocument(); 
@$pageDom->loadHTML($firstHt); 
//header("Content-Type: text/html; charset=utf-8");
echo "done";

//var_dump($html);

//$table = $html->find('table',1);
$rowData = array();
$start=0;
$end=0;
$remMec=0;
$last=0;
$cur=0;
$tbRem[0]=array();
$tbRem[1]=array();
$i=0;
foreach($pageDom->getElementsByTagName('table') as $table)
{
	//var_dump($table);
	//echo "Table"."\n";
	foreach($table->getElementsByTagName('tr') as $row) 
	{
	    // initialize array to store the cell data from each row
	   
	    $flight = array();
	    $j=0;
	    foreach($row->getElementsByTagName('td') as $cell) 
	    {
	        // push the cell's text to the array
	        $cellVal=$cell->textContent;
	        if(preg_match("/total common stocks.*/",strtolower($cellVal)))
	        {
	        	$start=0;
	        	$end=1;
	        }
	        elseif(preg_match("/common stocks.*/",strtolower($cellVal)))
	        {
	        	$remMec=0;
	        	$cur=0;
	        	$start++;
	        	$end=0;
	        	if($start==1)
	        	{
	        		$last=$i;
	        	}
	        	if($start==2)
	        	{

	        		$start=1;
	        		$remMec=1;
	        		$tbRem[0][]=$i;
	        		$tbRem[1][]=$last;
	        	}

	        }    
	        if($start==1 && $end==0 && $cellVal!="Â Â " && $cellVal!="Â " && $cellVal!=" ")
	        {
	        	$cellVal=str_replace("Â "," ",str_replace("Â Â ","  ",$cellVal));
	        	if(trim($cellVal)!="" && trim($cellVal)!="Â "  && trim($cellVal)!="Â Â " && trim($cellVal)!="$" )
	        	{
	     		   	$flight[] = trim($cellVal);	
	        	}        	
	        }	        
     		$j++;  
	    }
	    $i++;
	    if(isset($flight[0]))
	    {
		    if(strlen($flight[0])<2)
		    {
		    	unset($flight[0]);
		    }
		}
	    
	    if(isset($flight[0]))
	    {
		    if(strlen($flight[0])<2)
		    {
		    	unset($flight[0]);
		    }
		}

	    $rowData[] = $flight;
	}
}


if($start==1 && $end==0)
{
	$tbRem[0][]=count($rowData)+1;
	$tbRem[1][]=$last;
}

//Remove Other Tables
if (strpos($firstHt,$pattern) !== false) 
{

}
else
{
	for($x=0;$x<count($tbRem[0]);$x++)
	{
		for($z=$tbRem[1][$x];$z<$tbRem[0][$x]-1;$z++)
		{
			unset($rowData[$z]);
		}
	}
}
$i=0;


$i=0;
foreach($rowData as $rows)
{	
	$j=0;
	if(isset($rowData[$i]))
	{
		if(empty($rowData))
		{
			unset($rowData[$i]);
			next;
		}
	}
	foreach($rows as $tds)
	{	
		if(isset($rowData[$i][$j]))
		{
			if(trim($rowData[$i][$j])==" " || trim($rowData[$i][$j])=="  " || trim($rowData[$i][$j])=="" || empty($rowData[$i]))
			{
				unset($rowData[$i][$j]);			
			}

		}

		$j++;
	}
	$i++;
}




print_R($rowData);
/*
echo '<table>';
foreach ($rowData as $row => $tr) {
{
    echo '<tr>'; 
    foreach ($tr as $td)

        echo '<td>' . $td .'</td>';
    echo '</tr>';
    $i++;
}
echo '</table>';

for($i=0;$i<150;$i++)
{
    foreach ($rowData[$i] as $td)
    {
		echo $td." ";
	}
    echo "\n";
}
*/
?>