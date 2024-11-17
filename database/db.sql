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

create table societies (
	id char(13) primary key not null,
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
	email varchar(255) not null,
	created_at timestamp null default null,
  	updated_at timestamp null default null
) engine = innodb;

create table project_society (
	id ,
	year int,
	society_id char(13),
	project_id char(13),
	assets json,
)

create table 

create table partners (
	id char(13) not primary key null,
	dni char(8) unique not null,
	full_name varchar(500) not null,
	birthdate date,
	phone varchar(13) not null,
	addres varchar(255) not null,
	family_charge varchar(50) not null,
	created_at timestamp null default null,
  	updated_at timestamp null default null
) engine = innodb;

create table associations (
	id char(13) primary key not null,
	society_id char(13) not null,
	partner_id char(13) not null,
	--project_id char(13) not null,
	year int,
	assets json,
	qualification varchar(50),
	foreign key (society_id) 
	constraint unique (society_id, year, partner_id)
) engine = innodb;

create table asset_services(
	id char(13) primary key not null
	association_id not null,
	type char(1) not null, --A => asset,
    name varchar(30) not null,
    description varchar (100) not null,
    adquisition_date datetime not null,
    status varchar(50), -- estado del servicio
    fileName varchar(20),
    fileName1 varchar(20),
    idPartner char(13),
    FOREIGN KEY (idPartner) REFERENCES socios(idPartner)
)engine=innodb;

create table assigned

create table associations(
	idAssociations char(13) primary key not null,
    year int,
    name varchar (1009),
	status varchar(50),
    description TEXT,
	created_at datetime,
    updated_at datetime
)engine=innodb;
create table tpartner (
	idPartner char(13) primary key not null,
    firstName varchar(20),
    lastName varchar(20),
    dni char (8),
    phone char (9),
    address varchar (30),
    familyCharge varchar (30),
    created_at datetime,
    updated_at datetime,
    FOREIGN KEY (idAssociations) REFERENCES asociaciones(idAssociations)
)engine=innodb;
create table asset_services(
	idAssetService char(13) primary key not null,
    name varchar(30),
    description varchar (100),
    adquisitionDate datetime,
    status varchar(50), -- estado del servicio
    fileName varchar(20),
    fileName1 varchar(20),
    idPartner char(13),
    FOREIGN KEY (idPartner) REFERENCES socios(idPartner)
)engine=innodb;