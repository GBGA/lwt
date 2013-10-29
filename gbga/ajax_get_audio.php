<?php
$lang = $_REQUEST['lang'];
$text = $_REQUEST['text'];
$path = $_REQUEST['path'];

if (!is_dir($path)) {
	//new php
	@mkdir($dir, 0777, true);

	//old php
	if (!is_dir($path)) {
		$parts = explode('/', $path);
		array_pop($parts); //remove file name
		$dir = '..';
		foreach ($parts as $part) {
			$dir .= '/';
			$dir .= $part;
			if (!is_dir($dir)) {
				mkdir($dir, 0777);
				chmod($dir, 0777);
			}
		}
	}
}


$cmd  = "wget -q -U Mozilla -O \"../{$path}\" \"http://translate.google.com/translate_tts?ie=UTF-8&tl={$lang}&q={$text}\"";

exec($cmd, $array);

if (!is_file("../$path")) {
	return;
}

chmod("../$path", 0777);
echo "OK";
?>