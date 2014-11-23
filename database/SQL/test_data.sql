use onboard;

delete from user where id>0;

insert into user
	(fb_id, google_id, status, date_joined, display_name, email, phone)
values 
	(1, 1, 1, '2014-11-22 01:00:00', 'Jimmy Lu', 'wildabyss@gmail.com', '647-866-8908');
	
select * from user;