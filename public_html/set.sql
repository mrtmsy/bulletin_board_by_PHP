create database db; 

use db;

#テーブルを作成する
create table members(id int not null auto_increment,
                  name varchar(255),
                  email varchar(255),
                  password varchar(100),
                  picture varchar(255),
                  created datetime,
                  modified timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
                  primary key(id)
);

create table posts (id int not null auto_increment,
                  message text,
                  member_id int,
                  reply_message_id int default 0,
                  created datetime,
                  modified timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
                  primary key(id)
);

insert into members values
('1','name','email@mail','password',null,'20210625000000','2021-06-25 00:00:00');

insert into posts values
('1','message','1',null,'20210625000000','2021-06-25 00:00:00');

