--THIS PROJECT WAS CREATED BY PETR TALLER--

GCC: Global Chat Connect, connects users around the world in one chat
has one chat that holds 25 messages

this is what the datgabase looks like if it doesnt work:
"

create database WALogin;
use WALogin;

create table users (
    id int primary key auto_increment,
    username varchar(250),
    password varchar(250),
    profile_id int
);

"
