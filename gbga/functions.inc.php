<?php
require_once("langdefs.inc.php");

function GetLanguageInitialsByID($lang_id) {
	$gtr = get_first_value("select LgGoogleTranslateURI as value from languages where LgID = " . $lang_id);
	$t1  = explode('&sl=', $gtr);
	$t2  = explode('&', $t1[1]);
	$lng = $t2[0];
	return $lng;
}

function GetLanguageInitialsByName($lang_name) {
	global $langDefs;
	$lng = $langDefs[$lang_name][0];
	return $lng;
}

function SplitTagCommentRaw($tag_comment_raw, &$comment, &$props) {
	$parts = explode('|', $tag_comment_raw);
	$comment = $parts[0];
	$props = (count($parts) == 2) ? json_decode($parts[1], true) : Array();
}

function MakeTagCommentRaw($comment, $props) {
	$tag_comment_raw = $comment;
	foreach ($props as $key => $value)
		if (empty($value))
			unset($props[$key]);
	if (count($props)) {
		$props_json = json_encode($props);
		$tag_comment_raw .= '|';
		$tag_comment_raw .= $props_json;
	}
	return $tag_comment_raw;
}

function GetPropsFromTagList($tag_list, &$color) {
	 $color = '';
	 foreach ($tag_list as $tag) {
		$tag_comment_raw = get_first_value("select TgComment as value from tags where TgText='{$tag}'");
		SplitTagCommentRaw($tag_comment_raw, $comment, $props);
		if (empty($color)) {
			$color = $props['color'];
		}
	 }
}

?>