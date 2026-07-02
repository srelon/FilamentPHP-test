# Database Schema — Shop Domain

Reference for the tables added on top of the initial RBAC scaffold (`users`, `admins`, `admin_roles`, `admin_accesses`). All shop-domain tables use `timestamps()` + `softDeletes()`.

Migrations: `backend/database/migrations/2026_07_02_*`. Models: `backend/app/Models/*`.

## Status field reference

Project convention: **`4` is reserved for cancel/delete-type states** across enums (not `2`/`3`), so a sort/filter on "is this dead" only ever has to check one value. Fields marked "not explicitly defined" got a plain `status` column added on request but the exact value meaning was never pinned down in discussion — they default to the same 0/inactive / 1/active pattern as the rest of the project; confirm before building a Filament form around them.

| Table.column                    | Values                                                                                                                                                                                                             |
| ------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `products_categories.status`    | not explicitly defined — 0 inactive / 1 active (by analogy)                                                                                                                                                     |
| `news_categories.status`        | not explicitly defined — 0 inactive / 1 active                                                                                                                                                                  |
| `products.status`               | `0` created / `1` active (shown in search and filters) / `2` archived (reachable only via direct link, not searchable) / `3` reserved for future use / `4` deleted                                                |
| `products.bestseller`           | `0`/`1` — a flat flag, not a lifecycle status                                                                                                                                                                   |
| `product_stocks.status`         | `0` created / `1` active / `2` fulfilled / `3` queued / `4` cancelled                                                                                                                                             |
| `reviews.status`                | inherited from the source project (comments), `default 1`; no exact value list survived there either (before `recreate_comments_table` it was `pending/approved/rejected`) — confirm before building moderation |
| `reviews.deleted_by`            | `null` = deleted normally (by user/admin) / `1` = deleted by a moderator (shows a placeholder in its place)                                                                                                       |
| `review_likes.opp_type`         | `0` cancelled / `1` dislike / `2` like                                                                                                                                                                            |
| `carts.status`                  | `0` created / `1` completed (after checkout)                                                                                                                                                                      |
| `cart_items.status`             | `0` created / `1` ordered / `4` deleted                                                                                                                                                                           |
| `products_favorites.status`     | `0` cancelled (clicking again toggles, doesn't delete the row) / `1` active                                                                                                                                       |
| `delivery_services.status`      | not explicitly defined — 0 inactive / 1 active                                                                                                                                                                  |
| `delivery_branches.status`      | `0` inactive / `1` active                                                                                                                                                                                         |
| `payments.status`               | not explicitly defined — 0 inactive / 1 active                                                                                                                                                                  |
| `orders.status`                 | `0` placed / `1` shipped / `2` delivered / `3` paid / `4` cancelled                                                                                                                                               |
| `order_items.status`            | `0` pending / `1` shipped / `2` delivered / `3` completed / `4` cancelled                                                                                                                                         |
| `news_posts.status`             | `0` inactive / `1` active                                                                                                                                                                                         |
| `contact_messages.status`       | not explicitly defined — 0 new / 1 handled                                                                                                                                                                      |
| `newsletter_subscribers.status` | `0` unsubscribed / `1` subscribed (default `1`)                                                                                                                                                                   |

## Catalog

**products_categories** *(model `ProductsCategory`, table renamed from `categories` to avoid confusion with `news_categories`)* — `name, slug, icon, image, sort_order, status`. Product count is never stored, always `products()->count()`.

**products_authors** *(model `ProductsAuthor`, table renamed from `authors` for the same reason as `products_categories`)* — `name, slug, avatar_color, photo, content`. Same idea — `books` is `products()->count()`, not a column.

**products** — `category_id → products_categories`, `author_id → products_authors`, `title, slug, short_description, description, rating_avg, reviews_count, bestseller, status, published_at`.
Price and stock are **not here** — see `product_stocks`. For product cards/pages, always read price/availability off `Product::activeStock` (the row with `status = 1`) — never add a price column back onto `products`.

**product_images** — `product_id → products, image, sort_order`.

**Image columns are JSON, not a single path string** — `products_categories.icon`, `products_categories.image`, `products_authors.photo`, `product_images.image`, `news_posts.image` all store an object of size/format variants (e.g. `{"original": "...", "thumb": "..."}`) cast to `array` on the model, rather than one plain path. Exact variant keys aren't finalized yet — decide when the upload/image-processing service gets built.

**product_stocks** — a batch/lot system for inventory, not a single column on the product:
`product_id → products, quantity, price, before_price (nullable, pre-discount price), real_price (purchase/cost price), sort (default 999), status`.
A product can have several rows at once: one `active` (currently on sale), others `queued` (next batch at a different price), old ones `fulfilled`/`cancelled`. `order_items` points at a specific row (`product_stock_id`), so price and stock are decremented against the exact batch, not the product in general.

## Reviews and notifications

Ported almost 1:1 from another existing project (its `comments`/`comment_likes`/`comment_reports`/`user_notifications` tables), with `comment_id → review_id`. The moderation/likes/notifications logic for that structure is already built and working there, so it wasn't redesigned here.

Unlike the source project, `reviews` is **not** product-specific — it's polymorphic (`type`/`record_id`, aliased through the same morph map as `seo_meta`) so it can be reused for any content, not just `products`. `Product::reviews()` and `NewsPost::reviews()` are both `morphMany(Review::class, 'reviewable', 'type', 'record_id')`, sharing one table instead of duplicating the whole structure per content type.

**reviews** *(= `comments`, model `Review`)* — `type, record_id (the reviewed record — e.g. type "products" + a product id), user_id (nullable, nullOnDelete — but a row can only be created while authenticated, there is no guest path), parent_id (self, replies), replied_to_comment_id, rating (nullable, only on the root review — the one field that wasn't in the original), body, status, deleted_by`.

**review_likes** *(= `comment_likes`, model `ReviewLike`)* — `review_id, user_id, opp_type`, unique(`review_id, user_id`).

**review_reports** *(= `comment_reports`, model `ReviewReport`)* — `review_id, user_id, reason`, unique(`review_id, user_id`).

**user_notifications** *(= `user_notifications`, logic not implemented yet, structure only)* — `user_id, from_user_id (nullable, no FK), type (system/reply/like/dislike), data (json), product_id (nullable), review_id (nullable), parent_id (nullable, root review, no FK), read_at`.

## Cart / Favorites

**carts** — `user_id (unique)`, `status`. One cart per user.
**cart_items** — `cart_id → carts, product_id → products, quantity, status`.

Guest carts are **not stored in the DB** — client-side only (Pinia store + localStorage). Only an authenticated user's cart is persisted (so it syncs across devices).

**products_favorites** *(model `ProductsFavorite`, table renamed from `favorites`)* — `user_id → users, product_id → products, status`, unique(`user_id, product_id`). Clicking "favorite" again toggles `status` (0/1) instead of deleting the row.

## Delivery / Payment

**delivery_services** — carrier directory (Nova Poshta, self-pickup, etc.): `name, key (unique — for API lookups), status, price (rough estimate, informational only)`. Self-pickup is just a row here, not a `null` sentinel.

**delivery_branches** — actual branches/pickup points, synced from a carrier API: `delivery_id → delivery_services, city, branch, status, hash (unique — dedupes API data)`.

**payments** — payment method directory: `name, key, status`. Extensible for future payment gateways.

## Orders

**order_contacts** — `first_name, last_name, phone, email`. Reused across a customer's orders instead of duplicating the fields directly on `orders`.

**orders** — `public_id (unique — used in the order URL/status page instead of the real id), user_id (nullable — guest checkout), contact_id → order_contacts, delivery_id → delivery_services (required), delivery_branch_id → delivery_branches (nullable — not needed for self-pickup), payment_id → payments, status, paid_amount (nullable — a separate lump-sum amount for card payment of the whole order), txid (unique, auto-generated), tracking_number (nullable)`.

`public_id` and `txid` are **auto-generated** in `Order::booted()` (`static::creating`) — never set them manually on create.

**order_items** — `order_id → orders, product_id → products, product_stock_id → product_stocks, price (snapshot at order time), quantity (ordered), fact_quantity (actually shipped), paid_amount (actually paid for this line), status`. Title/author/image are not duplicated — pulled through `product_id`.

`orders.status` and `order_items.status` are **independent**: one order can have some items cancelled and others delivered. Planned admin flow (not implemented yet): set `order_items.status` first, then setting `orders.status` back-fills any untouched items.

## Content

**news_categories** — `name, slug, status`.
**news_posts** — `title, slug, category_id → news_categories, excerpt, content, image, author_id → admins (nullable), published_at, status`.
**contact_messages** — `first_name, last_name, email, subject, message, status`.
**newsletter_subscribers** — `email (unique), status`.

## Seed data

`database/seeders/` has one seeder per table group (`ProductsCategorySeeder`, `ProductsAuthorSeeder`, `ProductSeeder`, `NewsCategorySeeder`, `NewsPostSeeder`, `DeliveryServiceSeeder`, `DeliveryBranchSeeder`, `PaymentSeeder`), all wired into `DatabaseSeeder`. Every value is taken from the frontend's hardcoded mock data (`ProductList.vue`, `AuthorList.vue`, `NewsList.vue`/`NewsPage.vue`, `DeliveryStep.vue`, `PaymentStep.vue`) rather than invented — the two exceptions are `product_stocks.quantity` (frontend has no stock numbers at all, so every seeded product gets a flat placeholder of 50) and `delivery_branches.hash` (synthesized as `md5("{city}|{branch}")` since there's no real carrier API yet).

`ProductList.vue`'s mock data references two authors (Theodore Langley, Autumn Journey's Samuel Wright) that `AuthorList.vue` never gives a bio/avatar color to. Rather than seed authors with no real data, `ProductsAuthorSeeder` only has the 6 authors `AuthorList.vue` actually describes, and `ProductSeeder` drops the 2 products that only those missing authors wrote (Anxiety Unmasked, Autumn Journey) — 7 products, 6 authors seeded, not 9/8.

Images referenced by the seeders were copied from `frontend/public/images/` into `backend/storage/app/public/{products,products_categories,news}/` (served through the existing `public/storage` symlink) and are stored as `{"original": "products/book-image-19.webp"}` — relative to the `public` disk root, only the `original` variant populated (see the image-JSON note above).

## SEO

**seo_meta** — one shared table for SEO fields instead of duplicating `seo_title`/`seo_description`/`seo_keywords` on every content table: `type, record_id, seo_title, seo_description, seo_keywords`, unique(`type, record_id`).

`type` stores a short alias (the target's table name — `products`, `products_categories`, `news_posts`), not a PHP class name. This is wired through Eloquent's morph map (`Relation::enforceMorphMap` in `AppServiceProvider::boot()`), so `record_id`/`type` still work as a normal polymorphic relation (`Product::seo()`, `ProductsCategory::seo()`, `NewsPost::seo()` are all `morphOne(SeoMeta::class, 'seo', 'type', 'record_id')`) while keeping the column human-readable.

## Auth

Customer-facing auth (the `users` guard/table) uses **Sanctum SPA (cookie) authentication** — matches the frontend's `axios.ts` (`withCredentials: true`). Admin panel auth (the `admins` guard) is untouched and stays separate.

Infra wired up (no login/register endpoints yet):
- `routes/api.php` registered in `bootstrap/app.php`, with `$middleware->statefulApi()` enabled
- `config/sanctum.php` — `guard => ['web']` (the `web` guard already points at the `users` provider)
- `config/cors.php` — `supports_credentials: true`, `allowed_origins` derived from `SANCTUM_STATEFUL_DOMAINS` (scheme added automatically: `http://` in local, `https://` otherwise)
- `.env` — `SANCTUM_STATEFUL_DOMAINS` must be bare `host:port` entries (no scheme) — Sanctum matches against the request's Origin/Referer host directly; CORS derives its own scheme-prefixed origins from the same variable

## API response format

Ported from the same source project as the reviews structure: `App\Traits\RespondTrait` (used by the base `Controller`), giving every controller `respondWithJson($content, $status = 200)` → `{data, status}`, `respondWithError($message, $code = 400)` → `{status, errors}`, and `paginationMeta($paginated)` for a compact pagination envelope (`current_page, last_page, total, prev_page_url, next_page_url` — the latter two are just `'prev'`/`'next'`/`null` flags, not real URLs).

## Planned business logic (not implemented yet)

These are specified precisely so the eventual implementation matches what was agreed, not a re-guess:

**Stock availability** — for a given `product_stocks` row, available quantity is:

```
available = product_stocks.quantity
    - SUM(order_items.quantity WHERE product_stock_id = X AND status IN (0, 1, 2, 3))
```

Status `4` (cancelled) is excluded — a cancelled order item releases its reservation. This must be checked in two places: when placing an order (reject/adjust if not enough left) and when showing the cart to the user (silently trim items that are no longer available).

**Real-time stock updates** — whenever an `order_items` row is created or its `status` changes, recompute the available quantity for that product and broadcast it to every connected client for that `product_id`, using the existing Redis pub/sub → `websocket/server.js` pipeline (see CLAUDE.md § Real-time). Frontend product cards/pages should update live without a refresh.

**Search** — a dynamic autocomplete dropdown (type-ahead), scoped by the currently-selected category filter if any, matching against `products.title`, `products.short_description`, `products.description`. Each suggestion shows title → category (e.g. "Astral Journey → Fantasy") and links straight to the product page.

## Not implemented yet

- Filament admin resources (following the `BaseResource`/`BaseEditRecord` pattern)
- API controllers for the frontend (including auth login/register/logout endpoints)
- Review likes/reports/notifications handlers (structure is ready, no logic yet)
- Real card payment integration (`orders.txid`/`paid_amount` are reserved for it)
- Importing delivery branches from the carrier API (`delivery_branches`)
