<?php
ini_set('memory_limit', '-1');
//Check MaxDate


// Create DOM from URL or file

function loadXML($url)
{
	include 'mysqliConnect.php';
	$sel=mysqli_Select_db($con, "novaGlobalADV");	
		$xml = simplexml_load_file($url);
		// Parse all links 
		$countDt=0;
		//foreach($html->find('td ]') as $td)
		$indList=$xml->Firms;
			$i=0;
			$j=0;
			$k=0;
			$count=0;
		// Parse all links 
		foreach($indList->Firm as $Firm) 
		{
			//Info
	$Info=$Firm->Info->attributes();
		$FirmCrdNb[]=mysqli_real_escape_String($con,(string) $Info['FirmCrdNb']);
		$SECNb[]=mysqli_real_escape_String($con,(string) $Info['SECNb']);
		$BusNm[]=mysqli_real_escape_String($con,(string) $Info['BusNm']);
		$LegalNm[]=mysqli_real_escape_String($con,(string) $Info['LegalNm']);
	$MainAddr=$Firm->MainAddr->attributes();
		$MainAddr_Strt1[]=mysqli_real_escape_String($con,(string) $MainAddr['Strt1']);
		$MainAddr_City[]=mysqli_real_escape_String($con,(string) $MainAddr['City']);
		$MainAddr_State[]=mysqli_real_escape_String($con,(string) $MainAddr['State']);
		$MainAddr_Cntry[]=mysqli_real_escape_String($con,(string) $MainAddr['Cntry']);
		$MainAddr_PostlCd[]=mysqli_real_escape_String($con,(string) $MainAddr['PostlCd']);
		$MainAddr_PhNb[]=mysqli_real_escape_String($con,(string) $MainAddr['PhNb']);
		$MainAddr_FaxNb[]=mysqli_real_escape_String($con,(string) $MainAddr['FaxNb']);
		$MainAddr_Strt2[]=mysqli_real_escape_String($con,(string) $MainAddr['Strt2']);
	$MailingAddr=$Firm->MailingAddr->attributes();
		$MailingAddr_Strt1[]=mysqli_real_escape_String($con,(string) $MailingAddr['Strt1']);
		$MailingAddr_City[]=mysqli_real_escape_String($con,(string) $MailingAddr['City']);
		$MailingAddr_State[]=mysqli_real_escape_String($con,(string) $MailingAddr['State']);
		$MailingAddr_Cntry[]=mysqli_real_escape_String($con,(string) $MailingAddr['Cntry']);
		$MailingAddr_PostlCd[]=mysqli_real_escape_String($con,(string) $MailingAddr['PostlCd']);
		$MailingAddr_Strt2[]=mysqli_real_escape_String($con,(string) $MailingAddr['Strt2']);
	$Filing=$Firm->Filing->attributes();
		$Filing_Dt[]=mysqli_real_escape_String($con,(string) $Filing['Dt']);
		$FormVrsn[]=mysqli_real_escape_String($con,(string) $Filing['FormVrsn']);
	$Item1=$Firm->FormInfo->Part1A->Item1->attributes();
		$Q1I[]=mysqli_real_escape_String($con,(string) $Item1['Q1I']);
		$Q1M[]=mysqli_real_escape_String($con,(string) $Item1['Q1M']);
		$Q1N[]=mysqli_real_escape_String($con,(string) $Item1['Q1N']);
		$Q1O[]=mysqli_real_escape_String($con,(string) $Item1['Q1O']);
		$Q1P[]=mysqli_real_escape_String($con,(string) $Item1['Q1P']);
		$CIKNb[]=mysqli_real_escape_String($con,(string) $Item1['CIKNb']);	
	$Item3A=$Firm->FormInfo->Part1A->Item3A->attributes();
		$OrgFormNm[]=mysqli_real_escape_String($con,(string) $Item3A['OrgFormNm']);
		$OrgFormOthNm[]=mysqli_real_escape_String($con,(string) $Item3A['OrgFormOthNm']);
	$Item3B=$Firm->FormInfo->Part1A->Item3B->attributes();
		$Q3B[]=mysqli_real_escape_String($con,(string) $Item3B['Q3B']);
	$Item3C=$Firm->FormInfo->Part1A->Item3C->attributes();
		$StateCD[]=mysqli_real_escape_String($con,(string) $Item3C['StateCD']);
		$CntryNm[]=mysqli_real_escape_String($con,(string) $Item3C['CntryNm']);
	$Item5A=$Firm->FormInfo->Part1A->Item5A->attributes();
		$TtlEmp[]=mysqli_real_escape_String($con,(string) $Item5A['TtlEmp']);
	$Item5B=$Firm->FormInfo->Part1A->Item5B->attributes();
		$Q5B1[]=mysqli_real_escape_String($con,(string) $Item5B['Q5B1']);
		$Q5B2[]=mysqli_real_escape_String($con,(string) $Item5B['Q5B2']);
		$Q5B3[]=mysqli_real_escape_String($con,(string) $Item5B['Q5B3']);
		$Q5B4[]=mysqli_real_escape_String($con,(string) $Item5B['Q5B4']);
		$Q5B5[]=mysqli_real_escape_String($con,(string) $Item5B['Q5B5']);
		$Q5B6[]=mysqli_real_escape_String($con,(string) $Item5B['Q5B6']);
	$Item5C=$Firm->FormInfo->Part1A->Item5C->attributes();
		$Q5C1[]=mysqli_real_escape_String($con,(string) $Item5C['Q5C1']);
		$Q5C1Oth[]=mysqli_real_escape_String($con,(string) $Item5C['Q5C1Oth']);
		$Q5C2[]=mysqli_real_escape_String($con,(string) $Item5C['Q5C2']);
	$Item5D=$Firm->FormInfo->Part1A->Item5D->attributes();
		$Q5D1A[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1A']);
		$Q5D1B[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1B']);
		$Q5D1C[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1C']);
		$Q5D1D[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1D']);
		$Q5D1E[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1E']);
		$Q5D1F[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1F']);
		$Q5D1G[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1G']);
		$Q5D1H[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1H']);
		$Q5D1I[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1I']);
		$Q5D1J[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1J']);
		$Q5D1K[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1K']);
		$Q5D1L[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1L']);
		$Q5D1M[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1M']);
		$Q5D2A[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2A']);
		$Q5D2B[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2B']);
		$Q5D2C[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2C']);
		$Q5D2D[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2D']);
		$Q5D2E[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2E']);
		$Q5D2F[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2F']);
		$Q5D2G[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2G']);
		$Q5D2H[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2H']);
		$Q5D2I[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2I']);
		$Q5D2J[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2J']);
		$Q5D2K[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2K']);
		$Q5D2L[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2L']);
		$Q5D2M[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2M']);
		$Q5D1MOth[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D1MOth']);
		$Q5D2MOth[]=mysqli_real_escape_String($con,(string) $Item5D['Q5D2MOth']);
	$Item5E=$Firm->FormInfo->Part1A->Item5E->attributes();
		$Q5E1[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E1']);
		$Q5E2[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E2']);
		$Q5E3[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E3']);
		$Q5E4[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E4']);
		$Q5E5[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E5']);
		$Q5E6[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E6']);
		$Q5E7[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E7']);
		$Q5E7Oth[]=mysqli_real_escape_String($con,(string) $Item5E['Q5E7Oth']);
	$Item5F=$Firm->FormInfo->Part1A->Item5F->attributes();
		$Q5F1[]=mysqli_real_escape_String($con,(string) $Item5F['Q5F1']);
		$Q5F2A[]=mysqli_real_escape_String($con,(string) $Item5F['Q5F2A']);
		$Q5F2B[]=mysqli_real_escape_String($con,(string) $Item5F['Q5F2B']);
		$Q5F2C[]=mysqli_real_escape_String($con,(string) $Item5F['Q5F2C']);
		$Q5F2D[]=mysqli_real_escape_String($con,(string) $Item5F['Q5F2D']);
		$Q5F2E[]=mysqli_real_escape_String($con,(string) $Item5F['Q5F2E']);
		$Q5F2F[]=mysqli_real_escape_String($con,(string) $Item5F['Q5F2F']);
	$Item5G=$Firm->FormInfo->Part1A->Item5G->attributes();
		$Q5G1[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G1']);
		$Q5G2[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G2']);
		$Q5G3[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G3']);
		$Q5G4[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G4']);
		$Q5G5[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G5']);
		$Q5G6[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G6']);
		$Q5G7[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G7']);
		$Q5G8[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G8']);
		$Q5G9[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G9']);
		$Q5G10[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G10']);
		$Q5G11[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G11']);
		$Q5G12[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G12']);
		$Q5G12Oth[]=mysqli_real_escape_String($con,(string) $Item5G['Q5G12Oth']);
	$Item5H=$Firm->FormInfo->Part1A->Item5H->attributes();
		$Q5H[]=mysqli_real_escape_String($con,(string) $Item5H['Q5H']);
		$Q5HMT500[]=mysqli_real_escape_String($con,(string) $Item5H['Q5HMT500']);
	$Item5I=$Firm->FormInfo->Part1A->Item5I->attributes();
		$Q5I1[]=mysqli_real_escape_String($con,(string) $Item5I['Q5I1']);
		$Q5I2[]=mysqli_real_escape_String($con,(string) $Item5I['Q5I2']);
	$Item5J=$Firm->FormInfo->Part1A->Item5J->attributes();
		$Q5J[]=mysqli_real_escape_String($con,(string) $Item5J['Q5J']);
	$Item6A=$Firm->FormInfo->Part1A->Item6A->attributes();
		$Q6A1[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A1']);
		$Q6A2[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A2']);
		$Q6A3[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A3']);
		$Q6A4[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A4']);
		$Q6A5[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A5']);
		$Q6A6[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A6']);
		$Q6A7[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A7']);
		$Q6A8[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A8']);
		$Q6A9[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A9']);
		$Q6A10[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A10']);
		$Q6A11[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A11']);
		$Q6A12[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A12']);
		$Q6A13[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A13']);
		$Q6A14[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A14']);
		$Q6A14Oth[]=mysqli_real_escape_String($con,(string) $Item6A['Q6A14Oth']);
	$Item6B=$Firm->FormInfo->Part1A->Item6B->attributes();
		$Q6B1[]=mysqli_real_escape_String($con,(string) $Item6B['Q6B1']);
		$Q6B3[]=mysqli_real_escape_String($con,(string) $Item6B['Q6B3']);
		$Q6B2[]=mysqli_real_escape_String($con,(string) $Item6B['Q6B2']);
	$Item7A=$Firm->FormInfo->Part1A->Item7A->attributes();
		$Q7A1[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A1']);
		$Q7A2[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A2']);
		$Q7A3[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A3']);
		$Q7A4[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A4']);
		$Q7A5[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A5']);
		$Q7A6[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A6']);
		$Q7A7[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A7']);
		$Q7A8[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A8']);
		$Q7A9[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A9']);
		$Q7A10[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A10']);
		$Q7A11[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A11']);
		$Q7A12[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A12']);
		$Q7A13[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A13']);
		$Q7A14[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A14']);
		$Q7A15[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A15']);
		$Q7A16[]=mysqli_real_escape_String($con,(string) $Item7A['Q7A16']);
	$Item7B=$Firm->FormInfo->Part1A->Item7B->attributes();
		$Q7B[]=mysqli_real_escape_String($con,(string) $Item7B['Q7B']);
	$Item8A=$Firm->FormInfo->Part1A->Item8A->attributes();
		$Q8A1[]=mysqli_real_escape_String($con,(string) $Item8A['Q8A1']);
		$Q8A2[]=mysqli_real_escape_String($con,(string) $Item8A['Q8A2']);
		$Q8A3[]=mysqli_real_escape_String($con,(string) $Item8A['Q8A3']);
	$Item8B=$Firm->FormInfo->Part1A->Item8B->attributes();
		$Q8B1[]=mysqli_real_escape_String($con,(string) $Item8B['Q8B1']);
		$Q8B2[]=mysqli_real_escape_String($con,(string) $Item8B['Q8B2']);
		$Q8B3[]=mysqli_real_escape_String($con,(string) $Item8B['Q8B3']);
	$Item8C=$Firm->FormInfo->Part1A->Item8C->attributes();
		$Q8C1[]=mysqli_real_escape_String($con,(string) $Item8C['Q8C1']);
		$Q8C2[]=mysqli_real_escape_String($con,(string) $Item8C['Q8C2']);
		$Q8C3[]=mysqli_real_escape_String($con,(string) $Item8C['Q8C3']);
		$Q8C4[]=mysqli_real_escape_String($con,(string) $Item8C['Q8C4']);
	$Item8D=$Firm->FormInfo->Part1A->Item8D->attributes();
		$Q8D[]=mysqli_real_escape_String($con,(string) $Item8D['Q8D']);
	$Item8E=$Firm->FormInfo->Part1A->Item8E->attributes();
		$Q8E[]=mysqli_real_escape_String($con,(string) $Item8E['Q8E']);
	$Item8F=$Firm->FormInfo->Part1A->Item8F->attributes();
		$Q8F[]=mysqli_real_escape_String($con,(string) $Item8F['Q8F']);
	$Item8G=$Firm->FormInfo->Part1A->Item8G->attributes();
		$Q8G1[]=mysqli_real_escape_String($con,(string) $Item8G['Q8G1']);
		$Q8G2[]=mysqli_real_escape_String($con,(string) $Item8G['Q8G2']);
	$Item8H=$Firm->FormInfo->Part1A->Item8H->attributes();
		$Q8H[]=mysqli_real_escape_String($con,(string) $Item8H['Q8H']);
	$Item8I=$Firm->FormInfo->Part1A->Item8I->attributes();
		$Q8I[]=mysqli_real_escape_String($con,(string) $Item8I['Q8I']);
	$Item9A=$Firm->FormInfo->Part1A->Item9A->attributes();
		$Q9A1A[]=mysqli_real_escape_String($con,(string) $Item9A['Q9A1A']);
		$Q9A1B[]=mysqli_real_escape_String($con,(string) $Item9A['Q9A1B']);
		$Q9A2A[]=mysqli_real_escape_String($con,(string) $Item9A['Q9A2A']);
		$Q9A2B[]=mysqli_real_escape_String($con,(string) $Item9A['Q9A2B']);
	$Item9B=$Firm->FormInfo->Part1A->Item9B->attributes();
		$Q9B1A[]=mysqli_real_escape_String($con,(string) $Item9B['Q9B1A']);
		$Q9B1B[]=mysqli_real_escape_String($con,(string) $Item9B['Q9B1B']);
		$Q9B2A[]=mysqli_real_escape_String($con,(string) $Item9B['Q9B2A']);
		$Q9B2B[]=mysqli_real_escape_String($con,(string) $Item9B['Q9B2B']);
	$Item9C=$Firm->FormInfo->Part1A->Item9C->attributes();
		$Q9C1[]=mysqli_real_escape_String($con,(string) $Item9C['Q9C1']);
		$Q9C2[]=mysqli_real_escape_String($con,(string) $Item9C['Q9C2']);
		$Q9C3[]=mysqli_real_escape_String($con,(string) $Item9C['Q9C3']);
		$Q9C4[]=mysqli_real_escape_String($con,(string) $Item9C['Q9C4']);
	$Item9D=$Firm->FormInfo->Part1A->Item9D->attributes();
		$Q9D1[]=mysqli_real_escape_String($con,(string) $Item9D['Q9D1']);
		$Q9D2[]=mysqli_real_escape_String($con,(string) $Item9D['Q9D2']);
	$Item9E=$Firm->FormInfo->Part1A->Item9E->attributes();
		$Q9E[]=mysqli_real_escape_String($con,(string) $Item9E['Q9E']);
	$Item9F=$Firm->FormInfo->Part1A->Item9F->attributes();
		$Q9F[]=mysqli_real_escape_String($con,(string) $Item9F['Q9F']);
	$Item10=$Firm->FormInfo->Part1A->Item10->attributes();
		$Q10A[]=mysqli_real_escape_String($con,@(string) $Item10['Q10A']);
	$Item11=$Firm->FormInfo->Part1A->Item11->attributes();
		$Q11[]=mysqli_real_escape_String($con,(string) $Item11['Q11']);
	$Item11A=$Firm->FormInfo->Part1A->Item11A->attributes();
		$Q11A1[]=mysqli_real_escape_String($con,(string) $Item11A['Q11A1']);
		$Q11A2[]=mysqli_real_escape_String($con,(string) $Item11A['Q11A2']);
	$Item11B=$Firm->FormInfo->Part1A->Item11B->attributes();
		$Q11B1[]=mysqli_real_escape_String($con,(string) $Item11B['Q11B1']);
		$Q11B2[]=mysqli_real_escape_String($con,(string) $Item11B['Q11B2']);
	$Item11C=$Firm->FormInfo->Part1A->Item11C->attributes();
		$Q11C1[]=mysqli_real_escape_String($con,(string) $Item11C['Q11C1']);
		$Q11C2[]=mysqli_real_escape_String($con,(string) $Item11C['Q11C2']);
		$Q11C3[]=mysqli_real_escape_String($con,(string) $Item11C['Q11C3']);
		$Q11C4[]=mysqli_real_escape_String($con,(string) $Item11C['Q11C4']);
		$Q11C5[]=mysqli_real_escape_String($con,(string) $Item11C['Q11C5']);
	$Item11D=$Firm->FormInfo->Part1A->Item11D->attributes();
		$Q11D1[]=mysqli_real_escape_String($con,(string) $Item11D['Q11D1']);
		$Q11D2[]=mysqli_real_escape_String($con,(string) $Item11D['Q11D2']);
		$Q11D3[]=mysqli_real_escape_String($con,(string) $Item11D['Q11D3']);
		$Q11D4[]=mysqli_real_escape_String($con,(string) $Item11D['Q11D4']);
		$Q11D5[]=mysqli_real_escape_String($con,(string) $Item11D['Q11D5']);
	$Item11E=$Firm->FormInfo->Part1A->Item11E->attributes();
		$Q11E1[]=mysqli_real_escape_String($con,(string) $Item11E['Q11E1']);
		$Q11E2[]=mysqli_real_escape_String($con,(string) $Item11E['Q11E2']);
		$Q11E3[]=mysqli_real_escape_String($con,(string) $Item11E['Q11E3']);
		$Q11E4[]=mysqli_real_escape_String($con,(string) $Item11E['Q11E4']);
	$Item11F=$Firm->FormInfo->Part1A->Item11F->attributes();
		$Q11F[]=mysqli_real_escape_String($con,(string) $Item11F['Q11F']);
	$Item11G=$Firm->FormInfo->Part1A->Item11G->attributes();
		$Q11G[]=mysqli_real_escape_String($con,(string) $Item11G['Q11G']);
	$Item11H=$Firm->FormInfo->Part1A->Item11H->attributes();
		$Q11H1A[]=mysqli_real_escape_String($con,(string) $Item11H['Q11H1A']);
		$Q11H1B[]=mysqli_real_escape_String($con,(string) $Item11H['Q11H1B']);
		$Q11H1C[]=mysqli_real_escape_String($con,(string) $Item11H['Q11H1C']);
		$Q11H2[]=mysqli_real_escape_String($con,(string) $Item11H['Q11H2']);
	$Item2=$Firm->FormInfo->Part1B->Item2->attributes();
		$Q1B2B4[]=mysqli_real_escape_String($con,(string) $Item2['Q1B2B4']);
		$Q1B2B1[]=mysqli_real_escape_String($con,(string) $Item2['Q1B2B1']);
		$Q1B2B2[]=mysqli_real_escape_String($con,(string) $Item2['Q1B2B2']);
	$DsclrQstns=$Firm->FormInfo->Part1B->DsclrQstns->attributes();
		$Q1B2C[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2C']);
		$Q1B2D[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2D']);
		$Q1B2E1[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2E1']);
		$Q1B2E2[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2E2']);
		$Q1B2E3[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2E3']);
		$Q1B2E4[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2E4']);
		$Q1B2E5[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2E5']);
		$Q1B2F1[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2F1']);
		$Q1B2F2[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2F2']);
		$Q1B2F3[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2F3']);
		$Q1B2F4[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2F4']);
		$Q1B2F5[]=mysqli_real_escape_String($con,(string) $DsclrQstns['Q1B2F5']);
	$ItemG=$Firm->FormInfo->Part1B->ItemG->attributes();
		$Q1B2G2[]=mysqli_real_escape_String($con,(string) $ItemG['Q1B2G2']);
		$Q1BG1TaxPrprr[]=mysqli_real_escape_String($con,(string) $ItemG['Q1BG1TaxPrprr']);
		$Q1BG1IssueScrts[]=mysqli_real_escape_String($con,(string) $ItemG['Q1BG1IssueScrts']);
		$Q1BG1XcldPoolInvmt[]=mysqli_real_escape_String($con,(string) $ItemG['Q1BG1XcldPoolInvmt']);
		$Q1BG1PoolInvmt[]=mysqli_real_escape_String($con,(string) $ItemG['Q1BG1PoolInvmt']);
		$Q1BG1ReAdvsr[]=mysqli_real_escape_String($con,(string) $ItemG['Q1BG1ReAdvsr']);
	$ItemH=$Firm->FormInfo->Part1B->ItemH->attributes();
		$Q1B2HScrtsNvsmt[]=mysqli_real_escape_String($con,(string) $ItemH['Q1B2HScrtsNvsmt']);
		$Q1B2HNScrtsNvsmt[]=mysqli_real_escape_String($con,(string) $ItemH['Q1B2HNScrtsNvsmt']);
		$Q1B2HScrtsNvsmtAm[]=mysqli_real_escape_String($con,(string) $ItemH['Q1B2HScrtsNvsmtAm']);
		$Q1B2HNScrtsNvsmtAm[]=mysqli_real_escape_String($con,(string) $ItemH['Q1B2HNScrtsNvsmtAm']);
	$ItemI=$Firm->FormInfo->Part1B->ItemI->attributes();
		$Q1B2I1[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I1']);
		$Q1B2I1A[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I1A']);
		$Q1B2I1B[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I1B']);
		$Q1B2I1C[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I1C']);
		$Q1B2I2Ai[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I2Ai']);
		$Q1B2I2B[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I2B']);
		$Q1B2I3[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I3']);
		$Q1B2I2AiiAtrny[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I2AiiAtrny']);
		$Q1B2I2AiiCpa[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I2AiiCpa']);
		$Q1B2I2AiiOth[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I2AiiOth']);
		$Q1B2I2AiiOthTx[]=mysqli_real_escape_String($con,(string) $ItemI['Q1B2I2AiiOthTx']);
	$ItemJ=$Firm->FormInfo->Part1B->ItemJ->attributes();
		$Q1BJ1A[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ1A']);
		$Q1BJ1B[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ1B']);
		$Q1BJ2A[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ2A']);
		$Q1BJ2BCfp[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ2BCfp']);
		$Q1BJ2BCfa[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ2BCfa']);
		$Q1BJ2BChfc[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ2BChfc']);
		$Q1BJ2BCic[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ2BCic']);
		$Q1BJ2BPfs[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ2BPfs']);
		$Q1BJ2BNone[]=mysqli_real_escape_String($con,(string) $ItemJ['Q1BJ2BNone']);
	$ItemK=$Firm->FormInfo->Part1B->ItemK->attributes();
		$Q1B2K1[]=mysqli_real_escape_String($con,(string) $ItemK['Q1B2K1']);
		$Q1B2K2[]=mysqli_real_escape_String($con,(string) $ItemK['Q1B2K2']);




		//StateRgstn
		foreach($Firm->StateRgstn->Rgltrs->Rgltr as $StateRgstn)
		{
			$StateRg=$StateRgstn->attributes();
			$CdS[$i][]=mysqli_real_escape_String($con,(string) $StateRg['Cd']);
			$StS[$i][]=mysqli_real_escape_String($con,(string) $StateRg['St']);
			$DtS[$i][]=mysqli_real_escape_String($con,(string) $StateRg['Dt']);
		}

		//ERA
		foreach($Firm->ERA->Rgltrs->Rgltr as $ERA)
		{
			$ER=$ERA->attributes();
			$CdE[$i][]=mysqli_real_escape_String($con,(string) $ER['Cd']);
			$StE[$i][]=mysqli_real_escape_String($con,(string) $ER['St']);
			$DtE[$i][]=mysqli_real_escape_String($con,(string) $ER['Dt']);
		}

		//WebAddress
		foreach($Firm->FormInfo->Part1A->Item1->WebAddrs->WebAddr as $WebAddr)
		{
			$WebAddress[$i][]=mysqli_real_escape_String($con,(string) $WebAddr);
		}
	
		$i++;
	}



	//Insert into Tables

	//Info

	$query="";
	for($i=0;$i<count($FirmCrdNb);$i++)
	{
		if($i%200==0)
		{
		echo "Info".$i."\n";

			$query.="('".$FirmCrdNb[$i]."','".
							$SECNb[$i]."','".
							$BusNm[$i]."','".
							$LegalNm[$i]."','".
							$MainAddr_Strt1[$i]."','".
							$MainAddr_City[$i]."','".
							$MainAddr_State[$i]."','".
							$MainAddr_Cntry[$i]."','".
							$MainAddr_PostlCd[$i]."','".
							$MainAddr_PhNb[$i]."','".
							$MainAddr_FaxNb[$i]."','".
							$MainAddr_Strt2[$i]."','".
							$MailingAddr_Strt1[$i]."','".
							$MailingAddr_City[$i]."','".
							$MailingAddr_State[$i]."','".
							$MailingAddr_Cntry[$i]."','".
							$MailingAddr_PostlCd[$i]."','".
							$MailingAddr_Strt2[$i]."','".
							$Filing_Dt[$i]."','".
							$FormVrsn[$i]."','".
							$Q1I[$i]."','".
							$Q1M[$i]."','".
							$Q1N[$i]."','".
							$Q1O[$i]."','".
							$Q1P[$i]."','".
							$CIKNb[$i]."','".
							$OrgFormNm[$i]."','".
							$OrgFormOthNm[$i]."','".
							$Q3B[$i]."','".
							$StateCD[$i]."','".
							$CntryNm[$i]."','".
							$TtlEmp[$i]."','".
							$Q5B1[$i]."','".
							$Q5B2[$i]."','".
							$Q5B3[$i]."','".
							$Q5B4[$i]."','".
							$Q5B5[$i]."','".
							$Q5B6[$i]."','".
							$Q5C1[$i]."','".
							$Q5C1Oth[$i]."','".
							$Q5C2[$i]."','".
							$Q5D1A[$i]."','".
							$Q5D1B[$i]."','".
							$Q5D1C[$i]."','".
							$Q5D1D[$i]."','".
							$Q5D1E[$i]."','".
							$Q5D1F[$i]."','".
							$Q5D1G[$i]."','".
							$Q5D1H[$i]."','".
							$Q5D1I[$i]."','".
							$Q5D1J[$i]."','".
							$Q5D1K[$i]."','".
							$Q5D1L[$i]."','".
							$Q5D1M[$i]."','".
							$Q5D2A[$i]."','".
							$Q5D2B[$i]."','".
							$Q5D2C[$i]."','".
							$Q5D2D[$i]."','".
							$Q5D2E[$i]."','".
							$Q5D2F[$i]."','".
							$Q5D2G[$i]."','".
							$Q5D2H[$i]."','".
							$Q5D2I[$i]."','".
							$Q5D2J[$i]."','".
							$Q5D2K[$i]."','".
							$Q5D2L[$i]."','".
							$Q5D2M[$i]."','".
							$Q5D1MOth[$i]."','".
							$Q5D2MOth[$i]."','".
							$Q5E1[$i]."','".
							$Q5E2[$i]."','".
							$Q5E3[$i]."','".
							$Q5E4[$i]."','".
							$Q5E5[$i]."','".
							$Q5E6[$i]."','".
							$Q5E7[$i]."','".
							$Q5E7Oth[$i]."','".
							$Q5F1[$i]."','".
							$Q5F2A[$i]."','".
							$Q5F2B[$i]."','".
							$Q5F2C[$i]."','".
							$Q5F2D[$i]."','".
							$Q5F2E[$i]."','".
							$Q5F2F[$i]."','".
							$Q5G1[$i]."','".
							$Q5G2[$i]."','".
							$Q5G3[$i]."','".
							$Q5G4[$i]."','".
							$Q5G5[$i]."','".
							$Q5G6[$i]."','".
							$Q5G7[$i]."','".
							$Q5G8[$i]."','".
							$Q5G9[$i]."','".
							$Q5G10[$i]."','".
							$Q5G11[$i]."','".
							$Q5G12[$i]."','".
							$Q5G12Oth[$i]."','".
							$Q5H[$i]."','".
							$Q5HMT500[$i]."','".
							$Q5I1[$i]."','".
							$Q5I2[$i]."','".
							$Q5J[$i]."','".
							$Q6A1[$i]."','".
							$Q6A2[$i]."','".
							$Q6A3[$i]."','".
							$Q6A4[$i]."','".
							$Q6A5[$i]."','".
							$Q6A6[$i]."','".
							$Q6A7[$i]."','".
							$Q6A8[$i]."','".
							$Q6A9[$i]."','".
							$Q6A10[$i]."','".
							$Q6A11[$i]."','".
							$Q6A12[$i]."','".
							$Q6A13[$i]."','".
							$Q6A14[$i]."','".
							$Q6A14Oth[$i]."','".
							$Q6B1[$i]."','".
							$Q6B3[$i]."','".
							$Q6B2[$i]."','".
							$Q7A1[$i]."','".
							$Q7A2[$i]."','".
							$Q7A3[$i]."','".
							$Q7A4[$i]."','".
							$Q7A5[$i]."','".
							$Q7A6[$i]."','".
							$Q7A7[$i]."','".
							$Q7A8[$i]."','".
							$Q7A9[$i]."','".
							$Q7A10[$i]."','".
							$Q7A11[$i]."','".
							$Q7A12[$i]."','".
							$Q7A13[$i]."','".
							$Q7A14[$i]."','".
							$Q7A15[$i]."','".
							$Q7A16[$i]."','".
							$Q7B[$i]."','".
							$Q8A1[$i]."','".
							$Q8A2[$i]."','".
							$Q8A3[$i]."','".
							$Q8B1[$i]."','".
							$Q8B2[$i]."','".
							$Q8B3[$i]."','".
							$Q8C1[$i]."','".
							$Q8C2[$i]."','".
							$Q8C3[$i]."','".
							$Q8C4[$i]."','".
							$Q8D[$i]."','".
							$Q8E[$i]."','".
							$Q8F[$i]."','".
							$Q8G1[$i]."','".
							$Q8G2[$i]."','".
							$Q8H[$i]."','".
							$Q8I[$i]."','".
							$Q9A1A[$i]."','".
							$Q9A1B[$i]."','".
							$Q9A2A[$i]."','".
							$Q9A2B[$i]."','".
							$Q9B1A[$i]."','".
							$Q9B1B[$i]."','".
							$Q9B2A[$i]."','".
							$Q9B2B[$i]."','".
							$Q9C1[$i]."','".
							$Q9C2[$i]."','".
							$Q9C3[$i]."','".
							$Q9C4[$i]."','".
							$Q9D1[$i]."','".
							$Q9D2[$i]."','".
							$Q9E[$i]."','".
							$Q9F[$i]."','".
							$Q10A[$i]."','".
							$Q11[$i]."','".
							$Q11A1[$i]."','".
							$Q11A2[$i]."','".
							$Q11B1[$i]."','".
							$Q11B2[$i]."','".
							$Q11C1[$i]."','".
							$Q11C2[$i]."','".
							$Q11C3[$i]."','".
							$Q11C4[$i]."','".
							$Q11C5[$i]."','".
							$Q11D1[$i]."','".
							$Q11D2[$i]."','".
							$Q11D3[$i]."','".
							$Q11D4[$i]."','".
							$Q11D5[$i]."','".
							$Q11E1[$i]."','".
							$Q11E2[$i]."','".
							$Q11E3[$i]."','".
							$Q11E4[$i]."','".
							$Q11F[$i]."','".
							$Q11G[$i]."','".
							$Q11H1A[$i]."','".
							$Q11H1B[$i]."','".
							$Q11H1C[$i]."','".
							$Q11H2[$i]."','".
							$Q1B2B4[$i]."','".
							$Q1B2B1[$i]."','".
							$Q1B2B2[$i]."','".
							$Q1B2C[$i]."','".
							$Q1B2D[$i]."','".
							$Q1B2E1[$i]."','".
							$Q1B2E2[$i]."','".
							$Q1B2E3[$i]."','".
							$Q1B2E4[$i]."','".
							$Q1B2E5[$i]."','".
							$Q1B2F1[$i]."','".
							$Q1B2F2[$i]."','".
							$Q1B2F3[$i]."','".
							$Q1B2F4[$i]."','".
							$Q1B2F5[$i]."','".
							$Q1B2G2[$i]."','".
							$Q1BG1TaxPrprr[$i]."','".
							$Q1BG1IssueScrts[$i]."','".
							$Q1BG1XcldPoolInvmt[$i]."','".
							$Q1BG1PoolInvmt[$i]."','".
							$Q1BG1ReAdvsr[$i]."','".
							$Q1B2HScrtsNvsmt[$i]."','".
							$Q1B2HNScrtsNvsmt[$i]."','".
							$Q1B2HScrtsNvsmtAm[$i]."','".
							$Q1B2HNScrtsNvsmtAm[$i]."','".
							$Q1B2I1[$i]."','".
							$Q1B2I1A[$i]."','".
							$Q1B2I1B[$i]."','".
							$Q1B2I1C[$i]."','".
							$Q1B2I2Ai[$i]."','".
							$Q1B2I2B[$i]."','".
							$Q1B2I3[$i]."','".
							$Q1B2I2AiiAtrny[$i]."','".
							$Q1B2I2AiiCpa[$i]."','".
							$Q1B2I2AiiOth[$i]."','".
							$Q1B2I2AiiOthTx[$i]."','".
							$Q1BJ1A[$i]."','".
							$Q1BJ1B[$i]."','".
							$Q1BJ2A[$i]."','".
							$Q1BJ2BCfp[$i]."','".
							$Q1BJ2BCfa[$i]."','".
							$Q1BJ2BChfc[$i]."','".
							$Q1BJ2BCic[$i]."','".
							$Q1BJ2BPfs[$i]."','".
							$Q1BJ2BNone[$i]."','".
							$Q1B2K1[$i]."','".
							$Q1B2K2[$i]."'),";
			$queryIt="INSERT INTO stateADV values ".substr($query,0,-1).";";
			$q=mysqli_query($con,$queryIt) or die(mysqli_Error($con));
			$query="";
		}
		else
		{
			$query.="('".$FirmCrdNb[$i]."','".
			$SECNb[$i]."','".
			$BusNm[$i]."','".
			$LegalNm[$i]."','".
			$MainAddr_Strt1[$i]."','".
			$MainAddr_City[$i]."','".
			$MainAddr_State[$i]."','".
			$MainAddr_Cntry[$i]."','".
			$MainAddr_PostlCd[$i]."','".
			$MainAddr_PhNb[$i]."','".
			$MainAddr_FaxNb[$i]."','".
			$MainAddr_Strt2[$i]."','".
			$MailingAddr_Strt1[$i]."','".
			$MailingAddr_City[$i]."','".
			$MailingAddr_State[$i]."','".
			$MailingAddr_Cntry[$i]."','".
			$MailingAddr_PostlCd[$i]."','".
			$MailingAddr_Strt2[$i]."','".
			$Filing_Dt[$i]."','".
			$FormVrsn[$i]."','".
			$Q1I[$i]."','".
			$Q1M[$i]."','".
			$Q1N[$i]."','".
			$Q1O[$i]."','".
			$Q1P[$i]."','".
			$CIKNb[$i]."','".
			$OrgFormNm[$i]."','".
			$OrgFormOthNm[$i]."','".
			$Q3B[$i]."','".
			$StateCD[$i]."','".
			$CntryNm[$i]."','".
			$TtlEmp[$i]."','".
			$Q5B1[$i]."','".
			$Q5B2[$i]."','".
			$Q5B3[$i]."','".
			$Q5B4[$i]."','".
			$Q5B5[$i]."','".
			$Q5B6[$i]."','".
			$Q5C1[$i]."','".
			$Q5C1Oth[$i]."','".
			$Q5C2[$i]."','".
			$Q5D1A[$i]."','".
			$Q5D1B[$i]."','".
			$Q5D1C[$i]."','".
			$Q5D1D[$i]."','".
			$Q5D1E[$i]."','".
			$Q5D1F[$i]."','".
			$Q5D1G[$i]."','".
			$Q5D1H[$i]."','".
			$Q5D1I[$i]."','".
			$Q5D1J[$i]."','".
			$Q5D1K[$i]."','".
			$Q5D1L[$i]."','".
			$Q5D1M[$i]."','".
			$Q5D2A[$i]."','".
			$Q5D2B[$i]."','".
			$Q5D2C[$i]."','".
			$Q5D2D[$i]."','".
			$Q5D2E[$i]."','".
			$Q5D2F[$i]."','".
			$Q5D2G[$i]."','".
			$Q5D2H[$i]."','".
			$Q5D2I[$i]."','".
			$Q5D2J[$i]."','".
			$Q5D2K[$i]."','".
			$Q5D2L[$i]."','".
			$Q5D2M[$i]."','".
			$Q5D1MOth[$i]."','".
			$Q5D2MOth[$i]."','".
			$Q5E1[$i]."','".
			$Q5E2[$i]."','".
			$Q5E3[$i]."','".
			$Q5E4[$i]."','".
			$Q5E5[$i]."','".
			$Q5E6[$i]."','".
			$Q5E7[$i]."','".
			$Q5E7Oth[$i]."','".
			$Q5F1[$i]."','".
			$Q5F2A[$i]."','".
			$Q5F2B[$i]."','".
			$Q5F2C[$i]."','".
			$Q5F2D[$i]."','".
			$Q5F2E[$i]."','".
			$Q5F2F[$i]."','".
			$Q5G1[$i]."','".
			$Q5G2[$i]."','".
			$Q5G3[$i]."','".
			$Q5G4[$i]."','".
			$Q5G5[$i]."','".
			$Q5G6[$i]."','".
			$Q5G7[$i]."','".
			$Q5G8[$i]."','".
			$Q5G9[$i]."','".
			$Q5G10[$i]."','".
			$Q5G11[$i]."','".
			$Q5G12[$i]."','".
			$Q5G12Oth[$i]."','".
			$Q5H[$i]."','".
			$Q5HMT500[$i]."','".
			$Q5I1[$i]."','".
			$Q5I2[$i]."','".
			$Q5J[$i]."','".
			$Q6A1[$i]."','".
			$Q6A2[$i]."','".
			$Q6A3[$i]."','".
			$Q6A4[$i]."','".
			$Q6A5[$i]."','".
			$Q6A6[$i]."','".
			$Q6A7[$i]."','".
			$Q6A8[$i]."','".
			$Q6A9[$i]."','".
			$Q6A10[$i]."','".
			$Q6A11[$i]."','".
			$Q6A12[$i]."','".
			$Q6A13[$i]."','".
			$Q6A14[$i]."','".
			$Q6A14Oth[$i]."','".
			$Q6B1[$i]."','".
			$Q6B3[$i]."','".
			$Q6B2[$i]."','".
			$Q7A1[$i]."','".
			$Q7A2[$i]."','".
			$Q7A3[$i]."','".
			$Q7A4[$i]."','".
			$Q7A5[$i]."','".
			$Q7A6[$i]."','".
			$Q7A7[$i]."','".
			$Q7A8[$i]."','".
			$Q7A9[$i]."','".
			$Q7A10[$i]."','".
			$Q7A11[$i]."','".
			$Q7A12[$i]."','".
			$Q7A13[$i]."','".
			$Q7A14[$i]."','".
			$Q7A15[$i]."','".
			$Q7A16[$i]."','".
			$Q7B[$i]."','".
			$Q8A1[$i]."','".
			$Q8A2[$i]."','".
			$Q8A3[$i]."','".
			$Q8B1[$i]."','".
			$Q8B2[$i]."','".
			$Q8B3[$i]."','".
			$Q8C1[$i]."','".
			$Q8C2[$i]."','".
			$Q8C3[$i]."','".
			$Q8C4[$i]."','".
			$Q8D[$i]."','".
			$Q8E[$i]."','".
			$Q8F[$i]."','".
			$Q8G1[$i]."','".
			$Q8G2[$i]."','".
			$Q8H[$i]."','".
			$Q8I[$i]."','".
			$Q9A1A[$i]."','".
			$Q9A1B[$i]."','".
			$Q9A2A[$i]."','".
			$Q9A2B[$i]."','".
			$Q9B1A[$i]."','".
			$Q9B1B[$i]."','".
			$Q9B2A[$i]."','".
			$Q9B2B[$i]."','".
			$Q9C1[$i]."','".
			$Q9C2[$i]."','".
			$Q9C3[$i]."','".
			$Q9C4[$i]."','".
			$Q9D1[$i]."','".
			$Q9D2[$i]."','".
			$Q9E[$i]."','".
			$Q9F[$i]."','".
			$Q10A[$i]."','".
			$Q11[$i]."','".
			$Q11A1[$i]."','".
			$Q11A2[$i]."','".
			$Q11B1[$i]."','".
			$Q11B2[$i]."','".
			$Q11C1[$i]."','".
			$Q11C2[$i]."','".
			$Q11C3[$i]."','".
			$Q11C4[$i]."','".
			$Q11C5[$i]."','".
			$Q11D1[$i]."','".
			$Q11D2[$i]."','".
			$Q11D3[$i]."','".
			$Q11D4[$i]."','".
			$Q11D5[$i]."','".
			$Q11E1[$i]."','".
			$Q11E2[$i]."','".
			$Q11E3[$i]."','".
			$Q11E4[$i]."','".
			$Q11F[$i]."','".
			$Q11G[$i]."','".
			$Q11H1A[$i]."','".
			$Q11H1B[$i]."','".
			$Q11H1C[$i]."','".
			$Q11H2[$i]."','".
			$Q1B2B4[$i]."','".
			$Q1B2B1[$i]."','".
			$Q1B2B2[$i]."','".
			$Q1B2C[$i]."','".
			$Q1B2D[$i]."','".
			$Q1B2E1[$i]."','".
			$Q1B2E2[$i]."','".
			$Q1B2E3[$i]."','".
			$Q1B2E4[$i]."','".
			$Q1B2E5[$i]."','".
			$Q1B2F1[$i]."','".
			$Q1B2F2[$i]."','".
			$Q1B2F3[$i]."','".
			$Q1B2F4[$i]."','".
			$Q1B2F5[$i]."','".
			$Q1B2G2[$i]."','".
			$Q1BG1TaxPrprr[$i]."','".
			$Q1BG1IssueScrts[$i]."','".
			$Q1BG1XcldPoolInvmt[$i]."','".
			$Q1BG1PoolInvmt[$i]."','".
			$Q1BG1ReAdvsr[$i]."','".
			$Q1B2HScrtsNvsmt[$i]."','".
			$Q1B2HNScrtsNvsmt[$i]."','".
			$Q1B2HScrtsNvsmtAm[$i]."','".
			$Q1B2HNScrtsNvsmtAm[$i]."','".
			$Q1B2I1[$i]."','".
			$Q1B2I1A[$i]."','".
			$Q1B2I1B[$i]."','".
			$Q1B2I1C[$i]."','".
			$Q1B2I2Ai[$i]."','".
			$Q1B2I2B[$i]."','".
			$Q1B2I3[$i]."','".
			$Q1B2I2AiiAtrny[$i]."','".
			$Q1B2I2AiiCpa[$i]."','".
			$Q1B2I2AiiOth[$i]."','".
			$Q1B2I2AiiOthTx[$i]."','".
			$Q1BJ1A[$i]."','".
			$Q1BJ1B[$i]."','".
			$Q1BJ2A[$i]."','".
			$Q1BJ2BCfp[$i]."','".
			$Q1BJ2BCfa[$i]."','".
			$Q1BJ2BChfc[$i]."','".
			$Q1BJ2BCic[$i]."','".
			$Q1BJ2BPfs[$i]."','".
			$Q1BJ2BNone[$i]."','".
			$Q1B2K1[$i]."','".
			$Q1B2K2[$i]."'),";
		}

	}

	$queryIt="INSERT INTO stateADV values ".substr($query,0,-1).";";

	$q=mysqli_query($con,$queryIt) or die(mysqli_Error($con));

	//WebAddress

	$query="";

	for($i=0;$i<count($FirmCrdNb);$i++)
	{	
		for($j=0;$j<count(@$WebAddress[$i]);$j++)
		{	
			if($i%5000==0)
			{		
				echo "WebAddress".$i."\n";

				$query.="('".$FirmCrdNb[$i]."','".$WebAddress[$i][$j]."'),";		
				$queryIt="INSERT INTO stateADVWeb values ".substr($query,0,-1).";";
				$q=mysqli_query($con,$queryIt) or die(mysqli_Error($con)."TP");
				$query="";
			}
			else
			{
				$query.="('".$FirmCrdNb[$i]."','".$WebAddress[$i][$j]."'),";		
			}
		}
	}
	$queryIt="INSERT INTO stateADVWeb values ".substr($query,0,-1).";";

	$q=mysqli_query($con,$queryIt) or die(mysqli_Error($con)."ThomasH");
	echo "Thomas";
	//stateADVStates

	$query="";
	for($i=0;$i<count($FirmCrdNb);$i++)
	{
		for($j=0;$j<count(@$CdS[$i]);$j++)
		{
			if($i%5000==0)
			{			
				echo "stateADVStates".$i."\n";
				$query.="('".$FirmCrdNb[$i]."','".$CdS[$i][$j]."','".$StS[$i][$j]."','".$DtS[$i][$j]."'),";		
				$queryIt="INSERT INTO stateADVStates values ".substr($query,0,-1).";";
				$q=mysqli_query($con,$queryIt) or die(mysqli_Error($con)."Firsrt");
				$query="";
			}
			else
			{
				$query.="('".$FirmCrdNb[$i]."','".$CdS[$i][$j]."','".$StS[$i][$j]."','".$DtS[$i][$j]."'),";		
			}
		}
	}

	if(strlen($query)>7)
	{
			$queryIt="INSERT INTO stateADVStates values ".substr($query,0,-1).";";
			$q2=mysqli_query($con,$queryIt) or die(mysqli_Error($con).$queryIt);
	}


	//stateADVERA

	$query="";
	for($i=0;$i<count($FirmCrdNb);$i++)
	{
		for($j=0;$j<count(@$CdE[$i]);$j++)
		{
			if($i%5000==0)
			{	
				echo "stateADVERA".$i."\n";					
				$query.="('".$FirmCrdNb[$i]."','".$CdE[$i][$j]."','".$StE[$i][$j]."','".$DtE[$i][$j]."'),";		
				$queryIt="INSERT INTO stateADVERA values ".substr($query,0,-1).";";
				$q=mysqli_query($con,$queryIt) or die(mysqli_Error($con)."fi");
				$query="";
			}
			else
			{
				$query.="('".$FirmCrdNb[$i]."','".$CdE[$i][$j]."','".$StE[$i][$j]."','".$DtE[$i][$j]."'),";		
			}
		}
	}
	if(strlen($query)>7)
	{
		$queryIt="INSERT INTO stateADVERA values ".substr($query,0,-1).";";

		$q3=mysqli_query($con,$queryIt) or die(mysqli_Error($con)."se");	
	}

}


loadXML('IA_FIRM_STATE_Feed_02_23_2016.xml');
?>
