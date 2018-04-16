use db1;

#统计当前月份用户签到次数

select count(user_count.count) as time_count from
  (select count(id) as count ,user_id
from user_sign
where month(created_at) = 4
group by user_id) user_count group by time_count;


#统计当前月份每天用户签到数据
select
  count(id),
  date(created_at)
from user_sign
where month(created_at) = 4
group by date(created_at);

#统计当前月份每天用户补签数据
select
  count(id),
  date(created_at)
from user_sign
where month(created_at) = 4 and is_resign=1
group by date(created_at);


select
  resign_at,
  count(id)        as count,
  date(created_at) as date
from `user_sign`
where month(`created_at`) = 4 and `is_resign` = 1
group by date;




select * from user_sign where month(created_at)=4;



