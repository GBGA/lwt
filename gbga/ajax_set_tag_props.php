<?php
require_once( '../settings.inc.php' );
require_once( '../connect.inc.php' );
require_once( '../dbutils.inc.php' );
require_once( '../utilities.inc.php' );
require_once( 'functions.inc.php' );

$id    = $_REQUEST['id'];
$color = $_REQUEST['color'];

$tag_comment_raw = get_first_value("select {$tbpref}TgComment as value from tags where TgID='{$id}'");

SplitTagCommentRaw($tag_comment_raw, $comment, $props);
$props['color'] = $color;
$tag_comment_raw_new = MakeTagCommentRaw($comment, $props);
//$tag_comment_raw_new = convert_string_to_sqlsyntax(repl_tab_nl($tag_comment_raw_new));
//echo "$tag_comment_raw_new\n\n\n";

$message = runsql("update {$tbpref}tags set TgComment = '{$tag_comment_raw_new}' where TgID = {$id}", '');

$tag_comment_raw_res = get_first_value("select {$tbpref}TgComment as value from tags where TgID='{$id}'");
SplitTagCommentRaw($tag_comment_raw_res, $comment_res, $props_res);
if ($props_res['color'] != $color) {
	echo 'ERROR: ' . $message;
} else {
	echo "OK";
}
?>