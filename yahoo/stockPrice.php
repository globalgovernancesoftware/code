<?php

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
include 'simple_html_dom.php';
//include "extractTable.php";

$host="localhost"; // Host name 
$username="root"; // Mysql username 
$password=""; 
$con=mysqli_init();
if (!$con)
  {
  die("mysqli_init failed");
  }

if (!mysqli_real_connect($con,"localhost","root","","ysp"))
  {
  die("Connect Error: " . mysqli_connect_error());
  }

$query="select ticker from ysp.complist where exchange<>'PNK';";

$queryIt=mysqli_query($con,$query);

while($row=mysqli_fetch_Array($queryIt))
{
	$data[]=$row['ticker'];
}

unset($data);

//New Data from sedar
$data=["SHP.TO","WAW.TO","WAB.TO","NPT.TO","TBI.TO","WCX.TO","BCO.TO","NOVT.TO","GNOLF.TO","GFD.TO","ADW.TO","LBY.TO","NEW.TO","MIS.TO","UTC.TO","SV.TO","MIO.TO","IDC.TO","CEG.TO","JJJ.TO","SUV.TO","IDK.TO","MCZ.TO","CFE.TO","ADX.TO","MK.TO","GST.TO","INM.TO","BE.TO","EYEA.TO","MTA.TO","AARM.TO","FECOF.TO","OPA.TO","SUN.TO","LBIX.TO","UPL.TO","ZTE.TO","ASTI.TO","CPPXF.TO","DLE.TO","IME.TO","IN.TO","ALQ.TO","JEM.TO","RQB.TO","EDE.TO","SYS.TO","FIRE.TO","WSM.TO","CCY.TO","GTI.TO","ALK.TO","SNSG.TO","AVM.TO","GPK.TO","INO.TO","SQA.TO","CHX.TO","LGM.TO","CYL.TO","TXL.TO","AFF.TO","ARV.TO","GOR.TO","FEC.TO","EA.TO","CHV.TO","RBN.TO","FFT.TO","CNW.TO","TEA.TO","ARQ.TO","BKS.TO","ICEIF.TO","CRZ.TO","BRH.TO","REAHA.TO","PBTU.TO","PRL.TO","EBY.TO","AFI.TO","EM.TO","PDO.TO","SPO.TO","BZI.TO","LGF.TO","GFI.TO","ABK.TO","ZRO.TO","PKG.TO","NIL.TO","MARI.TO","TAI.TO","DNI.TO","KOT.TO","COPR.TO","RLG.TO","ATT.TO","NIL.TO","GLR.TO","ITR.TO","MCL.TO","TAK.TO","RFR.TO","MVT.TO","MOM.TO","MMI.TO","HAN.TO","ETFC.TO","JCI.TO","PVS.TO","OPI.TO","MUR.TO","C.TO","NVUUN.TO","RGL.TO","IVF.TO","SX.TO","ONI.TO","INR.TO","BIG.TO","RES.TO","MQ.TO","VTC.TO","EVG.TO","QCA.TO","VVX.TO","DHC.TO","BDR.TO","TAP.TO","TNY.TO","N.TO","RGO.TO","PMC.TO","BSE.TO","NGH.TO","MOS.TO","PUF.TO","ENI.TO","DOMUN.TO","BMR.TO","HHS.TO","BWC.TO","AMD.TO","PSE.TO","SYL.TO","NVG.TO","EAT.TO","GDP.TO","SNX.TO","GPC.TO","KNR.TO","FDM.TO","NPD.TO","NI.TO","SHV.TO","CCR.TO","LAI.TO","CRI.TO","KBB.TO","FNQ.TO","VGW.TO","BIS.TO","PRI.TO","CZC.TO","WUC.TO","CASC.TO","SBOT.TO","TBP.TO","CORE.TO","MMJ.TO","AMP.TO","JBR.TO","USCO.TO","MCF.TO","PKK.TO","LAN.TO","ACHV.TO","RGUS.TO","LXRP.TO","VSYS.TO","HR.TO","RISE.TO","EI.TO","DIGAF.TO","DNC.TO","RVG.TO","MHY.TO","SVP.TO","CLN.TO","AFD.TO","UMB.TO","NAF.TO","LSTMF.TO","EZM.TO","POOL.TO","AFH.TO","LOOP.TO","GHG.TO","CIM.TO","MEC.TO","PVOTF.TO","MIRL.TO","TO.TO","TCC.TO","INIS.TO","QUAD.TO","PHS.TO","AKE.TO","PIC.TO","RSS.TO","THMG.TO","SAAS.TO","PDH.TO","ADC.TO","TFS.TO","MMF.TO","LDS.TO","DPC.TO","MOG.TO","CBP.TO","EX.TO","NCD.TO","BVQ.TO","ISL.TO","FLT.TO","AMS.TO","CMC.TO","PDT.TO","VCT.TO","FAT.TO","NSM.TO","TTC.TO","JUJU.TO","SQX.TO","WRW.TO","MDD.TO","HC.TO","IP.TO","FFF.TO","KDZ.TO","AXC.TO","SHRC.TO","CK.TO","AYL.TO","OG.TO","DVR.TO","IBAT.TO","FTY.TO","MLK.TO","XMG.TO","GXY.TO","VP.TO","IXR.TO","CLNH.TO","API.TO","ZOR.TO","SNP.TO","SFA.TO","GLH.TO","TAUG.TO","GCS.TO","ZRI.TO","HM.TO","GRT.TO","BUA.TO","IFL.TO","CME.TO","RVR.TO","GBC.TO","PUL.TO","CFB.TO","MINE.TO","EVA.TO","MRTX.TO","EMS.TO","LEO.TO","FKSH.TO","MWIUN.TO","CMS.TO","TSZ.TO","SPRZ.TO","CTT.TO","DMC.TO","MYR.TO","MDM.TO","GIFUN.TO","OLE.TO","AQXP.TO","EFM.TO","OAA.TO","DST.TO","EPY.TO","EAC.TO","SKLN.TO","IMH.TO","XRO.TO","LVI.TO","BLA.TO","MCP.TO","NF.TO","LLP.TO","ISOL.TO","SOIGF.TO","VRT.TO","LHS.TO","MYM.TO","APP.TO","ACG.TO","CRL.TO","CBK.TO","BUX.TO","DDB.TO","J.TO","LION.TO","JGW.TO","ACM.TO","CGQ.TO","MJ.TO","SQR.TO","STV.TO","LTE.TO","TPS.TO","NUR.TO","RIW.TO","MBE.TO","AHG.TO","DEL.TO","PCE.TO","ZNK.TO","GHE.TO","TRL.TO","VGM.TO","CNI.TO","AEF.TO","APG.TO","CO.TO","LXR.TO","SO.TO","WGC.TO","HS.TO","VAI.TO","GEMC.TO","FLOW.TO","BTH.TO","URG.TO","GCA.TO","HU.TO","KEW.TO","IRV.TO","ORTH.TO","NAC.TO","CCN.TO","NLH.TO","FOX.TO","KBU.TO","GRP.TO","ANTL.TO","NGVT.TO","GET.TO","LKSD.TO","LNB.TO","ESTE.TO","EREP.TO","CBT.TO","PREV.TO","BRIO.TO","ECCFT.TO","ESX.TO","AZT.TO","NRGY.TO","PGLC.TO","CMED.TO","MNO.TO","FRII.TO","ADZN.TO","SGI.TO","JPJ.TO","GOOS.TO","BUFF.TO","LFC.TO","CDNT.TO","LEAF.TO","RTHM.TO","ADNT.TO","TETH.TO","SHP.V","WAW.V","WAB.V","NPT.V","TBI.V","WCX.V","BCO.V","NOVT.V","GNOLF.V","GFD.V","ADW.V","LBY.V","NEW.V","MIS.V","UTC.V","SV.V","MIO.V","IDC.V","CEG.V","JJJ.V","SUV.V","IDK.V","MCZ.V","CFE.V","ADX.V","MK.V","GST.V","INM.V","BE.V","EYEA.V","MTA.V","AARM.V","FECOF.V","OPA.V","SUN.V","LBIX.V","UPL.V","ZTE.V","ASTI.V","CPPXF.V","DLE.V","IME.V","IN.V","ALQ.V","JEM.V","RQB.V","EDE.V","SYS.V","FIRE.V","WSM.V","CCY.V","GTI.V","ALK.V","SNSG.V","AVM.V","GPK.V","INO.V","SQA.V","CHX.V","LGM.V","CYL.V","TXL.V","AFF.V","ARV.V","GOR.V","FEC.V","EA.V","CHV.V","RBN.V","FFT.V","CNW.V","TEA.V","ARQ.V","BKS.V","ICEIF.V","CRZ.V","BRH.V","REAHA.V","PBTU.V","PRL.V","EBY.V","AFI.V","EM.V","PDO.V","SPO.V","BZI.V","LGF.V","GFI.V","ABK.V","ZRO.V","PKG.V","NIL.V","MARI.V","TAI.V","DNI.V","KOT.V","COPR.V","RLG.V","ATT.V","NIL.V","GLR.V","ITR.V","MCL.V","TAK.V","RFR.V","MVT.V","MOM.V","MMI.V","HAN.V","ETFC.V","JCI.V","PVS.V","OPI.V","MUR.V","C.V","NVUUN.V","RGL.V","IVF.V","SX.V","ONI.V","INR.V","BIG.V","RES.V","MQ.V","VTC.V","EVG.V","QCA.V","VVX.V","DHC.V","BDR.V","TAP.V","TNY.V","N.V","RGO.V","PMC.V","BSE.V","NGH.V","MOS.V","PUF.V","ENI.V","DOMUN.V","BMR.V","HHS.V","BWC.V","AMD.V","PSE.V","SYL.V","NVG.V","EAT.V","GDP.V","SNX.V","GPC.V","KNR.V","FDM.V","NPD.V","NI.V","SHV.V","CCR.V","LAI.V","CRI.V","KBB.V","FNQ.V","VGW.V","BIS.V","PRI.V","CZC.V","WUC.V","CASC.V","SBOT.V","TBP.V","CORE.V","MMJ.V","AMP.V","JBR.V","USCO.V","MCF.V","PKK.V","LAN.V","ACHV.V","RGUS.V","LXRP.V","VSYS.V","HR.V","RISE.V","EI.V","DIGAF.V","DNC.V","RVG.V","MHY.V","SVP.V","CLN.V","AFD.V","UMB.V","NAF.V","LSTMF.V","EZM.V","POOL.V","AFH.V","LOOP.V","GHG.V","CIM.V","MEC.V","PVOTF.V","MIRL.V","TO.V","TCC.V","INIS.V","QUAD.V","PHS.V","AKE.V","PIC.V","RSS.V","THMG.V","SAAS.V","PDH.V","ADC.V","TFS.V","MMF.V","LDS.V","DPC.V","MOG.V","CBP.V","EX.V","NCD.V","BVQ.V","ISL.V","FLT.V","AMS.V","CMC.V","PDT.V","VCT.V","FAT.V","NSM.V","TTC.V","JUJU.V","SQX.V","WRW.V","MDD.V","HC.V","IP.V","FFF.V","KDZ.V","AXC.V","SHRC.V","CK.V","AYL.V","OG.V","DVR.V","IBAT.V","FTY.V","MLK.V","XMG.V","GXY.V","VP.V","IXR.V","CLNH.V","API.V","ZOR.V","SNP.V","SFA.V","GLH.V","TAUG.V","GCS.V","ZRI.V","HM.V","GRT.V","BUA.V","IFL.V","CME.V","RVR.V","GBC.V","PUL.V","CFB.V","MINE.V","EVA.V","MRTX.V","EMS.V","LEO.V","FKSH.V","MWIUN.V","CMS.V","TSZ.V","SPRZ.V","CTT.V","DMC.V","MYR.V","MDM.V","GIFUN.V","OLE.V","AQXP.V","EFM.V","OAA.V","DST.V","EPY.V","EAC.V","SKLN.V","IMH.V","XRO.V","LVI.V","BLA.V","MCP.V","NF.V","LLP.V","ISOL.V","SOIGF.V","VRT.V","LHS.V","MYM.V","APP.V","ACG.V","CRL.V","CBK.V","BUX.V","DDB.V","J.V","LION.V","JGW.V","ACM.V","CGQ.V","MJ.V","SQR.V","STV.V","LTE.V","TPS.V","NUR.V","RIW.V","MBE.V","AHG.V","DEL.V","PCE.V","ZNK.V","GHE.V","TRL.V","VGM.V","CNI.V","AEF.V","APG.V","CO.V","LXR.V","SO.V","WGC.V","HS.V","VAI.V","GEMC.V","FLOW.V","BTH.V","URG.V","GCA.V","HU.V","KEW.V","IRV.V","ORTH.V","NAC.V","CCN.V","NLH.V","FOX.V","KBU.V","GRP.V","ANTL.V","NGVT.V","GET.V","LKSD.V","LNB.V","ESTE.V","EREP.V","CBT.V","PREV.V","BRIO.V","ECCFT.V","ESX.V","AZT.V","NRGY.V","PGLC.V","CMED.V","MNO.V","FRII.V","ADZN.V","SGI.V","JPJ.V","GOOS.V","BUFF.V","LFC.V","CDNT.V","LEAF.V","RTHM.V","ADNT.V","TETH.V"];
//$data=["FEC.TO"];
$countTicker=count($data);
echo $countTicker;

$newData=0;
for($i=400;$i<$countTicker;$i++)
{
	//echo "https://finance.yahoo.com/quote/".$data[$i]."/history?period1=1482642000&period2=1484024400&interval=1d&filter=history&frequency=1d";
	$html=file_get_html("https://finance.yahoo.com/quote/".$data[$i]."/history?period1=1482642000&period2=1484024400&interval=1d&filter=history&frequency=1d");
	foreach($html->find('tr.BdT') as $rows) 
	{
		$countCol=0;
		foreach($rows->find('td') as $cols)
		{
			if($countCol==5)
			{
				$myData[$newData]['adjClose']=$cols->find('span',0)->plaintext;
				$myData[$newData]['ticker']=$data[$i];

			}
			if($countCol==0)
			{
				$myData[$newData]['date']=$cols->find('span',0)->plaintext;
			}
			$countCol++;
		}
		$newData++;

		//var_dump($rows);
	}
	echo $i."\n (downloaded)";
}

$values="";

foreach($myData as $row)
{
	if(isset($row))
	{
		if(count($row)==3)
		{
			if(isset($row['adjClose']))
			{
				$values.="('".mysqli_real_Escape_string($con,$row['date'])."','".mysqli_real_Escape_string($con,$row['adjClose'])."','".mysqli_real_Escape_string($con,$row['ticker'])."'),";
			}


		}
	}
	if($i%2000==0 && $i!=0)
	{
		$query="INSERT IGNORE INTO ysp.yahooAdjCloseNew (myDate,adjPrice,ticker) VALUES ".substr($values,0,-1);
		mysqli_query($con,$query) or die(Mysqli_error($con));
		$values="";
		echo $i."\n (loaded)";

	}

}

$query="INSERT IGNORE INTO ysp.yahooAdjCloseNew (myDate,adjPrice,ticker) VALUES ".substr($values,0,-1);
mysqli_query($con,$query) or die(Mysqli_error($con));


?>