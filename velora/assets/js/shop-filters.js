(function () {
  const panel = document.getElementById('velora-filters');
  const openBtn = document.querySelector('[data-filter-open]');
  const closeBtn = document.querySelector('[data-filter-close]');
  if (!panel) return;
  const open = () => panel.classList.add('is-open');
  const close = () => panel.classList.remove('is-open');
  if (openBtn) openBtn.addEventListener('click', open);
  if (closeBtn) closeBtn.addEventListener('click', close);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });
})();
