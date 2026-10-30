(function ($) {
  const drawer = document.getElementById('velora-minicart');
  const openBtns = document.querySelectorAll('[data-cart-open]');
  const closeBtn = document.querySelector('[data-cart-close]');

  const open = () => {
    if (!drawer) return;
    drawer.hidden = false;
  };
  const close = () => {
    if (!drawer) return;
    drawer.hidden = true;
  };

  openBtns.forEach((btn) => btn.addEventListener('click', open));
  if (closeBtn) closeBtn.addEventListener('click', close);
  if (drawer) {
    drawer.addEventListener('click', (e) => {
      if (e.target === drawer) close();
    });
  }

  // WooCommerce cart fragments refresh mini-cart HTML after add-to-cart.
  $(document.body).on('added_to_cart', function () {
    open();
  });
})(jQuery);
