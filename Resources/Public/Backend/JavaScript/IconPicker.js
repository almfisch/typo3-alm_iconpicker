$(document).ready(function($){
	fnt_icons = $('#icon_element').data('iconlist');
	$('#icon_element').fontIconPicker({
		source: fnt_icons,
		emptyIcon: true,
		hasSearch: true,
		iconsPerPage: 40
	});

	/* Only Temporary */
	$('#icon_element').on('input', function(){
		$('#icon_code').html($('#icon_element').val());
	});
});