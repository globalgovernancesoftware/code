SELECT * FROM nsMine.cikTicker where exchangeName='nasdaq' or exchangeName='nyse';

select * from (select * from novaEOD.mainEOD where source='nasdaq' or source='nyse' group by symbol) as t;

set sql_Safe_updates=0;
delete payForPerformance.* from payForPerformance;
create table payForPerformance (execId bigint,companyId varchar(10),ticker varchar(10),exchangeName varchar(20), percentileCompensation decimal (4,3));
alter table payForPerformance  add percentilePerformance decimal (4,3);


select * from payForPerformance where percentilePerformance is not null;
