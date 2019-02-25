CREATE TABLE sa_user_type(
	type_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(250),
	menu varchar(250),
	status enum ('A', 'D'),
	create_date datetime
);


CREATE TABLE sa_building_wings(
	bldg_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	bldg_unit varchar(250),
	status enum ('A', 'D'),
	create_date datetime
);


CREATE TABLE sa_unit_category(
	uc_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	uc_name varchar(250),
	status enum ('A', 'D'),
	create_date datetime
);


CREATE TABLE sa_unit(
	unit_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	bldg_foreign_id int(11),
	unitc_foreign_id int(11),
	unit_name varchar(250),
	unit_measure varchar(250),
	status enum ('A', 'D'),
	create_date datetime
);


CREATE TABLE sa_member(
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	bldg_foreign_id int(11),
	unitc_foreign_id int(11),
	unit_foreign_id int(11),
	unit_foreign_id int(11),
	first_name varchar(250),
	middle_name varchar(250),
	last_name varchar(250),
	gender enum ('Male', 'Female'),
	DOB date,
	member_type varchar('Owner', 'Tenant', 'Owner Family', 'Tenant Family', 'Care Taker'),
	address varchar(250),
	api_key varchar(250),
	membership_id varchar(250),
	contact_person varchar(250),
	contact_email varchar(250),
	contact_phone varchar(250),
	status enum ('A', 'D'),
	key_used enum('Y', 'N'),
	create_date datetime
);

CREATE TABLE sa_account_plan(
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	account_id int(11),
	plan_id int(11),
	create_date datetime,
	expire_date date
);

CREATE TABLE sa_member_data(
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	member_id varchar(250),
	name varchar(250),
	email varchar(250),
	phone varchar(250),
	company_name varchar(250),
	designation varchar(250),
	date_of_birth date,
	anniversay_date date,
	relationship_status enum('Married', 'Unmarried', 'Widow', 'Divorced'),
	relationship_with varchar(250),
	relationship enum('Father', 'Mother', 'Wife', 'Husband', 'Son', 'Daughter', 'Uncle', 'Aunty', 'GrandParent')
);




