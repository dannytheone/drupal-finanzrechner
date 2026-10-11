/**
 * @file
 * Planafolio Finanzrechner: two-click loader and auto-height.
 *
 * Runs locally from the module; nothing is fetched from planafolio.com
 * before a calculator is shown.
 */
(() => {
  const ORIGIN = 'https://planafolio.com';
  const MESSAGE_TYPE = 'planafolio-embed-resize';

  /**
   * Replaces the two-click placeholder with the calculator iframe.
   *
   * @param {HTMLElement} button
   *   The "Load calculator" button inside the placeholder.
   */
  function load(button) {
    const box = button.closest('.pft-consent');
    if (!box) {
      return;
    }
    const height = parseInt(button.getAttribute('data-pft-height'), 10) || 640;
    const iframe = document.createElement('iframe');
    iframe.setAttribute('data-planafolio-embed', '');
    iframe.src = button.getAttribute('data-pft-src');
    iframe.title = button.getAttribute('data-pft-title') || '';
    iframe.style.width = '100%';
    iframe.style.border = '0';
    iframe.style.height = `${height}px`;
    box.parentNode.replaceChild(iframe, box);
  }

  document.addEventListener('click', (event) => {
    const button = event.target.closest
      ? event.target.closest('.pft-consent-button')
      : null;
    if (button) {
      load(button);
    }
  });

  // The calculator reports its content height; size the matching iframe.
  window.addEventListener('message', (event) => {
    if (event.origin !== ORIGIN) {
      return;
    }
    const { data } = event;
    if (!data || typeof data !== 'object' || data.type !== MESSAGE_TYPE) {
      return;
    }
    const { height } = data;
    if (typeof height !== 'number' || !Number.isFinite(height) || height <= 0) {
      return;
    }
    const frames = document.querySelectorAll('iframe[data-planafolio-embed]');
    const frame = Array.prototype.find.call(
      frames,
      (candidate) => candidate.contentWindow === event.source,
    );
    if (frame) {
      frame.style.height = `${Math.min(height, 4000)}px`;
    }
  });
})();
