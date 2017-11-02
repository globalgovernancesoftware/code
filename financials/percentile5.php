<?PHP

ini_set("memory_limit","-1");
header('Content-Type: text/html; charset=utf-8');
include '../connection/simple_html_dom.php';
//include 'parseComp2.php';
//include "extractTable.php";
include '../connection/mysqliConnect.php';

$query="";
for($i=0;$i<101;$i++)
{
  $per=$i/100;
  $query.=" 
  SELECT result as mainReturnPerc, ".$per." as `percentile` FROM (
SELECT t1.`value` AS 'result' FROM
(
  SELECT @row:=@row+1 as `row`, x.returnPerc as `value`
  FROM (select * from (select (close-pastClose)/pastClose as returnPerc,  symbol, source from (select * from novaEOD.mainEOD where date=20151104 and (source='NYSE' or source='NASDAQ')) as t
left join (
select close as pastClose,symbol as pastSymbol,source as pastSource,date as pastDate from novaEOD.mainEOD 
where date=(select date from novaEOD.mainEOD where date>20141104 order by date asc limit 1)) as t2 on pastSymbol=symbol and pastSource=source) as tSS where returnPerc is not null) as x , (SELECT @row:=0) AS r
  WHERE 1
  -- add your where clause here
  ORDER BY x.returnPerc
) AS t1,
(
  SELECT COUNT(*) as 'count'
  FROM (select * from (select (close-pastClose)/pastClose as returnPerc,  symbol, source from (select * from novaEOD.mainEOD where date=20151104 and (source='NYSE' or source='NASDAQ')) as t
left join (
select close as pastClose,symbol as pastSymbol,source as pastSource,date as pastDate from novaEOD.mainEOD 
where date=(select date from novaEOD.mainEOD where date>20141104 order by date asc limit 1)) as t2 on pastSymbol=symbol and pastSource=source) as tSS where returnPerc is not null) as x 
  WHERE 1
  -- add your where clause here
) AS t2
WHERE t1.row >= t2.count*".$per." LIMIT 1) AS t3
    union";
}
$query=substr($query,0,-5);


$queryIt=mysqli_Query($con,$query);

while($row=mysqli_fetch_Array($queryIt))
{
  $data[]=$row;
}

$query='select * from (
select * from (select (close-pastClose)/pastClose as returnPerc,  symbol, source from (select * from novaEOD.mainEOD where date=20151104 and (source="NYSE" or source="NASDAQ")) as t
left join (
select close as pastClose,symbol as pastSymbol,source as pastSource,date as pastDate from novaEOD.mainEOD 
where date=(select date from novaEOD.mainEOD where date>20141104 order by date asc limit 1)) as t2 on pastSymbol=symbol and pastSource=source) as tSS where returnPerc is not null) as tMaster';


$queryIt=mysqli_query($con,$query);

while($row=mysqli_fetch_Array($queryIt))
{
  $data2[]=$row;
}

$countIt=count($data2);

for($i=0;$i<$countIt-1;$i++)
{
  if($data2[$i]['returnPerc']==$data[0]['mainReturnPerc'])
  {
    $finalP['relativePercentile'][]=1;
    $finalP['symbol'][]=$data2[$i]['symbol'];
    $finalP['source'][]=$data2[$i]['source'];
  }
  else
  {
    for($j=1;$j<count($data);$j++)
    {
      if($data2[$i]['returnPerc']<$data[$j]['mainReturnPerc'] && $data2[$i]['returnPerc']>=$data[$j-1]['mainReturnPerc'])
      {
        $finalP['relativePercentile'][]=(($data2[$i]['returnPerc']-$data[$j]['mainReturnPerc'])/($data[$j-1]['mainReturnPerc']-$data[$j]['mainReturnPerc']))*(($data[$j-1]['percentile']-$data[$j]['percentile']))+$data[$j]['percentile'];
        $finalP['symbol'][]=$data2[$i]['symbol'];
        $finalP['source'][]=$data2[$i]['source'];
      }    
    }    
  }
  echo $i."\n";
}


$query="";
for($i=0;$i<101;$i++)
{
  $per=$i/100;
  $query.=" 
SELECT result as mainReturnPerc, ".$per." as `percentile` FROM (
SELECT t1.`value` AS 'result' FROM
(
  SELECT @row:=@row+1 as `row`, x.total as `value`
  FROM (select * from (select * from (select *  from nsMine.mineSummaryComp where length(companyId)=9 and compYear=2014 order by urlS desc) as t group by execId order by total desc) as t2 group by companyId) AS x, (SELECT @row:=0) AS r
  WHERE 1
  -- add your where clause here
  ORDER BY x.total
) AS t1,
(
  SELECT COUNT(*) as 'count'
  FROM (select * from (select * from (select *  from nsMine.mineSummaryComp where length(companyId)=9 and compYear=2014 order by urlS desc) as t group by execId order by total desc) as t2 group by companyId) as x
  WHERE 1
  -- add your where clause here
) AS t2
WHERE t1.row >= t2.count*".$per." LIMIT 1) AS t3
    union";
}
$query2=substr($query,0,-5);


$queryIt2=mysqli_Query($con,$query2);

while($row=mysqli_fetch_Array($queryIt2))
{
  $data3[]=$row;
}


$query4='select * from (select companyId,execId,total from (select * from (select *  from nsMine.mineSummaryComp where length(companyId)=9 and compYear=2014 order by urlS desc) as t group by execId order by total desc) as t2 group by companyId) as tF
left join nsMine.cikTicker on companyId=lpad(CIK,"9","0") where CIK is not null;';

$queryIt4=mysqli_Query($con,$query4);

while($row=mysqli_fetch_Array($queryIt4))
{
  $data4[]=$row;
}

$countIt=count($data4);

for($i=0;$i<$countIt;$i++)
{
  if($data4[$i]['total']==$data3[0]['mainReturnPerc'])
  {
    $finalP2['relativePercentile'][]=1;
        $finalP2['companyId'][]=$data4[$i]['companyId'];
        $finalP2['execId'][]=$data4[$i]['execId'];
        $finalP2['Ticker'][]=$data4[$i]['Ticker'];
        $finalP2['exchangeName'][]=$data4[$i]['exchangeName'];  
  }
  else
  {
    for($j=1;$j<count($data);$j++)
    {
      if($data4[$i]['total']<$data3[$j]['mainReturnPerc'] && $data4[$i]['total']>=$data3[$j-1]['mainReturnPerc'])
      {
        $finalP2['relativePercentile'][]=(($data4[$i]['total']-$data3[$j]['mainReturnPerc'])/($data3[$j-1]['mainReturnPerc']-$data3[$j]['mainReturnPerc']))*(($data3[$j-1]['percentile']-$data3[$j]['percentile']))+$data3[$j]['percentile'];
        $finalP2['companyId'][]=$data4[$i]['companyId'];
        $finalP2['execId'][]=$data4[$i]['execId'];
        $finalP2['Ticker'][]=$data4[$i]['Ticker'];
        $finalP2['exchangeName'][]=$data4[$i]['exchangeName'];  
      }    
    }    
  }
  echo $i."\n";
}

echo "dataAggregated";
$countFinal2=count($finalP2['relativePercentile']);
$values="";
for($i=0;$i<$countFinal2;$i++)
{
  $values.="('".$finalP2['execId'][$i]."','".$finalP2['companyId'][$i]."','".$finalP2['Ticker'][$i]."','".$finalP2['exchangeName'][$i]."','".$finalP2['relativePercentile'][$i]."'),";
  if($i%500 ===0){
    echo $i."\n";
  }
}

$query="insert into nsMine.payForPerformance (execId,companyId,ticker,exchangeName,percentileCompensation) values ".substr($values,0,-1);
mysqli_query($con,$query) or die(mysqli_error($con));

for($i=0;$i<count($finalP['relativePercentile']);$i++)
{
  $query="update nsMine.payForPerformance set percentilePerformance=".$finalP['relativePercentile'][$i]." where ticker='".$finalP['symbol'][$i]."' and exchangeName='".$finalP['source'][$i]."';";

  mysqli_query($con,$query) or die(mysqli_error($con));
    if($i%500==0){
    echo $i."\n";
  }
}

?>