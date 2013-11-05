<?php
//-- #GBGA:

require_once( '../settings.inc.php' );
require_once( '../connect.inc.php' );
require_once( '../dbutils.inc.php' );
require_once( '../utilities.inc.php' );

//$word_id = $_POST['word_id'];
$word_lc = $_POST['word_lc'];
$word_lc = str_replace('@QUOTE1@', "'", $word_lc);
$word_lc = mysql_real_escape_string($word_lc);

//$lang_id = get_first_value("select WoLgID as value from words where WoID = " . $word_id);
$lang_id = get_first_value('select WoLgID as value from words where WoTextLC = "' . $word_lc . '"');

$NR = get_first_value("select MAX(ZwlNR) as value from z_word_list WHERE ZwlLgID = " . $lang_id);
++$NR;

//$message = runsql("insert into z_word_list (ZwlLgID, ZwlNR, ZwlNR2, ZwlLocked, ZwlWoID) values ({$lang_id}, {$NR}, 0, 0, {$word_id})", "");
$message = runsql("insert into z_word_list (ZwlLgID, ZwlNR, ZwlNR2, ZwlLocked, ZwlWoTextLC) values ({$lang_id}, {$NR}, 0, 0, \"" . $word_lc . "\")", "");

//$value = get_first_value('select ZwlNR as value from z_word_list where ZwlWoID = ' . $word_id);
$value = get_first_value('select ZwlNR as value from z_word_list where ZwlWoTextLC = "' . $word_lc . '"');

if ($value == '') 
	echo $message;
else 
	echo '';
?>
