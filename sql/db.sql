create schema redakcni collate utf8mb4_0900_ai_ci;

create table article
(
	id int auto_increment
		primary key,
	content text null,
	name varchar(255) null,
	price varchar(255) null,
	date varchar(255) null,
	img varchar(255) null,
	count int null,
	time varchar(255) null,
	constraint name
		unique (name)
)
collate=utf8_czech_ci;

create table `order`
(
	id int auto_increment,
	order_article int not null,
	name varchar(255) null,
	surname varchar(255) null,
	phone varchar(255) null,
	address varchar(255) null,
	zip_code varchar(255) null,
	city varchar(255) null,
	country varchar(255) null,
	mail varchar(255) null,
	message varchar(255) null,
	date varchar(255) null,
	price int null,
	constraint id
		unique (id)
)
collate=utf8_czech_ci;

alter table `order`
	add primary key (id);

create table user
(
	id int auto_increment
		primary key,
	username varchar(255) not null,
	password varchar(60) not null,
	role enum('member', 'admin') default 'member' not null,
	constraint username
		unique (username)
)
collate=utf8_czech_ci;

