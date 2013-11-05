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

function add_word_to_list(word_lc) {
	//if (word=='') $('#editimprtextdata').html('<img src="icn/waiting2.gif" />');
	
	//alert(word_lc);
	//var textid = $('#editimprtextdata').attr('data_id');
	$.post('gbga/ajax_add_word_to_list.php', { word_lc: word_lc }, 
		function(data) {
			if (data)
				alert(data);
			window.location.reload();
			//eval(data);
			//$.scrollTo(pagepos); 
			//$('input.impr-ann-text').change(changeImprAnnText);
			//$('input.impr-ann-radio').change(changeImprAnnRadio);
		} 
	);
}
