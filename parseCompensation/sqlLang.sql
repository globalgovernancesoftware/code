use compData;
create table mainComp (id int not null auto_increment primary key, nameC varchar(300), yearC int(4), salaryC bigint(15), bonusC bigint(15), stockC bigint(15), optionC bigint(15), incentiveC bigint(15), pensionC bigint(15), otherC bigint(15), totalC bigint(15), sourceC varchar(100));
alter table mainComp add sourceC varchar(100);
create table docComp (idD int not null auto_increment primary key, docNameC varchar(100), problemC varchar(100));


delete mainComp.* from mainComp;
delete docComp.* from docComp;
drop table mainComp;
drop table docComp;
set sql_safe_updates=0;


select * from docComp where problemC not like '%table%' group by docNameC;
select * from mainComp group by sourceC;