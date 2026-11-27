/* Logo-Auswahl im Marken-Editor */
jQuery(function ($) {
	var frame;
	$('#prez_logo_pick').on('click', function (e) {
		e.preventDefault();
		if (!frame) {
			frame = wp.media({ multiple: false, library: { type: ['image'] } });
			frame.on('select', function () {
				var a = frame.state().get('selection').first().toJSON();
				$('#prez_logo').val(a.id);
				var src = (a.sizes && a.sizes.medium) ? a.sizes.medium.url : a.url;
				$('#prez_logo_preview').html('<img src="' + src + '" style="max-width:200px;height:auto;background:#555;padding:10px;display:block">');
			});
		}
		frame.open();
	});
	$('#prez_logo_remove').on('click', function (e) {
		e.preventDefault();
		$('#prez_logo').val('');
		$('#prez_logo_preview').empty();
	});
});
