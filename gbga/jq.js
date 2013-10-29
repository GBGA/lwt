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
