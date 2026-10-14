# TechStore database schema

This document describes the schema declared by Laravel migrations. It contains
no row data. **The migrations in `backend/database/migrations/` are the source
of truth**; update this reference when those migrations change. It is not a
claim that an arbitrary pre-existing MySQL database has no schema drift.

## Commerce tables

| Table | Purpose and principal columns | Constraints and relationships |
|---|---|---|
| `users` | `id`, `name`, `email`, `phone`, `address`, `email_verified_at`, `password`, `role`, remember token, timestamps | Unique email; indexed role. |
| `categories` | `id`, `name`, `slug`, `description`, `status`, timestamps | Unique slug. |
| `products` | `id`, `category_id`, `name`, `slug`, `sku`, `price`, `sale_price`, `stock`, `thumbnail`, `description`, `is_featured`, `status`, timestamps | Category is restricted from deletion; unique slug and nullable unique SKU; indexes on `(category_id, status)` and `(is_featured, status)`. |
| `product_images` | `id`, `product_id`, `image_path`, `sort_order`, timestamps | Product deletion cascades to its gallery images. |
| `carts` | `id`, `user_id`, timestamps | One cart per user; user deletion cascades. |
| `cart_items` | `id`, `cart_id`, `product_id`, `quantity`, timestamps | Unique `(cart_id, product_id)`; cart/product deletion cascades. |
| `orders` | `id`, nullable `user_id`, `order_code`, customer/contact/shipping fields, `subtotal`, `shipping_fee`, `total`, payment method/status, order status, `completed_at`, timestamps | Unique order code; deleting a user keeps the order and nulls `user_id`; indexes on `(user_id, status)` and `created_at`. |
| `order_items` | `id`, `order_id`, nullable `product_id`, product name/SKU snapshots, `price`, `quantity`, `subtotal`, timestamps | Order deletion cascades; product deletion preserves the item and nulls `product_id`. |
| `reviews` | `id`, `user_id`, `product_id`, `rating`, `comment`, `status`, timestamps | Unique `(user_id, product_id)`; user/product deletion cascades. |

## Laravel infrastructure tables

`cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, and
`password_reset_tokens` are defined by Laravel migrations for framework
services. They are not storefront entities.

## Relationship overview

```text
users 1---1 carts 1---* cart_items *---1 products *---1 categories
users 1---* orders 1---* order_items *---0..1 products
users 1---* reviews *---1 products 1---* product_images
```

Foreign-key delete behavior is intentionally different by relationship:
cart contents and reviews cascade; order history is retained when its user or
product is removed. Product/category deletion restrictions and order snapshots
preserve catalog and purchase-history integrity.

## MySQL integration test database

`phpunit.mysql.xml` targets only `techstore_codex_test`. Create that dedicated
empty database, then run the normal additive command
`php artisan migrate --force` with `DB_CONNECTION=mysql`,
`DB_HOST=127.0.0.1`, `DB_PORT=3306`, and
`DB_DATABASE=techstore_codex_test` in the process environment. Credentials are
read from the developer's existing local environment. In PowerShell, set those
four variables for the migration command, run `php artisan migrate --force`,
then run `vendor/bin/phpunit --configuration=phpunit.mysql.xml`. The MySQL test
bootstrap refuses a different database name; the test configuration does not
use `migrate:fresh`. Apply pending migrations before testing.
