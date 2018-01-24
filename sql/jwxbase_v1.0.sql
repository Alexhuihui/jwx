drop database if exists jwxbase;
create database jwxbase;
use jwxbase;

CREATE TABLE tb_user
(
	userid bigint auto_increment PRIMARY KEY,
	telnum bigint NOT NULL,
	passwd varchar(32) NOT NULL,
	username varchar(64) NOT NULL,
	genderid bigint DEFAULT 1,
	address varchar(256) NOT NULL,
	roleid bigint DEFAULT 1,
	remark varchar(256),
	unique(telnum)
);

CREATE TABLE tb_machine
(
	machineid bigint auto_increment PRIMARY KEY,
	machinename varchar(64) NOT NULL,
	life bigint NOT NULL,
	unique(machinename)
);

CREATE TABLE tb_order
(
	orderid bigint auto_increment PRIMARY KEY,
	machineid bigint NOT NULL,
	userid bigint NOT NULL,
	buytime varchar(32) NOT NULL,
	changetime varchar(32) NOT NULL,
	constraint machine_fk foreign key(machineid) references tb_machine(machineid),
	constraint user_fk foreign key(userid) references tb_user(userid)
);

CREATE TABLE tb_gender
(
	genderid bigint auto_increment PRIMARY KEY,
	gender varchar(16) NOT NULL
);

CREATE TABLE tb_article
(
	articleid bigint auto_increment PRIMARY KEY,
	articlename varchar(64) NOT NULL,
	articleimg longblob,
	articlecontent varchar(1024) NOT NULL,
	createtime bigint not null,
	updatetime bigint not null
);

CREATE TABLE tb_role
(
	roleid bigint auto_increment PRIMARY KEY,
	rolename varchar(32) NOT NULL,
	remark varchar(256)
);

insert into tb_gender values (NULL, 'UNKNOWN');
insert into tb_gender values (NULL, 'MALE');
insert into tb_gender values (NULL, 'FEMALE');
insert into tb_role values (NULL, 'user', 'ordinary user');
insert into tb_role values (NULL, 'super', 'super role');
insert into tb_user values (NULL, '18397928308', 'jwxadmin', 'xieziheng', 1, "jiangxinanchang", "super role", 1);