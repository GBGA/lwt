<?php
//-- #GBGA:

//require_once( '../settings.inc.php' );
//require_once( '../connect.inc.php' );
//require_once( '../dbutils.inc.php' );
//require_once( '../utilities.inc.php' );

function init_tables($tables) {
	if (in_array($tbpref . 'z_word_list', $tables) == FALSE) {
		if ($debug) echo '<p>DEBUG: rebuilding z_word_list</p>';
		$sql = 'CREATE TABLE `z_word_list` (
						`ZwlID`       INT(11)      UNSIGNED NOT NULL AUTO_INCREMENT,
						`ZwlLgID`     INT(11)      UNSIGNED NOT NULL,
						`ZwlNR`       INT(11)      UNSIGNED NOT NULL,
						`ZwlNR2`      INT(11)      UNSIGNED,
						`ZwlLocked`   DATE         DEFAULT NULL,
						`ZwlWoTextLC` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						`ZwlNote`     TEXT         CHARACTER SET utf8 COLLATE utf8_general_ci,
						`ZwlNote2`    TEXT         CHARACTER SET utf8 COLLATE utf8_general_ci,
							PRIMARY	KEY               (`ZwlID`)       USING BTREE,
									KEY `ZwlLgID`     (`ZwlLgID`)     USING BTREE,
									KEY `ZwlNR`       (`ZwlNR`)       USING BTREE,
									KEY `ZwlNR2`      (`ZwlNR2`)      USING BTREE,
									KEY `ZwlLocked`   (`ZwlLocked`)   USING BTREE,
									KEY `ZwlWoTextLC` (`ZwlWoTextLC`) USING BTREE
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
					
						//`ZwlWoID`     INT(11)      UNSIGNED NOT NULL,
						//			KEY `ZwlWoID`     (`ZwlWoID`)     USING BTREE,

		runsql($sql,'');
		print('<hr /><hr /><b> DATABASE CREATED: z_word_list</b><hr /><hr />');
	}
}


?>
