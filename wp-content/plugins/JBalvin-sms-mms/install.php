<?php
global $table_prefix;

$create_sms_send = ("CREATE TABLE IF NOT EXISTS {$table_prefix}jbalvin_sms_send(
	ID int(10) NOT NULL auto_increment,
	type enum('sms','mms') NOT NULL DEFAULT 'mms',
	sender VARCHAR(20) NOT NULL,
	message TEXT NOT NULL,
	mms_file TEXT NOT NULL,
	recipient TEXT NOT NULL,
	date DATETIME,
	PRIMARY KEY(ID)) CHARSET=utf8
");
