-- user
create user 'server_user'@'localhost' identified by 'server_user';
grant all privileges on *.* to 'server_user'@'localhost';


-- database schema
drop database if exists onboard;
create database if not exists onboard;
use onboard;