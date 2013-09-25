jQuery(document).ready(function($) {

	$('.collapsed input:checkbox').click(unhideHidden);

	function unhideHidden(){
		if ($(this).attr('checked')) {
			$(this).parent().parent().next().removeClass('hidden');
		}
		else {
			$(this).parent().parent().next().addClass('hidden');
		}
	}

	$('#beta_theme_settings-content_archive').on('change', function() {
	  	if (this.value != 'full') {
			$(this).parent().next().removeClass('hidden');
		}
		else {
			$(this).parent().next().addClass('hidden');
		}
	});

});