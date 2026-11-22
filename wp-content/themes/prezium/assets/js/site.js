/* Prezium: Pop-up "Preis anfragen" und AJAX-Formular */
(function () {
	var dialog = document.getElementById('inquiry-dialog');

	function wire(form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var status = form.querySelector('.form-status');
			var btn = form.querySelector('button[type="submit"]');
			var data = new FormData(form);
			data.append('nonce', window.prezium.nonce);
			status.textContent = '';
			btn.disabled = true;

			fetch(window.prezium.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (res && res.success) {
						status.textContent = (res.data && res.data.message) || '';
						form.reset();
					} else {
						status.textContent = (res && res.data && res.data.message) || window.prezium.error;
					}
				})
				.catch(function () { status.textContent = window.prezium.error; })
				.then(function () { btn.disabled = false; });
		});
	}

	document.querySelectorAll('.inquiry-form').forEach(wire);

	if (!dialog) { return; }

	document.querySelectorAll('[data-inquiry]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var ref = btn.getAttribute('data-inquiry');
			dialog.querySelector('input[name="product"]').value = ref;
			dialog.querySelector('[data-inquiry-ref]').textContent = ref;
			dialog.querySelector('.form-status').textContent = '';
			dialog.showModal();
		});
	});

	dialog.querySelectorAll('[data-inquiry-close]').forEach(function (b) {
		b.addEventListener('click', function () { dialog.close(); });
	});

	// Klick auf den Hintergrund schließt das Pop-up.
	dialog.addEventListener('click', function (e) {
		if (e.target === dialog) { dialog.close(); }
	});
})();
