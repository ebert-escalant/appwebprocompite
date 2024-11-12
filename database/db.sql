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