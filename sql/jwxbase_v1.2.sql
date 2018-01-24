drop database if exists jwxbase;
create database jwxbase character set utf8 collate utf8_general_ci;
use jwxbase;

CREATE TABLE tb_role
(
	roleid bigint auto_increment PRIMARY KEY,
	rolename varchar(32) NOT NULL,
	remark varchar(256)
);

CREATE TABLE tb_gender
(
	genderid bigint auto_increment PRIMARY KEY,
	gender varchar(16) NOT NULL
);

CREATE TABLE tb_user
(
	userid bigint auto_increment PRIMARY KEY,
	createtime bigint NOT NULL,
	telnum bigint NOT NULL,
	passwd varchar(32) NOT NULL,
	username varchar(64) NOT NULL,
	genderid bigint DEFAULT 1,
	address varchar(256) NOT NULL,
	roleid bigint DEFAULT 1,
	remark varchar(256),
	unique(telnum)
);

CREATE TABLE tb_filter
(
	filterid bigint auto_increment PRIMARY KEY,
	filtername varchar(128) NOT NULL,
	life bigint NOT NULL,
	unique(filtername)
);

CREATE TABLE tb_machine
(
	machineid bigint auto_increment PRIMARY KEY,
	machinename varchar(128) NOT NULL,
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
	foreign key(userid) references tb_user(userid) on delete cascade
);

CREATE TABLE tb_article
(
	articleid bigint auto_increment PRIMARY KEY,
	articlename varchar(128) NOT NULL,
	articleimg longblob,
	articlecontent varchar(4096) NOT NULL,
	createtime bigint not null,
	updatetime bigint not null
);

insert into tb_gender values (NULL, 'UNKNOWN');
insert into tb_gender values (NULL, 'MALE');
insert into tb_gender values (NULL, 'FEMALE');
insert into tb_role values (NULL, 'user', 'ordinary user');
insert into tb_role values (NULL, 'super', 'super role');
insert into tb_user values (NULL, '0' ,'18579056029', 'jwxadmin', 'lisisi', 1, "jiangxinanchang", "super role", 2);