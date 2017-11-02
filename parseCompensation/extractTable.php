<?php
ini_set("memory_limit","-1");
$tables=array();
function extractTables($doc){

	$html="";
	$html = file_get_contents($doc);
	$part = preg_split('/([.?!>])/', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

	$findIT=0;
	$findITToo=0;
	$name=0;
	$total=0;
	$year=0;
	$total=0;
	$beginning=0;
	$ending=0;
	$salary=0;
	$firstHt="";
	$awards=0;

	$count=0;
	$z=0;
	$final=array();
	    for($i=0;$i<count($part);$i++)
	    {
	    	if (strpos(strtolower($part[$i]), "</table")!== false ) 
	        {
	            $ending++;
	        }   
	        if (strpos(strtolower($part[$i]), "<table")!== false ) 
	        {       
	            $firstHt="";
	            $ending=0;
	            $findIT=0;
	            $name=0;
	            $salary=0;  
	            $awards=0;       
	            $beginning++;
	        }
	        if (strpos(strtolower($part[$i]), "<td")!== false ) 
	        {
	             $findIT++;
	        }
	        if (strpos(strtolower($part[$i]), "name") !== false ) 
	        {
	             $name++;
	        }
	                
	        if (strpos(strtolower($part[$i]), "salary") !== false ) 
	        {
	             $salary++;
	        }    
	        if (strpos(strtolower($part[$i]), "awards") !== false ) 
	        {
	             $awards++;
	        }                

	        if($beginning>0 && $ending>0 && $name>0 && $salary>0 && $awards>0)
	        {   $firstHt.=$part[$i];
	            
	            $final[]=$firstHt.">";
	            $firstHt="";
	            $findIT=0;
	            $beginning=0;
	            $ending=0;
	            $name=0;      
	            $salary=0;
	            $awards=0;

	        }     
	        elseif($beginning>0 && $ending>0 && $name<1 && $salary<1  && $awards<1)
	        {   
	            $firstHt="";
	            $findIT=0;
	            $beginning=0;
	            $ending=0;
	            $total=0;
	            $name=0;
	            $year=0;
	            $salary=0;
	            $awards=0;

	        }
	        elseif($beginning==1 && $ending==0)
	        {   
	            $firstHt.=$part[$i];
	        }
	        

	    }

	    $nrTab=0;
	    $tables=$final;
	    unset($final);
	    
	    $nrTab=count($tables);
	    for($z=0;$z<$nrTab;$z++)
	    {
	        $last=substr($tables[$z], -8);
	        if(strtolower($last)!="</table>")
	        {
	            unset($tables[$z]);
	        }
	    }
	   	unset($html);
	    unset($part);
	    return $tables;
};

?>