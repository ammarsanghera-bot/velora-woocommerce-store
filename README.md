# VELORA — Premium Custom WooCommerce Store

**Portfolio demonstration store** with fictional fashion & lifestyle products. Built as a real WordPress + WooCommerce project (custom theme + companion plugin), not a React/static/Elementor demo.

> Tagline: *Everyday Essentials. Elevated.*

---

## Screenshots

Add captures to `screenshots/` after local bootstrap (see that folder’s README). Suggested: homepage, shop, single product, cart/checkout.

---

## Features

- Custom **velora** theme (premium editorial UI, sticky header, mini-cart drawer, responsive shop)
- Companion **velora-core** plugin (product meta fields, badges, wishlist, AJAX, demo import, hooks)
- Native WooCommerce cart, checkout, orders, My Account, coupons, sorting
- Shop filters: category, price, size, color, rating (real reviews), stock — works without JS
- Variable product demo with size/color variations
- Guest + logged-in wishlist (cookie / user meta)
- Newsletter UI with validation + `velora_core_newsletter_subscribe` hook (no fake email storage claim)
- SEO helpers (OG basics, semantic markup) compatible with Yoast / Rank Math
- Docker Compose local environment (WordPress 6.7, PHP 8.2, MySQL 8)
- Stripe sandbox documented via official gateway plugin — **no hardcoded keys**

---

## Technology stack

| Layer | Choice |
|--------|--------|
| CMS | WordPress 7+ (current WooCommerce requires WP 7; Docker image updates via `wp core update`) |
| Ecommerce | WooCommerce 11.x |
| Language | PHP 8.1+ |
| Front-end | HTML5, CSS3, vanilla JS |
| DB | MySQL 8 |
| Payments | Official WooCommerce Stripe Gateway (test mode) or COD for local smoke tests |

---

## Project structure

```
velora-woocommerce-store/
├── velora/                 # Custom theme
│   ├── style.css
│   ├── functions.php
│   ├── front-page.php
│   ├── header.php / footer.php
│   ├── woocommerce/        # Minimal overrides only
│   ├── inc/                # setup, enqueue, WC, SEO, performance
│   ├── template-parts/
│   └── assets/
├── velora-core/            # Companion plugin
│   ├── velora-core.php
│   └── includes/
├── bin/bootstrap.sh
├── docker-compose.yml
├── .env.example
├── screenshots/
└── README.md
```

---

## Quick start (Docker)

**Requirements:** Docker Desktop.

```bash
cd velora-woocommerce-store
docker compose up -d
```

Wait ~30–60s for MySQL + WordPress, then bootstrap:

```bash
# Ensure bootstrap script has Unix line endings inside the container
docker compose run --rm --entrypoint sh wpcli -c "tr -d '\r' < /scripts/bootstrap.sh | sh"
```

Or step-by-step with WP-CLI:

```bash
docker compose run --rm wpcli core install \
  --url=http://localhost:8081 \
  --title="VELORA" \
  --admin_user=admin \
  --admin_password="Velora2026!" \
  --admin_email=admin@velora.local \
  --skip-email

docker compose run --rm wpcli plugin install woocommerce --activate
docker compose run --rm wpcli plugin activate velora-core
docker compose run --rm wpcli theme activate velora
docker compose run --rm wpcli eval 'echo velora_core_import_demo_products();'
```

| Service | URL / port |
|---------|------------|
| Storefront | http://localhost:8081 |
| WP Admin | http://localhost:8081/wp-admin |
| Default admin | `admin` / `Velora2026!` |
| MySQL host port | `3308` |

Stop:

```bash
docker compose down
```

Data volumes persist until `docker compose down -v`.

---

## Install theme & plugin (non-Docker)

1. Copy `velora/` → `wp-content/themes/velora`
2. Copy `velora-core/` → `wp-content/plugins/velora-core`
3. Install & activate **WooCommerce**
4. Activate **Velora Core**, then **Velora** theme
5. Run WooCommerce setup wizard (currency, country, pages)
6. **WooCommerce → Velora → Import demo products**

---

## WooCommerce store configuration

Recommended demo settings:

| Setting | Value |
|---------|--------|
| Country | United States (or your market) |
| Currency | USD |
| Guest checkout | Enabled |
| Account registration | Enabled |
| Taxes | Optional for demo |
| Shipping | Flat rate / free shipping zone for “Demo” |

Ensure pages exist and are assigned:

- Shop, Cart, Checkout, My Account (WooCommerce → Settings → Advanced)
- Privacy Policy, Terms, Shipping & Returns (created by bootstrap)

---

## Sample product import

**Idempotent by SKU** — re-running skips existing products.

- Admin: **WooCommerce → Velora → Import demo products**
- CLI: `wp eval 'echo velora_core_import_demo_products();'`

Creates 12+ fictional products across Women, Men, Bags, Lifestyle, Footwear, Accessories, plus one variable tee (`VEL-VAR-TEE`). All descriptions state they are demo content.

Upload your own licensed product photography in the product editor; placeholders appear until then.

---

## Stripe sandbox setup

1. Install official plugin: **WooCommerce Stripe Payment Gateway**  
   `wp plugin install woocommerce-gateway-stripe --activate`
2. Create a [Stripe test account](https://dashboard.stripe.com/test/apikeys)
3. Copy **test** publishable + secret keys into  
   **WooCommerce → Settings → Payments → Stripe**
4. Enable test mode; never put keys in theme/plugin code or Git
5. Use placeholders from `.env.example` only as a personal reminder file (gitignored `.env`)

### Test cards (Stripe docs)

| Scenario | Card |
|----------|------|
| Success | `4242 4242 4242 4242` |
| Decline | `4000 0000 0000 0002` |
| Auth required | `4000 0025 0000 3155` |

Use any future expiry, any CVC, any ZIP. Confirm order status updates in **WooCommerce → Orders**.

**Without Stripe keys:** enable **Cash on Delivery** for local cart/checkout smoke tests (bootstrap enables COD when possible). Real card flows require the official gateway + your test keys.

---

## Theme architecture

- Presentation & templates only; ecommerce logic stays in WooCommerce + Velora Core
- Hooks preferred over template copies (`inc/woocommerce.php`)
- Overrides: `woocommerce/archive-product.php`, `woocommerce/content-product.php` (versioned headers)
- Assets versioned with `filemtime()` in development
- Design tokens: `#F8F7F3` bg, `#A58A63` accent, Playfair Display + Inter

---

## Companion plugin architecture

| File | Responsibility |
|------|----------------|
| `product-fields.php` | Subtitle, material, care, highlight, dispatch |
| `product-badges.php` | Sale / Featured / New badges |
| `wishlist.php` | Cookie + user meta wishlist + shortcode |
| `ajax-handlers.php` | Wishlist toggle, newsletter stub |
| `custom-hooks.php` | Single product extras, order status log, ATC note |
| `admin-settings.php` | Settings + demo import UI |
| `demo-import.php` | Idempotent SKU-based sample catalog |
| `security.php` | Shared AJAX nonce helper |

---

## Hooks & filters (selected)

| Hook | Purpose |
|------|---------|
| `after_setup_theme` | Theme supports, WC gallery, menus, image sizes |
| `wp_enqueue_scripts` | Styles/scripts + localized AJAX nonce |
| `pre_get_posts` | Secure shop filter query args |
| `woocommerce_add_to_cart_fragments` | Live cart count |
| `woocommerce_single_product_summary` | Custom meta + wishlist button |
| `woocommerce_after_add_to_cart_button` | Shipping/returns note |
| `woocommerce_order_status_changed` | Debug log (WP_DEBUG only) |
| `velora_core_newsletter_subscribe` | Connect a real ESP |

---

## Security

- Sanitize input (`sanitize_text_field`, `sanitize_email`, `absint`)
- Escape output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`)
- Nonces + `check_ajax_referer` on custom AJAX
- Capability checks on admin import / settings
- Wishlist validates product post type; no payment data stored
- No hardcoded Stripe secrets

---

## Performance

- Responsive image sizes (`velora-product`, `velora-hero`, `velora-category`)
- Lazy-load below-the-fold where browsers allow
- Minimal custom JS; WC cart fragments retained for mini-cart
- Efficient product queries; filters use main query + meta/tax queries

---

## Testing checklist

Mark items only after you run them:

1. Theme activation  
2. Plugin activation  
3. WooCommerce install  
4. Category & product listing  
5. Filters + sorting  
6. Simple + variable add to cart  
7. Cart qty update / remove / coupon  
8. Checkout validation  
9. Guest checkout / My Account  
10. Wishlist add/remove  
11. Search  
12. Stripe test success / decline (when keys configured)  
13. Order status changes  
14. Mobile nav + responsive layouts  
15. Browser console / PHP error log  

### Syntax check (host with PHP CLI)

```bash
find velora velora-core -name "*.php" -print0 | xargs -0 -n1 php -l
```

---

## Deployment notes

1. Deploy theme + plugin to a PHP 8.1+ host with MySQL  
2. Install WooCommerce + Stripe gateway  
3. Configure SSL, permalinks, shipping, taxes  
4. Import demo or replace with real catalog  
5. Set production Stripe keys only in WP admin (never in repo)  
6. Clear caches after deploy  

---

## Known limitations

- Demo product images are placeholders until you upload licensed photography  
- Newsletter does not persist subscribers until you hook an ESP  
- Stripe card tests require your own test API keys  
- Local Docker uses port **8081** (8080 may be used by other projects)  
- This is a **portfolio demo** with fictional products — not a live retail brand  

---

## License

GPL-2.0-or-later (WordPress theme/plugin convention).
