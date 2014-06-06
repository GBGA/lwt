<?php
if (substr($id, 0, 2) == "nr") {
	$id    = substr($id, 2);
	$sqlNR = convert_string_to_sqlsyntax(repl_tab_nl($value));
	if (empty($value)) {
		$message = runsql('delete from z_word_list where ZwlID = ' . $id, '');
	} else {
		$message = runsql('update z_word_list set ZwlNR = ' . $sqlNR . ' where ZwlID = ' . $id, '');
	}
	$value = get_first_value('select ZwlNR as value from z_word_list where ZwlID = ' . $id);
	if ($value == '') 
		echo '+';
	else 
		echo $value;
	exit;
}

{
	$key       = substr($id, 0, 9);
	$idd       = substr($id, 9);
	if ($idd)
	{
		$value_sql = convert_string_to_sqlsyntax(repl_tab_nl(str_replace('*', '', $value)));
		$table     = 'z_sent_list';
		$field_id  = 'ZslID';
		switch ($key) {
			case "sent_nrnr": $field = 'ZslNR';           break;
			case "sent_sent": $field = 'ZslSentence';     break;
			case "sent_romn": $field = 'ZslRomanization'; break;
			case "sent_tran": $field = 'ZslTranslation';  break;
			case "sent_note": $field = 'ZslNote';         break;
			case "sent_nt2_": $field = 'ZslNote2';        break;

			case "word_note": $table = 'z_word_list'; $field_id  = 'ZwlID'; $field = 'ZwlNote';  break;
			case "word_nt2_": $table = 'z_word_list'; $field_id  = 'ZwlID'; $field = 'ZwlNote2'; break;
			default: assert(FALSE); break;
		}

		if (empty($value) && $field == 'ZslNR') {
			$message = runsql("delete from {$table} where {$field_id} = {$idd}", '');
		} else {
			$message = runsql("update {$table} set {$field} = {$value_sql} where {$field_id} = {$idd}", '');
		}
		$value = get_first_value("select {$field} as value from {$table} where {$field_id} = {$idd}");
		if (empty($value)) echo '';
		else               echo $value;
		exit;
	}
}

if (substr($id, 0, 3) == "tag") {
	$id = substr($id, 3);
	
	runsql('delete from ' . $tbpref . 'wordtags where WtWoID = ' . $id, "");
	if (!empty($value)) {
		addtaglist($value, '(' . $id . ')');
	}
		
	$sql = "SELECT GROUP_CONCAT(TgText) AS value FROM "
			. $tbpref . "wordtags LEFT JOIN " . $tbpref . "tags ON "
			. $tbpref . "wordtags.WtTgID = " . $tbpref . "tags.TgID WHERE WtWoID=" . $id;
		
	$value = get_first_value($sql);
	echo $value;
	exit;
}


?>