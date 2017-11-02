<?php
ini_set('memory_limit', '-1');
ini_set('default_charset', 'utf-8');
//$file="http://www.sec.gov/Archives/edgar/data/891190/000093247115001044/0000932471-15-001044.txt";
//$file="http://www.sec.gov/Archives/edgar/data/736054/000093247114006749/0000932471-14-006749.txt";
$file="http://www.sec.gov/Archives/edgar/data/24238/000087846714000949/0000878467-14-000949.txt";
//$file="http://www.sec.gov/Archives/edgar/data/930667/000119312514357189/0001193125-14-357189.txt";

$html = file($file);

//print_R($html);
 $findIT=0;

 $beginning=0;

 $ending=0;

 $firstHt="";

$begin=preg_grep("/HTML\>.*/", $html);
if(count($begin)==0)
{
    $begin=preg_grep("/html\>.*/", $html);   
}

//echo $begin;
//echo $end;
$lim=array_keys($begin);
$firstHt="";

for($i=$lim[0];$i<=$lim[1];$i++)
{
    $firstHt.=$html[$i];
}
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
	        elseif(preg_match("/common stocks.*/",strtolower($cellVal)) && !preg_match("/common stocks - continued.*/",strtolower($cellVal)))
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
	        if($start==1 && $end==0 && $cellVal!="&nbsp;")
	        {
	        	$flight[] = $cell->textContent;	    
	        	echo $cell->textContent;    	
	        }	        
     		$j++;  
	    }
	    $i++;
	    if(isset($flight[0]))
	    {
		    if(strlen($flight[0])<5)
		    {
		    	unset($flight[0]);
		    }
		}


	    $rowData[] = $flight;
	}
}
print_R($rowData);
/*
if($start==1 && $end==0)
{
	$tbRem[0][]=count($rowData)+1;
	$tbRem[1][]=$last;
}

//Remove Other Tables

for($x=0;$x<count($tbRem[0]);$x++)
{
	for($z=$tbRem[1][$x];$z<$tbRem[0][$x]-1;$z++)
	{
		unset($rowData[$z]);
	}
}
$i=0;

echo '<table>';
foreach ($rowData as $row => $tr) {
    echo '<tr>'; 
    foreach ($tr as $td)
        echo '<td>' . $td .'</td>';
    echo '</tr>';
    $i++;
}
echo '</table>';
*/
?>