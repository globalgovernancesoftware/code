<?php
ini_set('max_execution_time', 30000);
ini_set('memory_limit', '-1');
require_once("simple_html_dom.php");
header('Content-Type: text/html; charset=utf-8');

include 'mysqliConnect.php';
mysqli_select_db($con,"novaGlobalADV");	

$folder="F:\Novashare\CSA\FIRM\FIRMDES\\";
for($z=2523;$z<3048;$z++)
{
	if($z==100)
	{
		mysqli_close($con);
		include 'mysqliConnect.php';
		mysqli_select_db($con,"novaGlobalADV");		
	}



	$file=$z.".htm";
	$mainId=substr($file, 0,-4);

	$html=file_get_html($folder.$file);

	//Names
	$prevFundNameArray=$html->find('#ctl00_bodyContent_lblPreviousNamesList',0)->find('li');
	$prevFundNames=array();
	foreach($prevFundNameArray as $prevFundName)
	{
		$prevFundNames[]=$prevFundName->innertext;
	}
	if($html->find('.current-content',0))
	{
		$firmInfo=$html->find('.current-content',0);
	
		$firmName=$firmInfo->find('table',0)->find('tr',0)->find('td',1)->find('b',0)->innertext;
		$firmAddress=$firmInfo->find('table',0)->find('tr',1)->find('td',1)->innertext;

		$firmAddressPhone=explode("Phone: ",$firmAddress);

		if(count($firmAddressPhone)>1)
		{
			$firmAddressF=str_replace("  "," ",str_replace("  "," ",trim(str_replace("<br>", " ", $firmAddressPhone[0]))));
			$firmPhoneF=str_replace("  "," ",str_replace("  "," ",(str_replace("<br>", " ", $firmAddressPhone[1]))));
		}
		else
		{
			$firmAddressF=str_replace("  "," ",str_replace("  "," ",trim(str_replace("<br>", " ", $firmAddressPhone[0]))));
			$firmPhoneF="";
		}


		$provinceName=$html->find('.province-name');

		$province=array();
		$i=0;
		foreach($provinceName as $pName)
		{
			$province[$i]['name']=$pName->innertext;
			$i++;
		}

		$provinceContent=$html->find('.province-content');
		$i=0;

		if(count($provinceContent)>0)
		{
			foreach($provinceContent as $pContent)
			{
				if($pContent->find('div',0))
				{
					$pClick=$pContent->find('div',0)->find('a',0)->getAttribute('onclick');
					$pClick=str_replace("&quot;","",str_replace("javascript:return OpenRegisteredPermittedIndividuals(","",$pClick));
					$pClickA=explode(",",$pClick);
					$province[$i]['id']=str_replace('"','',$pClickA[0]);
					$province[$i]['invName']=str_replace('"','',$pClickA[1]);
					$province[$i]['pCode']=str_replace('"','',str_replace("   ","",$pClickA[2]));
					$province[$i]['pName']=str_replace('"','',str_replace(" ","",str_replace(");","",$pClickA[3])));

					$provinceType=$pContent->find('ul',0)->find('li');
					$j=0;
					foreach($provinceType as $pType)
					{
						if(strlen($pType->innertext)<50){$province[$i]['pType'][$j]=$pType->innertext;}
						$j++;
					}
					$i++;	
					$tExist=1;			
				}


			}
			if(isset($province))
			{
				$queryInfo="insert into cdnFirms values ('".mysqli_real_escape_string($con,$mainId)."','".mysqli_real_escape_string($con,$firmName)."','".mysqli_real_escape_string($con,$firmAddressF)."','".mysqli_real_escape_string($con,$firmPhoneF)."');";				
			}

		}
		if(isset($prevFundNames))
		{
			if(count($prevFundNames)>0)
			{
				$valuesPrevNames="";
				foreach($prevFundNames as $pGpFN)
				{
					$valuesPrevNames.="('".mysqli_real_escape_string($con,$mainId)."','".mysqli_real_escape_string($con,$pGpFN)."'),";
				}
				$queryPrevNames="insert into cdnFirmPrevName values ".substr($valuesPrevNames,0,-1).";";		
			}		
		}


		if(count($province)>0  && isset($tExist))
		{
			$valuesProvinces="";
			$valuesTypes="";
			foreach($province as $pCont)
			{
				if(isset($pCont['id']))
				{
					$valuesProvinces.="('".mysqli_real_escape_string($con,$mainId)."','".mysqli_real_escape_string($con,$pCont['id'])."','".mysqli_real_escape_string($con,$pCont['invName'])."','".mysqli_real_escape_string($con,$pCont['pCode'])."','".mysqli_real_escape_string($con,$pCont['pName'])."'),";
					$tExist2=0;

				}


				if(isset($pCont['pType']))
				{
					if(count($pCont['pType'])>0)
					{
						foreach($pCont['pType'] as $pType)
						{
							$valuesTypes.="('".mysqli_real_escape_string($con,$mainId)."','".mysqli_real_escape_string($con,$pCont['pCode'])."','".mysqli_real_escape_string($con,$pType)."'),";
						}	
						$queryFirmType="insert into cdnFirmType values ".substr($valuesTypes,0,-1).";";
					}				
				}

			}
			if(isset($tExist2))
			{
				$queryProvinces="insert into cdnFirmProvince values ".substr($valuesProvinces,0,-1).";";	
				unset($tExist2);	

			}
		}


		if(isset($queryInfo))
		{
			mysqli_query($con,$queryInfo) or die(mysqli_error($con)."1");
			unset($queryInfo);

		}
		if(isset($queryPrevNames))
		{
			mysqli_query($con,$queryPrevNames) or die(mysqli_error($con)."2");
			unset($queryPrevNames);

		}
		if(isset($queryFirmType))
		{
			mysqli_query($con,$queryFirmType) or die(mysqli_error($con)."3");
			unset($queryFirmType);

		}
		if(isset($queryProvinces))
		{
			mysqli_query($con,$queryProvinces) or die(mysqli_error($con).$queryProvinces);
			unset($queryProvinces);

		}		


	}
		echo $z."\n";		

}


/*
$dateFM=$html->find('a');
$test=$html->find('div[style="font-size: 7.5pt; line-height: 130%"]');

$pmS=$test[0]->innertext();
//Find Text
$pmSE=explode("\r\n", $pmS);
echo count($pmSE);
$lenFundName1=strlen('<div class="tSidebarTitle2">');
$lenFundName2=strlen('</div>');


$fPMN=strpos($pmS, '<div class="tSidebarTitle2">');
$lPMN=strpos($pmS, "</div>");


//echo substr($pmS,$fPMN);
//echo $fPMN;
for($i=0;$i<count($dateFM);$i++)
{
	//echo $fmS[$i]->outer;
}
*/

?>