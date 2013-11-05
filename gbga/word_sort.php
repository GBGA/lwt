<?php
//-- #GBGA:

require_once( '../settings.inc.php' );
require_once( '../connect.inc.php' );
require_once( '../dbutils.inc.php' );
require_once( '../utilities.inc.php' );

$apply    = isset($_GET['apply']) ? $_GET['apply'] : 0;
$text_id  = validateText(processSessParam("text","currentwordtext",'',0));     if (empty($text_id)) die("text not set");
$lang_id  = validateLang(processDBParam("filterlang",'currentlanguage','',0)); if (empty($lang_id)) die("language not set");
$NR_FIRST = get_first_value("select max(ZwlNR) as value from z_word_list where ZwlLgID = {$lang_id} and ZwlLocked <> 0");
$r_words  = do_mysql_query("select * from z_word_list where ZwlLgID = {$lang_id} and ZwlLocked = 0 order by ZwlNR");

echo "SORTING by text ID={$text_id}, starting after nr={$NR_FIRST}...<br />";
echo $apply ? "APPLYING..." : "(To apply press 'Apply new order' button)";
echo "<hr />";
echo "SORTED:<br />";

$arSorted = Array();
$arUnsorted = Array();
while ($word = mysql_fetch_assoc($r_words)) {
	$word_lc   = mysql_real_escape_string($word['ZwlWoTextLC']);
	$word_tx   = get_first_value('SELECT WoText as value FROM words WHERE WoTextLC = "' . $word_lc . '"');
	$word_se   = get_first_value('SELECT WoSentence as value FROM words WHERE WoTextLC = "' . $word_lc . '"');
	$word_info = mysql_fetch_assoc(do_mysql_query("SELECT * FROM textitems WHERE TiTxID = {$text_id} and TiLgID = {$lang_id} and TiText = \"" . $word_tx . "\" limit 1"));
	//echo "{$word['ZwlNR']}: [id={$word_id}] [ord={$word_info[TiOrder]}] {$word_tx} ––– \"{$word_se}\"<br />";

	if (!empty($word_info[TiOrder])) {
		$arSorted[$word_info[TiOrder]] = Array($word_tx, $word, $word_se);
	} else {
		array_push($arUnsorted, Array($word_tx, $word, $word_se));
	}
}

//echo "<hr />";
ksort($arSorted);

$NR_CURR = $NR_FIRST;
$can_lock = true;

foreach ($arSorted as $order => $info) {
	$NR_CURR++;
	$word_tx = $info[0];
	$word    = $info[1];
	$word_se = $info[2];
	$id      = $word['ZwlID'];
	$orig_nr = $word['ZwlNR'];
	$word_id = $word['ZwlWoID'];
	$result  = $apply ? runsql("update z_word_list set ZwlNR = {$NR_CURR} where ZwlID = {$id}", '') : -1;
	$NR      = $apply ? get_first_value("select ZwlNR as value from z_word_list where ZwlID = {$id}") : $NR_CURR;

	echo $apply ? "{$NR} <-- {$orig_nr}" : "({$NR}) <-- {$orig_nr}";
	echo " : [wid={$word_id}] [ord={$order}] {$word_tx} ----- \"{$word_se}\"";

	if ($apply) {
		if ($result == 1) echo " UPDATED";
		if ($NR_CURR != $NR) {
			$can_lock = false;
			echo " UPDATE FAILED";
		}
	}
	echo "<br />";
}

if ($apply && $can_lock) {
	$count = $NR_CURR - $NR_FIRST;
	if ($count) {
		echo "<input type='button' value='Lock {$count} word(s)' onclick='{location.href=\"word_lock.php?last={$NR_CURR}\";}' />";
	}
}

echo "<hr />";
echo "NOT SORTED:<br />";

foreach ($arUnsorted as $info)
{
	$NR_CURR++;
	$word_tx = $info[0];
	$word    = $info[1];
	$word_se = $info[2];
	$id      = $word['ZwlID'];
	$orig_nr = $word['ZwlNR'];
	$word_id = $word['ZwlWoID'];
	$result  = $apply ? runsql("update z_word_list set ZwlNR = {$NR_CURR} where ZwlID = {$id}", '') : -1;
	$NR      = get_first_value("select ZwlNR as value from z_word_list where ZwlID = {$id}");
	
	echo $apply ? "{$NR} <-- {$orig_nr}" : "({$NR}) <-- {$orig_nr}";
	echo " : [wid={$word_id}] {$word_tx} ----- \"{$word_se}\"";

	if ($apply) {
		if ($result == 1) echo " UPDATED";
		if ($NR_CURR != $NR) {
			$can_lock = false;
			echo " UPDATE FAILED";
		}
	}
	echo "<br />";
}

echo "<hr />";
echo "<input type='button' value='BACK' onclick='{location.href=\"../edit_words.php\";}' />";
if (!$apply)
	echo "<input type='button' value='Apply new order' onclick='{location.href=\"?apply=1\";}' />";
