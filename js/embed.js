/**
 * Planafolio Finanzrechner (Drupal): two-click loader and auto-height.
 * Runs locally from the plugin; nothing is fetched from planafolio.com
 * before a calculator is shown.
 */
(function () {
	'use strict';

	var ORIGIN = 'https://planafolio.com';
	var MESSAGE_TYPE = 'planafolio-embed-resize';

	function load(button) {
		var box = button.closest('.pft-consent');
		if (!box) return;
		var iframe = document.createElement('iframe');
		iframe.setAttribute('data-planafolio-embed', '');
		iframe.src = button.getAttribute('data-pft-src');
		iframe.title = button.getAttribute('data-pft-title') || '';
		iframe.style.width = '100%';
		iframe.style.border = '0';
		iframe.style.height = (parseInt(button.getAttribute('data-pft-height'), 10) || 640) + 'px';
		box.parentNode.replaceChild(iframe, box);
	}

	document.addEventListener('click', function (event) {
		var button = event.target.closest && event.target.closest('.pft-consent-button');
		if (button) load(button);
	});

	// The calculator reports its content height; size the matching iframe.
	window.addEventListener('message', function (event) {
		if (event.origin !== ORIGIN) return;
		var data = event.data;
		if (!data || typeof data !== 'object' || data.type !== MESSAGE_TYPE) return;
		var height = data.height;
		if (typeof height !== 'number' || !isFinite(height) || height <= 0) return;
		var frames = document.querySelectorAll('iframe[data-planafolio-embed]');
		for (var i = 0; i < frames.length; i++) {
			if (frames[i].contentWindow === event.source) {
				frames[i].style.height = Math.min(height, 4000) + 'px';
				break;
			}
		}
	});
})();
