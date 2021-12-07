$(document).ready(function($){
	var fnt_icons = $('#icon_element').data('iconlist');
	var cur_icon = $('#icon_element').data('curicon');

	$('#icon_element').val(cur_icon);
	
	if(cur_icon !== "")
	{
		$('#icon_code').html(cur_icon);
	}

	$('#icon_element').fontIconPicker({
		source: fnt_icons,
		emptyIcon: true,
		hasSearch: true,
		iconsPerPage: 40
	});

	$('.icon_picker_save .btn').on('click', function(){
		$('.t3js-modal-close').trigger('click');

		//$('input[name="data[tt_content][4][tx_almiconfields_icon]"]').val($('#icon_element').val());
		//$('input[name="data[tt_content][4][tx_almiconfields_icon]"]').val('abc');

		//console.log($('input[name="data[tt_content][4][tx_almiconfields_icon]"]'));
		//console.log($('#typo3-contentIframe').contents().find('input[name="data[tt_content][4][tx_almiconfields_icon]"]'));

		//$('#typo3-contentIframe').contents().find('input[name="data[tt_content][4][tx_almiconfields_icon]"]').val('myValue');
	});

	/* Only Temporary */
	$('#icon_element').on('input', function(){
		$('#icon_code').html($('#icon_element').val());
	});
});