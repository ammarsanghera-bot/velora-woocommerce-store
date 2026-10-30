#!/usr/bin/env sh
set -e
URL="${WP_URL:-http://localhost:8081}"

if ! wp core is-installed --allow-root; then
  wp core install \
    --url="$URL" \
    --title="VELORA" \
    --admin_user="admin" \
    --admin_password="Velora2026!" \
    --admin_email="admin@velora.local" \
    --skip-email \
    --allow-root
fi

# Prefer current WooCommerce; requires WordPress 7+ (run `wp core update` if needed).
wp core update --allow-root || true
wp plugin install woocommerce --activate --allow-root || true
wp plugin activate velora-core --allow-root || true
wp theme activate velora --allow-root || true

wp option update blogdescription "Everyday Essentials. Elevated." --allow-root
wp option update woocommerce_store_address "100 Demo Street" --allow-root
wp option update woocommerce_store_city "Demo City" --allow-root
wp option update woocommerce_default_country "US:CA" --allow-root
wp option update woocommerce_currency "USD" --allow-root
wp option update woocommerce_calc_taxes "no" --allow-root
wp option update woocommerce_enable_guest_checkout "yes" --allow-root
wp option update woocommerce_enable_myaccount_registration "yes" --allow-root

# Create pages if missing
create_page() {
  NAME="$1"; TITLE="$2"; CONTENT="$3"
  ID=$(wp post list --post_type=page --name="$NAME" --field=ID --allow-root | head -n1)
  if [ -z "$ID" ]; then
    ID=$(wp post create --post_type=page --post_title="$TITLE" --post_name="$NAME" --post_status=publish --post_content="$CONTENT" --porcelain --allow-root)
  fi
  echo "$ID"
}

HOME_ID=$(create_page home Home "")
SHOP_ID=$(wp option get woocommerce_shop_page_id --allow-root 2>/dev/null || true)
CART_ID=$(wp option get woocommerce_cart_page_id --allow-root 2>/dev/null || true)
CHECKOUT_ID=$(wp option get woocommerce_checkout_page_id --allow-root 2>/dev/null || true)
ACCOUNT_ID=$(wp option get woocommerce_myaccount_page_id --allow-root 2>/dev/null || true)

# WooCommerce usually creates shop/cart/checkout/account on activation.
wp option update show_on_front page --allow-root
wp option update page_on_front "$HOME_ID" --allow-root

create_page shipping-returns "Shipping & Returns" "Fictional shipping and returns policy for the Velora portfolio demo."
create_page terms "Terms & Conditions" "Fictional terms for the Velora portfolio demonstration store."
PRIVACY=$(create_page privacy-policy "Privacy Policy" "Fictional privacy policy for the Velora demo store.")
wp option update wp_page_for_privacy_policy "$PRIVACY" --allow-root

wp rewrite structure '/%postname%/' --allow-root
cat > /var/www/html/.htaccess <<'EOF'
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
EOF
chown 33:33 /var/www/html/.htaccess || true
wp rewrite flush --allow-root

# Demo products via plugin function
wp eval 'echo (int) velora_core_import_demo_products();' --allow-root

# Menu
MENU=$(wp menu list --fields=term_id,name --format=csv --allow-root | awk -F, '$2=="Primary"{print $1; exit}')
if [ -z "$MENU" ]; then
  MENU=$(wp menu create "Primary" --porcelain --allow-root)
fi
wp menu item add-custom "$MENU" "New Arrivals" "$URL/shop/" --allow-root || true
wp menu item add-custom "$MENU" "Women" "$URL/product-category/women/" --allow-root || true
wp menu item add-custom "$MENU" "Men" "$URL/product-category/men/" --allow-root || true
wp menu item add-custom "$MENU" "Accessories" "$URL/product-category/accessories/" --allow-root || true
wp menu item add-custom "$MENU" "Lifestyle" "$URL/product-category/lifestyle/" --allow-root || true
wp menu location assign "$MENU" primary --allow-root || true

# Prefer classic shortcode cart/checkout for theme hook compatibility.
CART_ID=$(wp option get woocommerce_cart_page_id --allow-root 2>/dev/null || true)
CHECKOUT_ID=$(wp option get woocommerce_checkout_page_id --allow-root 2>/dev/null || true)
if [ -n "$CART_ID" ] && [ "$CART_ID" != "0" ]; then
  wp post update "$CART_ID" --post_content='[woocommerce_cart]' --allow-root || true
fi
if [ -n "$CHECKOUT_ID" ] && [ "$CHECKOUT_ID" != "0" ]; then
  wp post update "$CHECKOUT_ID" --post_content='[woocommerce_checkout]' --allow-root || true
fi

# COD for local testing without Stripe keys
wp eval 'if ( function_exists( "WC" ) ) { WC()->payment_gateways(); $g = WC()->payment_gateways->payment_gateways()["cod"] ?? null; if ( $g ) { $g->update_option( "enabled", "yes" ); } }' --allow-root || true

wp option update woocommerce_coming_soon no --allow-root || true

echo "VELORA ready at $URL"
echo "Admin: $URL/wp-admin  user=admin  pass=Velora2026!"
echo "Install official WooCommerce Stripe Gateway for card test mode; never commit keys."
