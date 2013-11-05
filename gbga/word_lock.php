<?php
//-- #GBGA:

require_once( '../settings.inc.php' );
require_once( '../connect.inc.php' );
require_once( '../dbutils.inc.php' );
require_once( '../utilities.inc.php' );

$apply = isset($_GET['apply']) ? $_GET['apply'] : 0;
$date  = isset($_GET['date'])  ? $_GET['date']  : date('Y-m-d');
$lang_id = validateLang(processDBParam("filterlang",'currentlanguage','',0)); if (empty($lang_id)) die("language not set");
//$text_id = validateText(processSessParam("text","currentwordtext",'',0));
$NR_FIRST = get_first_value("select max(ZwlNR) as value from z_word_list where ZwlLgID = {$lang_id} and ZwlLocked <> 0");
$sql_limit = '';

if (isset($_GET['last']) && 0 < $_GET['last']) {
	$last  = $_GET['last'];
	$count = $last - $NR_FIRST;
	if ($count < 1) {
		echo "Nothing to lock";
	} else {
		echo "Locking {$count} words";
		$sql_limit = 'limit ' . $count;
	}
} else {
	$last  = -1;
	$count = 9999;
	echo "Locking ALL words";
}

$NR_FIRST++;
$real_count = 0;

if (0 < $count)
{
	echo "<br />";
	echo $apply ? "APPLYING..." : "(To apply press 'Apply lock' button)<br />";
	echo "<hr />";

	$r_words = do_mysql_query("select * from z_word_list where ZwlLgID = {$lang_id} and ZwlLocked = 0 order by ZwlNR {$sql_limit}");

	$arr = Array();
	while ($words = mysql_fetch_assoc($r_words))
	{
		$real_count++;
		$id      = $words['ZwlID'];
		$word_lc = mysql_real_escape_string($words['ZwlWoTextLC']);
		$word_nr = $words['ZwlNR'];
		$word_tx = get_first_value("SELECT WoText as value FROM words WHERE WoTextLC = '{$word_lc}'");
		$result  = $apply ? runsql("update z_word_list set ZwlLocked = '{$date}' where ZwlID = " . $id, '') : -1;
		$locked  = get_first_value('select ZwlLocked as value from z_word_list where ZwlID = ' . $id);
		
		echo "{$word_nr}: [wid=$word_id] $word_tx";
		

		if ($apply) {
			if ($result == 1) echo " ---  LOCKED";
			else              echo " ---  LOCK FAILED";
		}
		echo "<br />";
	}
}

$NR_LAST = $NR_FIRST + $real_count - 1;
if (!$apply) {
	echo "<hr />";
	echo "<form action='' method='get' onsubmit=''>";
	echo "Show from {$NR_FIRST} to ";
	echo "<input type='text' name='last' value={$NR_LAST} size=5/>";
	echo "<br />Lock date: ";
	echo "<input type='text' name='date' value={$date} size=10/>";
	echo " ";
	echo "<input type='submit' value='Refresh'/>";
	echo "</form>";
}
echo "<hr />";
echo "<input type='button' value='BACK' onclick='{location.href=\"../edit_words.php\";}' /> ";
if (!$apply)
	echo "<input type='button' value='Apply lock (from {$NR_FIRST} to {$NR_LAST}, at {$date})' onclick='{location.href=\"?apply=1&last={$last}&date={$date}\";}' />";
