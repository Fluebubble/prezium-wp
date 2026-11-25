/* Prezium: weiches Einblenden beim Scrollen (Startseite) */
(function () {
	var root = document.documentElement;
	var items = document.querySelectorAll('.rv');
	if (!items.length || !('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}
	root.classList.add('js');
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				entry.target.classList.add('in');
				io.unobserve(entry.target);
			}
		});
	}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
	items.forEach(function (el, i) {
		el.style.transitionDelay = (i % 3) * 80 + 'ms';
		io.observe(el);
	});
})();
