<?php
ini_set('memory_limit', '-1');

ini_set('max_execution_time', 0);
$host="novasharedb1.cbh0r72xry9r.us-west-2.rds.amazonaws.com"; // Host name 
$username="novaUser"; // Mysql username 
$password="Tyrose1214"; // Mysql password 

$con=mysqli_connect("$host","$username","$password","novaNSAR") or die("Could not connect");


$query="select typeOf, name as typeName, fundName, m2, sourceN, cik, dateRep from (
select * from(select *,ansN as typeOf, concat(sourceN,mid(midN,5,2),mid(midN,7,2)) as m1, concat(sourceN,mid(midN,5,2)) as m4 from masterNSAR 
where typeN='008' and ( midN like ' B00%') and ansN like '%A%' and mid(midN,5,2)<>'AA' and mid(midN,5,2)<>'00' ) as t 
left join ( select ansN as name, concat(sourceN,mid(midN,5,2),mid(midN,7,2)) as m2 from masterNSAR where typeN='008' and (midN like ' A00%') ) as tt on t.m1=tt.m2 
left join (select ansN as fundName, concat(sourceN,mid(midN,5,2)) as m3 from masterNSAR where typeN='007' and ( midN like ' C02%')) as tttt on m4=m3
inner join (select ansN as cik , sourceN as cikSource from masterNSAR where typeN='000' and ( midN like ' C00%')) as sourceTab1 on t.sourceN=sourceTab1.cikSource 
inner join (select ansN as dateRep , sourceN as dateSource from masterNSAR where typeN='000' and ( midN like ' A00%')) as dateTab1 on t.sourceN=dateTab1.dateSource 
) as ttt
 
union

select typeOf, name as typeName, fundName,  m2, sourceN, cik, dateRep from (
select * from(
select *,ansN as typeOf, concat(sourceN,mid(midN,5,2),mid(midN,7,2)) as m1, concat(sourceN,mid(midN,5,2)) as m4 from masterNSAR 
where typeN='008' and ( midN like ' B00%') and ansN like '%A%' and mid(midN,5,2)='AA') as t 
left join ( select ansN as name, concat(sourceN,mid(midN,5,2),mid(midN,7,2)) as m2 from masterNSAR where typeN='008' and (midN like ' A00%') ) as tt on t.m1=tt.m2 
inner join (select ansN as fundName, sourceN as fundSource from masterNSAR where typeN='007' and ( midN like ' C02%')) as tttt on sourceN=fundSource
inner join (select ansN as cik , sourceN  as cikSource from masterNSAR where typeN='000' and ( midN like ' C00%')) as sourceTab2 on t.sourceN=sourceTab2.cikSource
inner join (select ansN as dateRep , sourceN as dateSource from masterNSAR where typeN='000' and ( midN like ' A00%')) as dateTab2 on t.sourceN=dateTab2.dateSource 
) as ttttt ;";


echo "Querying Server..."."\n";
$queryIt=mysqli_query($con, $query);
echo "Server Queried..."."\n";

while($row=Mysqli_fetch_Array( $queryIt))
{
	print_r($row);
}

?>