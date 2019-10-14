# This file is part of the "bmf_budget" Extension for TYPO3 CMS.
#
# For the full copyright and license information, please read the
# LICENSE.txt file that was distributed with this source code.

#
# Table structure for table 'tx_bmfbudget_domain_model_title'
#
CREATE TABLE tx_bmfbudget_domain_model_title (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	section int(11) unsigned DEFAULT '0' NOT NULL,
	groupe int(11) unsigned DEFAULT '0' NOT NULL,
	functione int(11) unsigned DEFAULT '0' NOT NULL,
	budgetgroup int(11) unsigned DEFAULT '0' NOT NULL,
	titlegroup int(11) unsigned DEFAULT '0' NOT NULL,

	address varchar(10) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	flow enum('e','a') NOT NULL default 'e',

	actual_income double(16,2) DEFAULT NULL,
	actual_expenses double(16,2) DEFAULT NULL,
	actual_page int(11) DEFAULT '0' NOT NULL,
	actual_page_link int(11) DEFAULT '0' NOT NULL,

	target_income double(16,2) DEFAULT NULL,
	target_expenses double(16,2) DEFAULT NULL,
	target_page int(11) DEFAULT '0' NOT NULL,
	target_page_link int(11) DEFAULT '0' NOT NULL,

	flexible tinyint(1) unsigned DEFAULT '0' NOT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,
	budget int(11) unsigned DEFAULT '0',
	supplementaries int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY address (address),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_section'
#
CREATE TABLE tx_bmfbudget_domain_model_section (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	section int(11) unsigned DEFAULT '0' NOT NULL,
	budget int(11) unsigned DEFAULT '0' NOT NULL,

	address varchar(4) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	actual_income double(16,2) DEFAULT NULL,
	actual_expenses double(16,2) DEFAULT NULL,
	target_income double(16,2) DEFAULT NULL,
	target_expenses double(16,2) DEFAULT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,
	sections int(11) unsigned DEFAULT '0' NOT NULL,
	titles int(11) unsigned DEFAULT '0' NOT NULL,
	budgetgroups int(11) unsigned DEFAULT '0' NOT NULL,
	titlegroups int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY address (address),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_groupe'
#
CREATE TABLE tx_bmfbudget_domain_model_groupe (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	groupe int(11) unsigned DEFAULT '0' NOT NULL,
	budget int(11) unsigned DEFAULT '0' NOT NULL,

	address varchar(3) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	actual_income double(16,2) DEFAULT NULL,
	actual_expenses double(16,2) DEFAULT NULL,
	target_income double(16,2) DEFAULT NULL,
	target_expenses double(16,2) DEFAULT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,
	groups int(11) unsigned DEFAULT '0' NOT NULL,
	titles int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY address (address),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_functione'
#
CREATE TABLE tx_bmfbudget_domain_model_functione (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	functione int(11) unsigned DEFAULT '0' NOT NULL,
	budget int(11) unsigned DEFAULT '0' NOT NULL,

	address varchar(3) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	actual_income double(16,2) DEFAULT NULL,
	actual_expenses double(16,2) DEFAULT NULL,
	target_income double(16,2) DEFAULT NULL,
	target_expenses double(16,2) DEFAULT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,
	functions int(11) unsigned DEFAULT '0' NOT NULL,
	titles int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY address (address),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_budgetgroup'
#
CREATE TABLE tx_bmfbudget_domain_model_budgetgroup (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	section int(11) unsigned DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	actual_income double(16,2) DEFAULT NULL,
	actual_expenses double(16,2) DEFAULT NULL,
	target_income double(16,2) DEFAULT NULL,
	target_expenses double(16,2) DEFAULT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,
	titles int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_titlegroup'
#
CREATE TABLE tx_bmfbudget_domain_model_titlegroup (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	section int(11) unsigned DEFAULT '0' NOT NULL,

	address varchar(255) DEFAULT '' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	actual_income double(16,2) DEFAULT NULL,
	actual_expenses double(16,2) DEFAULT NULL,
	target_income double(16,2) DEFAULT NULL,
	target_expenses double(16,2) DEFAULT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,
	titles int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_budget'
#
CREATE TABLE tx_bmfbudget_domain_model_budget (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title_actual varchar(255) DEFAULT '' NOT NULL,
	title_target varchar(255) DEFAULT '' NOT NULL,
	year varchar(255) DEFAULT '' NOT NULL,
	section_actual_income double(16,2) DEFAULT NULL,
	section_actual_expenses double(16,2) DEFAULT NULL,
	section_target_income double(16,2) DEFAULT NULL,
	section_target_expenses double(16,2) DEFAULT NULL,
	function_actual_income double(16,2) DEFAULT NULL,
	function_actual_expenses double(16,2) DEFAULT NULL,
	function_target_income double(16,2) DEFAULT NULL,
	function_target_expenses double(16,2) DEFAULT NULL,
	group_actual_income double(16,2) DEFAULT NULL,
	group_actual_expenses double(16,2) DEFAULT NULL,
	group_target_income double(16,2) DEFAULT NULL,
	group_target_expenses double(16,2) DEFAULT NULL,
	pid_title int(11) DEFAULT '0' NOT NULL,
	pid_section int(11) DEFAULT '0' NOT NULL,
	pid_function int(11) DEFAULT '0' NOT NULL,
	pid_group int(11) DEFAULT '0' NOT NULL,
	pid_budgetgroup int(11) DEFAULT '0' NOT NULL,
	pid_titlegroup int(11) DEFAULT '0' NOT NULL,
	pid_supplementary_budget int(11) DEFAULT '0' NOT NULL,
	pid_supplementary_title int(11) DEFAULT '0' NOT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,
	sections int(11) unsigned DEFAULT '0' NOT NULL,
	groups int(11) unsigned DEFAULT '0' NOT NULL,
	functions int(11) unsigned DEFAULT '0' NOT NULL,
	supplementary_budgets int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_supplementarytitle'
#
CREATE TABLE tx_bmfbudget_domain_model_supplementarytitle (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title int(11) unsigned DEFAULT '0' NOT NULL,
	supplementarybudget int(11) unsigned DEFAULT '0' NOT NULL,

	actual_income double(16,2) DEFAULT NULL,
	actual_expenses double(16,2) DEFAULT NULL,
	target_income double(16,2) DEFAULT NULL,
	target_expenses double(16,2) DEFAULT NULL,
	actual_page int(11) DEFAULT '0' NOT NULL,
	target_page int(11) DEFAULT '0' NOT NULL,
	info_image int(11) unsigned NOT NULL default '0',
	info_text text NOT NULL,
	info_link varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_supplementarybudget'
#
CREATE TABLE tx_bmfbudget_domain_model_supplementarybudget (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	budget int(11) unsigned DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	supplementary_titles int(11) unsigned DEFAULT '0' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudget_domain_model_supplementarytitle'
#
CREATE TABLE tx_bmfbudget_domain_model_supplementarytitle (

	title  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_section'
#
CREATE TABLE tx_bmfbudget_domain_model_section (

	section  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_title'
#
CREATE TABLE tx_bmfbudget_domain_model_title (

	section  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_budgetgroup'
#
CREATE TABLE tx_bmfbudget_domain_model_budgetgroup (

	section  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_titlegroup'
#
CREATE TABLE tx_bmfbudget_domain_model_titlegroup (

	section  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_groupe'
#
CREATE TABLE tx_bmfbudget_domain_model_groupe (

	groupe  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_title'
#
CREATE TABLE tx_bmfbudget_domain_model_title (

	groupe  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_functione'
#
CREATE TABLE tx_bmfbudget_domain_model_functione (

	functione  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_title'
#
CREATE TABLE tx_bmfbudget_domain_model_title (

	functione  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_title'
#
CREATE TABLE tx_bmfbudget_domain_model_title (

	budgetgroup  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_title'
#
CREATE TABLE tx_bmfbudget_domain_model_title (

	titlegroup  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_section'
#
CREATE TABLE tx_bmfbudget_domain_model_section (

	budget  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_groupe'
#
CREATE TABLE tx_bmfbudget_domain_model_groupe (

	budget  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_functione'
#
CREATE TABLE tx_bmfbudget_domain_model_functione (

	budget  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_supplementarybudget'
#
CREATE TABLE tx_bmfbudget_domain_model_supplementarybudget (

	budget  int(11) unsigned DEFAULT '0' NOT NULL,

);

#
# Table structure for table 'tx_bmfbudget_domain_model_supplementarytitle'
#
CREATE TABLE tx_bmfbudget_domain_model_supplementarytitle (

	supplementarybudget  int(11) unsigned DEFAULT '0' NOT NULL,

);
