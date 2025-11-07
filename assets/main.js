const message = 'Vite est correctement configuré et prêt à compiler vos assets !';

function enhanceHero() {
  const target = document.querySelector('[data-js-hook="vite-message"]');

  if (!target) {
    return;
  }

  const highlight = document.createElement('span');
  highlight.className = 'badge text-bg-success ms-2';
  highlight.textContent = 'powered by Vite';

  target.appendChild(highlight);
  target.dataset.jsEnhanced = 'true';
}

window.addEventListener('DOMContentLoaded', () => {
  console.info(message);
  enhanceHero();
});
