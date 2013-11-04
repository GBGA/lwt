//-- #GBGA:

/**************************************************************
LWT jQuery functions
***************************************************************/

$(document).ready( function() {
	$(".edit_area_1row").editable("inline_edit.php",
		{ 
			indicator : "<img src='../icn/indicator.gif'>",
			placeholder : '&nbsp;',
			tooltip   : "Click to edit...",
			style     : "inherit"
		}
	);
} ); 

function download_and_play(path, lang, text, th) {
	var player = document.getElementById('audio_player');
	player.pause();

	if (lang != '') {
		$.post('gbga/ajax_get_audio.php', { lang: lang, text: text, path: path }, 
			function(data) {
				if (data == 'OK') {
					var id = '#'+th;
					$(id).removeClass('btn_no_audio').addClass('btn_audio');

					var audio = document.getElementById('audio_mp3');
					audio.src = path;
					player.load();
					player.play();
				} else if (data != '') {
					alert(data);
				}
			} 
		);
	} else {
		var audio = document.getElementById('audio_mp3');
		audio.src = path;
		player.load();
		player.play();
	}
}

function on_color_changed(id, value, color)
{
	color = (value == '') ? '' : '#' + String(color);
	$.get('gbga/ajax_set_tag_props.php', { id: id, color: color }, function(data) {
		if (data == 'OK') {
			var iid = '#tagid_'+id;
			$(iid).attr('bgcolor', color);
		} else {
			alert(data);
		}
	});
}

