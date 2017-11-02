<?php
ini_set('memory_limit', '-1');
require_once("simple_html_dom.php");
include 'mysqliConnect.php';
//Check MaxDate

$queryUS="insert into nsMine.companyFiling (regulatoryId, companyName, source) 
select * FROM (SELECT lpad(cikE,10,'0'), compNameE,'edgar' FROM edgarDb.edgarTb group  by cikE) as t
ON DUPLICATE KEY UPDATE companyName=compNameE;";

$queryIt=mysqli_query($con,$queryUS);


$queryCDN="insert into nsMine.companyFiling (regulatoryId, companyName, source) 
select * FROM (SELECT lpad(id,8,'0'),compName,'sedar' FROM sedarf.listCompanies) as t
ON DUPLICATE KEY UPDATE companyName=compName;";

$queryIt2=mysqli_query($con,$queryCDN);



?>