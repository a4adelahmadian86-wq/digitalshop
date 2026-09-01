DigitalShop Notification System Patch

This patch adds a Laravel database notification center with:
- role-aware notifications (buyer/admin and any future role)
- per-notification category icon + accent/border/background
- unread badge and dropdown in the main header
- mark one/all as read
- full notification center
- daily product recommendations capped at 3 products per user
- duplicate protection for daily recommendations

Copy the files into C:\\xampp\\htdocs\\digitalshop preserving paths.
Then run:
  php artisan migrate
  php artisan optimize:clear

Recommended first test:
  php artisan digitalshop:recommendations --dry-run
  php artisan digitalshop:recommendations

IMPORTANT: The header partial is included below as a drop-in addition. Merge its notification block into your existing header rather than replacing the whole header unless you compare it first.
