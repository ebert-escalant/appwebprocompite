create database dbprocompite

create table users (
  id bigint(20) unsigned not null auto_increment,
  name varchar(255) not null,
  email varchar(255) unique not null,
  rol varchar(255) not null,
  password varchar(255) not null,
  remember_token varchar(100) default null,
  created_at timestamp null default null,
  updated_at timestamp null default null,
  primary key (id)
) engine = innodb;

create table sessions (
  id varchar(255) not null,
  user_id bigint(20) unsigned default null,
  ip_address varchar(45) default null,
  user_agent text default null,
  payload longtext not null,
  last_activity int(11) not null,
  primary key (id),
  key sessions_user_id_index (user_id),
  key sessions_last_activity_index (last_activity)
) engine = innodb;

create table projects (
	id char(13) primary key not null,
	code varchar(10) unique not null,
	name varchar(700) not null,
	category varchar(255) not null,
	investment_amount double not null,
	cofinance_amount double not null,
	created_at timestamp null default null,
  	updated_at timestamp null default null
) engine = innodb;

create table partners (
	id char(13) primary key not null,
	dni char(8) unique not null,
	full_name varchar(500) not null,
	birthdate date,
	phone varchar(13) not null,
	addres varchar(255) not null,
	email varchar(255) not null,
	family_charge varchar(50) not null,
	charge varchar(50) not null,
	created_at timestamp null default null,
  	updated_at timestamp null default null
) engine = innodb;

create table societies (
	id char(13) primary key not null,
	id_partner char(13) not null,
	type varchar(30) not null,
	social_razon varchar(255) not null,
	ruc char(11) not null,
	constitution_date date not null,
	part_number bigint not null,
	district varchar(255) not null,
	province varchar(255) not null,
	department varchar(255) not null,
	comunity varchar(255) not null,
	address varchar(255) not null,
	phone varchar(13) not null,
	latitude varchar(255) not null,
	longitude varchar(255) not null,
	created_at timestamp null default null,
  	updated_at timestamp null default null,
	foreign key (id_partner) REFERENCES partners(id)
) engine = innodb;

create table society_project (
	id char(13) primary key not null,
	society_id char(13) not null,
	project_id char(13) not null,
	year int not null,
	assets json,
	qualification varchar(50),
	foreign key (society_id) references societies(id),
	foreign key (project_id) references projects(id),
	constraint unique (society_id, year, project_id)
) engine = innodb;

create table partner_society_project (
	id char(13) primary key not null,
	society_project_id char(13) not null,
	partner_id char(13) not null,
	assets json,
	created_at timestamp null default null,
  	updated_at timestamp null default null,
	foreign key (society_project_id) references society_project(id),
	foreign key (partner_id) references partners(id),
	constraint unique (society_project_id, partner_id)
) engine = innodb;