(function () {
  const header = document.getElementById('site-header');
  const toggle = document.querySelector('.velora-nav-toggle');
  const nav = document.getElementById('site-nav');

  const onScroll = () => {
    if (!header) return;
    const threshold = header.classList.contains('velora-header--transparent') ? 48 : 8;
    header.classList.toggle('is-scrolled', window.scrollY > threshold);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  document.querySelectorAll('.velora-wishlist-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
      if (!window.veloraData) return;
      const body = new FormData();
      body.append('action', 'velora_toggle_wishlist');
      body.append('nonce', veloraData.nonce);
      body.append('product_id', btn.dataset.productId);
      const res = await fetch(veloraData.ajaxUrl, { method: 'POST', body, credentials: 'same-origin' });
      const json = await res.json();
      if (!json.success) return;
      const added = json.data.action === 'added';
      btn.setAttribute('aria-pressed', added ? 'true' : 'false');
      btn.textContent = added ? 'Remove from Wishlist' : 'Add to Wishlist';
    });
  });

  const form = document.querySelector('[data-newsletter-form]');
  if (form && window.veloraData) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = form.querySelector('input[type="email"]').value;
      const msg = form.querySelector('.velora-newsletter__msg');
      const body = new FormData();
      body.append('action', 'velora_newsletter');
      body.append('nonce', veloraData.nonce);
      body.append('email', email);
      const res = await fetch(veloraData.ajaxUrl, { method: 'POST', body, credentials: 'same-origin' });
      const json = await res.json();
      msg.textContent = json.success ? json.data.message : (json.data && json.data.message) || 'Something went wrong.';
    });
  }
})();
