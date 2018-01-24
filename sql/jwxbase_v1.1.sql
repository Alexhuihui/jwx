drop database if exists jwxbase;
create database jwxbase;
use jwxbase;

CREATE TABLE tb_user
(
	userid bigint auto_increment PRIMARY KEY,
	createtime bigint NOT NULL,
	telnum bigint NOT NULL,
	passwd varchar(32) NOT NULL,
	username varchar(64) NOT NULL,
	genderid bigint DEFAULT 1,
	address varchar(256) NOT NULL,
	roleid bigint DEFAULT 0,
	remark varchar(256),
	unique(telnum)
);

CREATE TABLE tb_filter
(
	filterid bigint auto_increment PRIMARY KEY,
	filtername varchar(64) NOT NULL,
	life bigint NOT NULL,
	unique(filtername)
);

CREATE TABLE tb_machine
(
	machineid bigint auto_increment PRIMARY KEY,
	machinename varchar(64) NOT NULL,
	unique(machinename)
);

CREATE TABLE tb_order
(
	orderid bigint auto_increment PRIMARY KEY,
	machineid bigint NOT NULL,
	userid bigint NOT NULL,
	buytime bigint NOT NULL,
	filter1 bigint DEFAULT -1,
	changetime1 bigint NOT NULL,
	filter2 bigint DEFAULT -1,
	changetime2 bigint NOT NULL,
	filter3 bigint DEFAULT -1,
	changetime3 bigint NOT NULL,
	filter4 bigint DEFAULT -1,
	changetime4 bigint NOT NULL,
	filter5 bigint DEFAULT -1,
	changetime5 bigint NOT NULL,
	filter6 bigint DEFAULT -1,
	changetime6 bigint NOT NULL,
	foreign key(machineid) references tb_machine(machineid) on delete cascade,
	foreign key(userid) references tb_user(userid) on delete cascade
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
	articleintro varchar(64) NOT NULL,
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
insert into tb_user values (NULL, '12345678' ,'18397928308', 'jwxadmin', 'xieziheng', 1, "jiangxinanchang", "super role", 1);