# This file is part of the "bmf_budget" Extension for TYPO3 CMS.
#
# For the full copyright and license information, please read the
# LICENSE.txt file that was distributed with this source code.

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	show_teaser tinyint(4) unsigned DEFAULT '0' NOT NULL
);


#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	bodytext2 mediumtext,
	header2 varchar(255) DEFAULT '' NOT NULL,
  header_link2 varchar(1024) DEFAULT '' NOT NULL,
  header_layout2 varchar(30) DEFAULT '0' NOT NULL
);
