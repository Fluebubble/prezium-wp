/* Mediathek-Auswahl für Dokumentfelder */
jQuery(function ($) {
	$('.prezium-pick').on('click', function (e) {
		e.preventDefault();
		var target = $('#' + $(this).data('target'));
		var frame = wp.media({ multiple: false });
		frame.on('select', function () {
			target.val(frame.state().get('selection').first().toJSON().url);
		});
		frame.open();
	});
});
