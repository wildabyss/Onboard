use onboard;

/* This script is only used to generate test data */

delete from user where id>0;
insert into user
	(fb_id, google_id, status, date_joined, display_name, email, phone)
values 
	(1, 1, 1, '2014-11-22 01:00:00', 'Jimmy Lu', 'wildabyss@gmail.com', '647-866-8908'),
    (1, 1, 1, '2014-11-23 01:00:00', 'Ken Li', 'ken.li.ut@gmail.com', '');
	
