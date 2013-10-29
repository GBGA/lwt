<?php

function GetLanguageInitials($lang_id) {
	$gtr = get_first_value("select LgGoogleTranslateURI as value from languages where LgID = " . $lang_id);
	$t1  = explode('&sl=', $gtr);
	$t2  = explode('&', $t1[1]);
	return $t2[0];
}

?>