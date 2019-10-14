# This file is part of the "bmf_budget_crawler" Extension for TYPO3 CMS.
#
# For the full copyright and license information, please read the
# LICENSE.txt file that was distributed with this source code.

#
# Table structure for table 'tx_bmfbudgetcrawler_domain_model_crawler'
#
CREATE TABLE tx_bmfbudgetcrawler_domain_model_crawler (

  uid              int(11)                         NOT NULL auto_increment,
  pid              int(11) DEFAULT '0'             NOT NULL,

  ext_title        varchar(255) DEFAULT ''         NOT NULL,
  ext_class        varchar(255) DEFAULT ''         NOT NULL,

  budget           int(11) DEFAULT '0'             NOT NULL,
  account          varchar(15) DEFAULT ''          NOT NULL,
  flow             varchar(15) DEFAULT ''          NOT NULL,
  structure        varchar(15) DEFAULT ''          NOT NULL,

  preprocessed     tinyint(4) unsigned DEFAULT '0' NOT NULL,
  progress         double DEFAULT '0'              NOT NULL,
  queues           int(11) unsigned DEFAULT '0'    NOT NULL,

  error            int(11) DEFAULT '0'             NOT NULL,
  error_message    varchar(255) DEFAULT ''         NOT NULL,

  process_start    int(11) unsigned DEFAULT '0'    NOT NULL,
  process_end      int(11) unsigned DEFAULT '0'    NOT NULL,

  tstamp           int(11) unsigned DEFAULT '0'    NOT NULL,
  crdate           int(11) unsigned DEFAULT '0'    NOT NULL,
  cruser_id        int(11) unsigned DEFAULT '0'    NOT NULL,
  deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
  hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,
  starttime        int(11) unsigned DEFAULT '0'    NOT NULL,
  endtime          int(11) unsigned DEFAULT '0'    NOT NULL,

  t3ver_oid        int(11) DEFAULT '0'             NOT NULL,
  t3ver_id         int(11) DEFAULT '0'             NOT NULL,
  t3ver_wsid       int(11) DEFAULT '0'             NOT NULL,
  t3ver_label      varchar(255) DEFAULT ''         NOT NULL,
  t3ver_state      tinyint(4) DEFAULT '0'          NOT NULL,
  t3ver_stage      int(11) DEFAULT '0'             NOT NULL,
  t3ver_count      int(11) DEFAULT '0'             NOT NULL,
  t3ver_tstamp     int(11) DEFAULT '0'             NOT NULL,
  t3ver_move_id    int(11) DEFAULT '0'             NOT NULL,

  sys_language_uid int(11) DEFAULT '0'             NOT NULL,
  l10n_parent      int(11) DEFAULT '0'             NOT NULL,
  l10n_diffsource  mediumblob,

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY t3ver_oid (t3ver_oid,t3ver_wsid),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudgetcrawler_domain_model_queue'
#
CREATE TABLE tx_bmfbudgetcrawler_domain_model_queue (

  uid              int(11)                         NOT NULL auto_increment,
  pid              int(11) DEFAULT '0'             NOT NULL,

  crawler          int(11) unsigned DEFAULT '0'    NOT NULL,
  address          varchar(255) DEFAULT ''         NOT NULL,
  status           varchar(255) DEFAULT ''         NOT NULL,
  result           text                            NOT NULL,
  error            int(11) DEFAULT '0'             NOT NULL,
  error_message    varchar(255) DEFAULT ''         NOT NULL,

  tstamp           int(11) unsigned DEFAULT '0'    NOT NULL,
  crdate           int(11) unsigned DEFAULT '0'    NOT NULL,
  cruser_id        int(11) unsigned DEFAULT '0'    NOT NULL,
  deleted          tinyint(4) unsigned DEFAULT '0' NOT NULL,
  hidden           tinyint(4) unsigned DEFAULT '0' NOT NULL,
  starttime        int(11) unsigned DEFAULT '0'    NOT NULL,
  endtime          int(11) unsigned DEFAULT '0'    NOT NULL,

  t3ver_oid        int(11) DEFAULT '0'             NOT NULL,
  t3ver_id         int(11) DEFAULT '0'             NOT NULL,
  t3ver_wsid       int(11) DEFAULT '0'             NOT NULL,
  t3ver_label      varchar(255) DEFAULT ''         NOT NULL,
  t3ver_state      tinyint(4) DEFAULT '0'          NOT NULL,
  t3ver_stage      int(11) DEFAULT '0'             NOT NULL,
  t3ver_count      int(11) DEFAULT '0'             NOT NULL,
  t3ver_tstamp     int(11) DEFAULT '0'             NOT NULL,
  t3ver_move_id    int(11) DEFAULT '0'             NOT NULL,

  sys_language_uid int(11) DEFAULT '0'             NOT NULL,
  l10n_parent      int(11) DEFAULT '0'             NOT NULL,
  l10n_diffsource  mediumblob,

  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY t3ver_oid (t3ver_oid,t3ver_wsid),
  KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_bmfbudgetcrawler_domain_model_queue'
#
CREATE TABLE tx_bmfbudgetcrawler_domain_model_queue (

  crawler int(11) unsigned DEFAULT '0' NOT NULL,

);
