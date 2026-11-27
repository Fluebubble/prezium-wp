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


/* Mobiles Menü */
(function () {
	var btn = document.querySelector('.nav-toggle');
	var menu = document.getElementById('site-menu');
	if (!btn || !menu) { return; }

	function set(open) {
		menu.classList.toggle('is-open', open);
		document.body.classList.toggle('menu-open', open);
		btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		btn.setAttribute('aria-label', btn.getAttribute(open ? 'data-label-close' : 'data-label-open'));
	}

	btn.addEventListener('click', function () { set(btn.getAttribute('aria-expanded') !== 'true'); });
	menu.addEventListener('click', function (e) { if (e.target.closest('a')) { set(false); } });
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { set(false); } });
	window.addEventListener('resize', function () { if (window.innerWidth > 1024) { set(false); } });
})();


/* Projekte: Filter nach Bereich */
(function () {
	var chips = document.querySelectorAll('.pa-chip');
	if (!chips.length) { return; }
	var items = document.querySelectorAll('.pa-item');
	var counter = document.querySelector('[data-count]');
	chips.forEach(function (chip) {
		chip.addEventListener('click', function () {
			var f = chip.getAttribute('data-filter');
			var n = 0;
			chips.forEach(function (c) { c.classList.toggle('is-active', c === chip); });
			items.forEach(function (it) {
				var show = f === '*' || (it.getAttribute('data-areas') || '').split(' ').indexOf(f) !== -1;
				it.hidden = !show;
				if (show) { n++; }
			});
			if (counter) { counter.textContent = n + (n === 1 ? ' Projekt' : ' Projekte'); }
		});
	});
})();
