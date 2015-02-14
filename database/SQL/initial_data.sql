use onboard;

/* This script is only used to generate test data */

insert into category
	(name)
values
	('Arts'), ('Cinema'), ('Clubbing'), ('Craft'), ('Dancing'), ('Food'), ('Gaming'), ('Reading'), ('Shopping'),
    ('Sports'), ('Travel');

insert into enum
	(name, value)
values
	('fb_app_id', '0'), ('fb_app_secret', '0'), ('domain', '192.168.1.126');