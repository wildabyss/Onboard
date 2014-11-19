-- user
grant usage on *.* to 'server_user'@'localhost';
drop user 'server_user'@'localhost';
create user 'server_user'@'localhost' identified by 'server_user';
grant all privileges on *.* to 'server_user'@'localhost';


-- database schema
drop database if exists onboard;
create database if not exists onboard;
use onboard;


-- base tables

create table user (
	id int unsigned not null auto_increment,
	fb_id varchar(22),
	google_id varchar(22),
	status tinyint unsigned not null,
	date_joined datetime not null,
	display_name varchar(255),
	email varchar(255),
	phone varchar(25),
	primary key (id)
) engine=InnoDB;

create table activity (
	id int unsigned not null auto_increment,
	name varchar(255),
	primary key (id)
) engine=InnoDB;

create table category (
	id mediumint unsigned not null auto_increment,
	name varchar(255),
	primary key (id)
) engine=InnoDB;

create table activity_list (
	id int unsigned not null auto_increment,
	name varchar(255),
	user_id int unsigned not null,
	primary key (id),
	foreign key (user_id) references user(id)
		on delete cascade
) engine=InnoDB;

create table discussion (
	id int unsigned not null auto_increment,
	name varchar(255),
	activity_id int unsigned not null,
	owner_id int unsigned not null,
	status tinyint unsigned not null,
	date_created datetime not null,
	file_name varchar(255) not null,
	primary key (id),
	foreign key (activity_id) references activity(id)
		on delete cascade,
	foreign key (owner_id) references user(id)
		on delete cascade
) engine=InnoDB;

-- association tables

create table user_community_assoc (
	id bigint unsigned not null auto_increment,
	user_id_left int unsigned not null,
	user_id_right int unsigned not null,
	primary key (id),
	foreign key (user_id_left) references user(id)
		on delete cascade,
	foreign key (user_id_right) references user(id)
		on delete cascade
) engine=InnoDB;

create table activity_user_assoc (
	id bigint unsigned not null auto_increment,
	activity_id int unsigned not null,
	user_id int unsigned not null,
	list_id int unsigned not null,
	status tinyint unsigned not null,
	date_added datetime not null,
	alias varchar(255),
	description varchar(255),
	primary key (id),
	foreign key (activity_id) references activity(id)
		on delete cascade,
	foreign key (user_id) references user(id)
		on delete cascade,
	foreign key (list_id) references activity_list(id)
		on delete cascade
) engine=InnoDB;

create table activity_category_assoc (
	id bigint unsigned not null auto_increment,
	activity_id int unsigned not null,
	category_id mediumint unsigned not null,
	primary key (id),
	foreign key (activity_id) references activity(id)
		on delete cascade,
	foreign key (category_id) references category(id)
		on delete cascade
) engine=InnoDB;

create table discussion_user_assoc (
	id bigint unsigned not null auto_increment,
	discussion_id int unsigned not null,
	user_id int unsigned not null,
	primary key (id),
	foreign key (discussion_id) references discussion(id)
		on delete cascade,
	foreign key (user_id) references user(id)
		on delete cascade
) engine=InnoDB;