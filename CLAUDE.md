# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Architecture

Monorepo with three independent services:

- `backend/` — Laravel 13 + FilamentPHP (API + Admin panel at `/admin`)
- `frontend/` — Vue 3 + TypeScript + Vite (public site)
- `websocket/` — Node.js WebSocket server (ws + ioredis + pino)

All services run in Docker. The entire stack is mounted as volumes — no image rebuilds needed for code changes, only for dependency changes.

## Docker

```bash
# First run / after Dockerfile changes
docker compose up -d --build

# Normal start
make up

# Stop everything
make down

# Start Vue dev server (port 5173)
make site
```

**Container names:** `filament_app`, `filament_nginx`, `filament_db`, `filament_redis`, `filament_scheduler`, `filament_websocket`

**Ports:** все на одном порту `8880` (`SITE_PORT` в `.env`) — API `/api`, Admin `/admin`, сайт `/`; Vue dev-сервер (`make site`) — `5173`, phpMyAdmin `8080`, WebSocket `6001`, MySQL `8101`

## Running commands in the backend container

The container WORKDIR is `/var/www` — always `cd /var/www/backend` first.

```bash
# One-off commands
docker exec -it filament_app bash -c "cd /var/www/backend && php artisan migrate"
docker exec -it filament_app bash -c "cd /var/www/backend && composer require vendor/package"

# Interactive shell
docker exec -it filament_app bash
cd /var/www/backend

# Common artisan commands
php artisan migrate
php artisan make:filament-resource ModelName --generate
php artisan make:model ModelName -mfs
php artisan tinker
```

## Shell scripts and permissions

`_docker/app/entrypoint.sh` and `scheduler-entrypoint.sh` are called via `sh script.sh` in docker-compose.yml — **do not** change this to a direct path call, as files created on Windows lose the `+x` bit. The `sh` wrapper bypasses this.

## Filament

After installing (`composer require filament/filament` inside the container):
```bash
php artisan filament:install --panels   # creates AdminPanelProvider
php artisan make:filament-user          # creates first admin user
php artisan make:filament-resource ModelName --generate
```

Filament files live in `backend/app/Filament/` (Resources, Pages, Widgets).

**Every "New X" button defaults to the `info` color, not Filament's stock `primary`** — set once, panel-wide, via `CreateAction::configureUsing(fn (CreateAction $action) => $action->color('info'))` in `AdminPanelProvider::boot()`. This applies automatically to every resource's create button (and any custom page's `CreateAction`, e.g. `MenuTree`) — don't set `->color('info')` per-resource/page, that's already redundant.

### RBAC

Every resource extends `BaseResource` (sets `$accessKey`) and every edit page extends `BaseEditRecord`. No separate View pages — the Edit page is used for both viewing and editing.

```
app/Filament/Resources/
    BaseResource.php      ← abstract, hasAccess() helper, all can* methods
    BaseEditRecord.php    ← abstract, hides Save + shows danger toast on unauthorized save
    Users/
        UserResource.php  ← extends BaseResource, $accessKey = 'users'
        Pages/
            ListUsers.php   ← CreateAction with ->visible(canCreate())
            CreateUser.php
            EditUser.php    ← extends BaseEditRecord, authorizeAccess() checks .view
```

- `canViewAny/canView` → requires `{key}.view`
- `canCreate/canEdit` → requires `{key}.edit`
- Form `->disabled(!static::hasAccess('edit'))` makes fields read-only for view-only users
- Never use `abort(403)` inside `beforeSave()` — use `Notification::make()->danger()->send()` + `$this->halt()` to show a toast instead of an error modal
- New access keys (any new `$accessKey`) must be added to `database/seeders/AccessesSeeder.php` in the same change, or the permission can never be assigned to a role
- Standalone `Filament\Pages\Page` subclasses (not a `Resource`, e.g. a page from a third-party package) can't extend `BaseResource` — replicate its `hasAccess(string $type)` helper locally instead, and gate visibility via `canAccess()` (the `Page`-level equivalent of `canViewAny`)

### Tree / nested UI (menus, and any future drag-and-drop hierarchy)

Use `solution-forest/filament-tree` — don't hand-roll drag-and-drop nesting. Column names are remapped in `config/filament-tree.php` (`order → sort_order`, `title → name`; `parent` stays `parent_id`) to match this project's naming conventions.

**`parent_id` must be `integer default(-1)`, not a nullable FK** — the package uses `-1` as its root sentinel (`ModelTree::defaultParentKey()`), and `-1` never has to match a real row so there's no FK constraint on the column. This is a deliberate deviation from the nullable-self-FK pattern used elsewhere; don't nullable-FK-ify it to match convention, it breaks the package's `isRoot()`/`scopeIsRoot()`/cascading-delete logic.

Model: `use SolutionForest\FilamentTree\Concern\ModelTree;`. Page: generate with `php artisan make:filament-tree-page {Name} --model={Model}`, then wire RBAC (see above) and `getFormSchema()` manually — the generated stub ships empty. See `Menu` / `App\Filament\Pages\MenuTree` for the reference implementation.

**Custom panel-wide CSS** (e.g. the drag-and-drop nesting highlight) goes in a small Blade partial under `resources/views/filament/`, wired via `->renderHook(PanelsRenderHook::STYLES_AFTER, fn () => view('filament.xxx'))` in `AdminPanelProvider` — don't edit anything under `vendor/`, it gets wiped on `composer update`/`vendor:publish --force`. See `resources/views/filament/menu-tree-styles.blade.php` (targets the tree package's `.dd-*` classes from `solution-forest/filament-tree`, which ships dbushell's Nestable JS — nesting happens by dragging an item right past a horizontal threshold, not by hovering over a target).

**Restructuring a package's own Blade markup** (not just adding CSS/JS around it — e.g. moving the tree's Save button to the page bottom, adding a Cancel button, relabeling "Save" → "Save changes") uses Laravel's standard package-view-override mechanism instead of a render hook: drop a same-named file at `resources/views/vendor/{package-view-namespace}/{path}.blade.php` — Laravel's view finder checks there before falling back to the package's own view. `solution-forest/filament-tree` registers its views under the `filament-tree::` namespace, so `filament-tree::components.tree.index` is overridden at `resources/views/vendor/filament-tree/components/tree/index.blade.php` (copy of the vendor original with the Save button moved below `.filament-tree.dd` into a `.menu-tree-bottom-actions` bar alongside a Cancel button using `x-on:click="$wire.$refresh()"` — Livewire 3's own "re-render from server state" magic method, discards any unsaved client-side drag state without any custom JS). Run `php artisan view:clear` after adding/editing an override, since Blade caches compiled views and won't otherwise notice a new override file exists.

For a Filament `Action`'s color (e.g. making "New menu" green), use the action's own `->color('success')` (Filament's semantic palette) rather than CSS — see `MenuTree::getCreateAction()`.

**Menu tree requires a manual Save click** — a client-side JS "auto-save on drop" was attempted and abandoned (2026-07-02) after five increasingly-careful implementations all failed in the real browser (wrong event, then debounce firing mid-drag, then firing on unrelated clicks, then a listener-accumulation bug plus a MutationObserver/mouseup race causing duplicate requests) despite the last version passing a 6-scenario jsdom test harness. **Don't re-attempt this without the user explicitly asking again** — see [[feedback-filament-tree]] for the full history of what was tried and why each attempt failed, so a future try doesn't repeat the same dead ends.

**Dynamic route params on the menu form** — when a field's options depend on another field picked in the same form (here: which model to search depends on the selected `route`), use a `Select::make('json_column.key')->searchable()->getSearchResultsUsing(fn (string $search, $get) => ...)->getOptionLabelUsing(fn ($value, $get) => ...)`, with the driving field marked `->live()`. This queries through the panel's existing Livewire round-trip (capped with `->limit(10)`, filtered by `$search`) — don't build a bespoke API endpoint + JS autocomplete for internal-admin-only lookups like this, `searchable()` already does it.

## Shop domain (backend database)

Full table/column/status reference: **`backend/docs/database.md`** — read it before writing migrations, models, or Filament resources for these tables. Migrations: `backend/database/migrations/2026_07_02_*`. Models: `backend/app/Models/*`.

Facts that aren't obvious from the schema alone:

- **Manual ordering columns are always named `sort_order`**, never `sort`/`order`/`position` — one name across the whole schema, including `menus` (remapped from the tree package's `order` config key).
- **Price/stock live in `product_stocks`, not `products`.** A product can have several stock rows (batches at different prices); `status` tracks which batch is active/queued/finished. `order_items.product_stock_id` pins an order line to the exact batch it was sold from.
- **`orders.status` and `order_items.status` are independent** — one order can have some items delivered and others cancelled. Admin flow (not built yet): set `order_items.status` first, then `orders.status`, which back-fills any untouched items.
- **`reviews`/`review_likes`/`review_reports`/`user_notifications` are ported 1:1 from another existing project** (its `comments`/`comment_likes`/`comment_reports`/`user_notifications` tables), with `comment_id → review_id`. Don't redesign this structure — the moderation/notification logic behind it already works there and will be ported later.
- **`reviews` is polymorphic (`type`/`record_id`), not product-specific** — `Product::reviews()` and `NewsPost::reviews()` are both `morphMany(Review::class, 'reviewable', 'type', 'record_id')`, sharing the same morph map as `seo_meta`. Don't add a `product_id`-only reviews table for a new content type — reuse this one.
- **`orders.public_id` and `orders.txid` are auto-generated** in `Order::booted()` (`static::creating`) — never set them manually.
- **Status convention:** across every status enum in this domain, `4` means cancelled/deleted, never `2`/`3`. Keep new statuses consistent with this.
- **Guest support differs by table.** Cart, checkout, orders all support guests (`user_id` nullable). `reviews` does **not** — reviews require authentication, no guest name/email fields.
- **`User::notifications()` is taken by Laravel's `Notifiable` trait** — the shop notifications relation is `User::userNotifications()`.
- **Several shop tables are prefixed `products_` for disambiguation, not their bare name** — `products_categories` (model `ProductsCategory`, vs. `news_categories`), `products_authors` (model `ProductsAuthor`), `products_favorites` (model `ProductsFavorite`). Don't reintroduce `categories`/`authors`/`favorites` as table or class names.
- **Image columns (`icon`, `image`, `photo`) are JSON, not a path string** — cast to `array`, storing size/format variants. Applies to `products_categories`, `products_authors`, `product_images`, `news_posts`.
- **SEO fields live in one shared `seo_meta` table**, not duplicated per content table — `Product::seo()`, `ProductsCategory::seo()`, `NewsPost::seo()` are `morphOne(SeoMeta::class, 'seo', 'type', 'record_id')`, with the morph map (`AppServiceProvider::boot()`) aliasing `type` to the target's table name instead of its class name.
- **Customer auth is Sanctum SPA (cookie-based)**, guard `web` / provider `users` — separate from the `admins` guard used by the Filament panel. `SANCTUM_STATEFUL_DOMAINS` in `.env` must stay bare `host:port` (no scheme); `config/cors.php` derives its own scheme-prefixed origins from that same variable.
- **`php artisan migrate:fresh --seed` gives a working demo catalog** — every shop table has a seeder in `database/seeders/`, sourced from the frontend's hardcoded mock data (not invented), with the referenced images copied into `storage/app/public/{products,products_categories,news}/`. See `backend/docs/database.md` § Seed data for the two placeholder exceptions (stock quantity, delivery branch hash).

## Backend API conventions — ALWAYS follow these

**Keep `backend/docs/database.md` current.** Any migration, model, or other change to the database (new table, new column, changed status meaning, dropped field) must update that doc in the same change — it's part of the change, not a follow-up task. Never let it drift out of sync with the actual schema.

**Tests ship with the API code that needs them.** When adding or changing an API endpoint, create or update its Pest feature test in the same change — don't leave that for a later pass. If a request/response contract changes, update the existing test alongside it so it never goes stale.

**Tests are class-based, not Pest's functional `it()`/`test()` style** — `class XTest extends TestCase { use RefreshDatabase; public function test_snake_case_description(): void { ... } }`, matching `ExampleTest.php` and the user's other project (`srelon/demo-news/backend/tests`). Factor repeated test-data setup into a reusable trait under `tests/Helpers/` (e.g. `ShopTestHelper::createCategory()/createProduct()/createMenuItem()/createContact()`) instead of duplicating `Model::create([...])` inline in every test method.

**Validation lives in Form Requests, never in controllers.** One request class per resource, shared between create and update instead of two near-duplicate classes — read the route's id param inside `rules()` to adjust rules that differ between the two (e.g. `Rule::unique(...)->ignore($id)` for edit vs. plain `unique` for create).

```php
class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', Rule::unique('products', 'slug')->ignore($id)],
        ];
    }
}
```

**No business logic in controllers.** A controller method only resolves a Request, calls a Service (`app/Services/`) or another dedicated layer (e.g. an Action), and returns the response — nothing else. All business logic belongs in the service layer.

**Group routes by resource, don't declare flat separate `Route::` calls.** One `Route::prefix('resource')->controller(Controller::class)->group(function () { ... })` block per resource area, e.g.:

```php
Route::prefix('news/{category}')->controller(NewsController::class)->group(function () {
    Route::get('/', 'category');
    Route::get('/{subcategory}', 'subcategoryNews');
    Route::get('/{subcategory}/articles', 'subcategoryArticles');
    Route::get('/{subcategory}/{slug}', 'article');
});
```

**Response shape comes from `App\Traits\RespondTrait`** (already `use`d in the base `Controller`) — never build the JSON envelope by hand in a controller:
- `$this->respondWithJson($content, $status = 200)` → `{data, status}`
- `$this->respondWithError($message, $code = 400)` → `{status, errors}`
- `$this->paginationMeta($paginated)` → `{current_page, last_page, total, prev_page_url, next_page_url}` (the last two are `'prev'`/`'next'`/`null` flags, not real URLs)

## Frontend (Vue 3 — `frontend/`)

### Structure

```
src/
  assets/scss/        ← global styles only
    _variables.scss   ← design tokens ($color-primary, $color-accent, $color-dark, $color-danger, etc.)
    _mixins.scss      ← shared mixins (form-field-label, form-field-base, form-field-error-text)
    _reset.scss       ← Google Fonts import, base reset
    _helpers.scss     ← .container, .section, .section__title, .dots
    main.scss         ← @forward variables, @use reset/helpers
  components/
    layout/           ← AppHeader, AppFooter, Layout
    ui/base/          ← BaseButton, BaseInput, BaseSelect, BaseRadioGroup, BaseTabs, BaseSlider
    ui/shop/          ← all page-section components (PageHero, ProductCard, etc.)
    ui/cart/          ← CheckoutStep, CartPopup (shared across cart/checkout views)
    ui/forms/         ← standalone form components (ContactForm, NewsletterForm) — vee-validate + yup, reusable across pages
  composables/        ← useCheckoutForm (localStorage-backed cart form state), useWizardStep (step draft/confirm pattern)
  views/Pages/        ← Home.vue (fetches its own page-scoped data once, passes it down to sections as props — see "Pinia store" rule below; otherwise assembles components only, no UI logic)
  views/Pages/Cart/   ← CartPage.vue (checkout wizard) + ContactStep/DeliveryStep/PaymentStep.vue
  stores/shop.ts      ← Pinia: cart_count
  stores/layout.ts    ← Pinia: categories/menu/contacts from GET /api/layout, fetched once in App.vue's onMounted
  types/shop.ts       ← plain TS interfaces for shop-domain display shapes (ProductSummary/AuthorSummary/BlogPostSummary) — not a store, see below
  routes/router.ts    ← Vue Router history mode
public/images/        ← static images served at /images/*.png
public/favicon.svg    ← square book-logo icon, matches AppHeader logo colors
```

### SCSS rules

- Vite `additionalData` auto-injects `@use "@/assets/scss/variables" as *;` and `@use "@/assets/scss/mixins" as *;` into every component
- **Never redeclare `$color-*` variables inside component `<style>`** — they come from global injection
- Form-field components (`BaseInput`, `BaseSelect`) share label/field/error styling via `@include form-field-label`, `@include form-field-base`, `@include form-field-error-text` from `_mixins.scss` — don't re-declare padding/border/focus styles per component
- `_reset.scss` and `_helpers.scss` each start with `@use 'variables' as *;` to access variables; `main.scss` pulls them together with `@forward 'variables'; @use 'reset'; @use 'helpers';`
- All component styles: `<style lang="scss" scoped>` with BEM naming

### Component rules

- All reusable components in `src/components/ui/` — never tie components to a specific page
- `ui/base/` — generic (BaseButton, BaseInput, BaseTabs, BaseSlider)
- `ui/shop/` — shop-specific but still reusable (ProductCard, ProductSlider, PageHero, etc.)
- `ui/forms/` — standalone forms usable from more than one place (ContactForm, NewsletterForm) — full vee-validate/yup form + submit logic, not just a field
- Views (`views/`) only assemble components — no inline styles, no UI logic
- Static images referenced in JS data → `public/images/` (Vite can't resolve dynamic `src/assets` paths)

### BaseSlider

Single slider component used by PageHero and ProductSlider. Never duplicate slider logic.

```ts
// Props
count: number          // total slides/pages
dot_style: 'rect' | 'diamond' | 'circle'
auto_play_ms: number   // 0 = disabled; PageHero uses 30000
model_value?: number   // optional v-model for parent to track active index

// Slot
#default="{ active }"  // scoped slot, active = current index
```

Handles: drag/swipe (threshold 30px), auto-play, dot clicks, v-model sync.

### Key design decisions

**Book store (current project):**
- **HeroSection** — `hero__top` (grid 1fr 1fr: H1 left, desc right) + `hero__bottom` (grid 2fr 1fr 1fr, min-height 500px); the 3 `hero__card`s are 3 random categories from `useLayoutStore().categories` (reshuffled each page load via a `computed`, not re-shuffled on every re-render), each linking to `{ path: '/products', query: { category } }`, image = category's `image` field via `to_storage_url()`; newsletter block in mid column uses `NewsletterForm.vue` (`ui/forms/`), not inline markup
- **AppHeader** — book SVG logo + "BookStore" text; Categories mega-menu is a separate element LEFT of Home nav link, controlled by `cats_open` ref + mouseenter/mouseleave on wrapper. Nav links, mega-menu categories, and the phone contact are all driven by `useLayoutStore()`, not hardcoded — hovering a mega-menu category swaps `header__mega-promo`'s image to that category's `image` (`hovered_category` ref, falls back to the first category); top-nav items with `children` get a `header__nav-dropdown` flyout (same mouseenter/mouseleave pattern as the categories button)
- **CategoryStrip** — CSS carousel (transform translateX), shows 8 items, auto-advances every 10s; reset without jump uses double `requestAnimationFrame` to skip transition for one frame; categories come from `useLayoutStore().categories` — `total`/`max_index`/`track_width`/`item_width` are `computed`, not plain values derived once, since the category list starts empty and populates async after `fetch_layout()` resolves
- **ProductCard** — extracted reusable component; hover slides action icons in from right (`translateX(60px) → 0`); `aspect-ratio: 2/3` on figure
- **BestAuthorSection** — award badges are 72px circles with `border: 2px solid $color-primary` and text inside — NO SVG icons. Takes the `products_authors` row (highest `SUM(bestseller)` across its products, see `backend/docs/database.md` § API response format) as an `author` prop from `Home.vue` — falls back to the static `/images/best-author-1.webp` when `author.photo` is null, which it is for every seeded author right now (no real author photos exist yet)
- **BestsellersSection** / **BestRatedSection** / **BlogSection** — title and description configurable via props with defaults; product/post data comes in as a `products`/`posts` prop from `Home.vue`, which fetches `GET /api/home` once in its own `onMounted` (bundles all 4 Home sections in one call, same shape as `useLayoutStore()`'s single `layout` fetch, just page-scoped state instead of a store — see the "Pinia store" rule above) and distributes slices down. These 4 sections stay purely presentational — no store, no fetch of their own.

**SVG fill in scoped styles** — CSS `fill` on `<svg>` does not reliably cascade to `<path>` in Vue scoped CSS. Always target child elements directly:
```scss
&__icon {
    path, circle { fill: $color-primary; }
}
```

**Card overlays** — always use `position: absolute; inset: 0` on the overlay, never `position: relative; height: 100%` — the latter collapses when the parent gets its height from flex.

**Pinia stores use the Composition API form** (`defineStore('name', () => { ... return {...} })` with `ref`/`computed`), matching `stores/shop.ts` — not the Options API form (`defineStore('name', { state, actions })`). Keep this even when porting a pattern from another project that used the Options form (e.g. `stores/layout.ts`'s `fetch_layout()` mirrors an old project's `fetchLayout()` action, but rewritten Composition-style).

**A Pinia store is only for data genuinely global across pages** (menu/categories/contacts in `stores/layout.ts`, cart count in `stores/shop.ts`, later: logged-in user) — data that many unrelated components need without a prop chain, and that shouldn't be re-fetched every time a component mounts. **Data scoped to a single page is not a store, even if several sibling components need it** — fetch it once in the page's `views/` component (an `onMounted` + `ref`s there is orchestration, not the "UI logic" the views-stay-pure-assemblers rule is about — see [[project-shop-backend]] for the concrete Home-page correction) and pass it down to each section via props. Shared TS interfaces for these display shapes go in a domain-named file under `types/` (e.g. `types/shop.ts` — not `types/home.ts`, since `ProductSummary`/`AuthorSummary`/`BlogPostSummary` aren't Home-specific and other pages listing products/authors/posts will want the same shapes) — not re-declared per component, but also not smuggled into a store just to have somewhere to export them from. Example: `views/Pages/Home.vue` fetches `GET /api/home` once and passes `bestsellers`/`best_author`/`best_rated`/`blog` (typed via `types/shop.ts`) down to `BestsellersSection`/`BestAuthorSection`/`BestRatedSection`/`BlogSection` as props — those 4 sections stay presentational (no store, no fetch of their own). `bestsellers`/`best_rated` are kept as separate top-level refs in `Home.vue` (not one combined `home_data` blob) so that a future websocket handler patching a single product's live price/stock (planned, not built yet) can find and mutate that product by `slug` in place, in whichever array(s) contain it, without restructuring.

**Checkout wizard (`views/Pages/Cart/CartPage.vue`)** — 3-step flow (Contact → Delivery → Payment) via `CheckoutStep.vue` wrapper (`step_number`, `active`, `done`, `#default`/`#summary` slots, emits `edit`).
- `useCheckoutForm()` holds the three step data refs, persisted to `localStorage`.
- Each step component takes `initial_data` prop, emits `change` (live draft, drives the sidebar `#summary` slot) and `complete` (fires only on explicit "Continue" click, advances `current_step` in `CartPage.vue`).
- `ContactStep.vue` uses vee-validate (`useForm`/`useField`, matches `ContactForm.vue` pattern) — **not** part of `useWizardStep`.
- `DeliveryStep.vue`/`PaymentStep.vue` use the `useWizardStep<T>(initial_data, emit, validator?)` composable instead (plain `reactive` draft + `is_valid` + `on_continue`) — do not hand-roll this pattern again, only these two non-vee-validate steps need it.
- Radio-style method pickers (delivery method, payment method) → `ui/base/BaseRadioGroup.vue` (generic `<script setup generic="T extends string">` component) — never re-duplicate the label/input markup or `__method`/`__method--active` styles per step.
- `BaseButton` has a third `variant="text"` (no background/border, small font, color-only hover) for inline actions like "Edit"/"Edit Items" — use it instead of ad-hoc `<button>` + custom SCSS.

## Environment

Root `.env` controls Docker (ports, MySQL credentials). Backend has its own `backend/.env` for Laravel (DB_HOST=`db`, REDIS_HOST=`redis`).

## Real-time

Redis pub/sub connects backend to websocket server. PHP publishes to Redis → `websocket/server.js` subscribes and broadcasts to connected clients via ws.

## Caching

`CACHE_STORE=redis` (`predis` client) is already configured — `app/Services/CacheService.php` holds TTL constants (`TTL_LAYOUT`, `TTL_HOME`) and tag constants (`TAG_LAYOUT`, `TAG_HOME`), one per cached "page bundle" endpoint, plus a `flushOnXWrite()` static method per write source. Read-path services wrap their aggregate query in `Cache::tags([...])->remember($key, $ttl, fn () => [...])` — see `LayoutService::getLayout()` / `HomeService::getHome()`.

**Cache invalidation goes on the model's `booted()` hook** (`static::saved()` / `static::deleted()` calling `CacheService::flushOnXWrite()`), not a manual call from a controller/service — admin writes go through Filament's own generated CRUD with no custom service layer in front of it, so a model-level hook is the one place guaranteed to fire regardless of what triggered the write (Filament, Tinker, a future API endpoint). See `backend/docs/database.md` § Caching for exactly which models flush which tag.

**Never cache a raw `Collection` or Eloquent model — always `->toArray()` before returning from the cached closure.** This project's `config/cache.php` has Laravel 13's `'serializable_classes' => false` default (a real security control against object-injection via a compromised cache backend) — Redis's `unserialize()` under that setting silently returns `__PHP_Incomplete_Class` for **any** object read back from cache, so a cached `Collection` looks fine on the first request (computed fresh, never round-tripped through serialization) and then silently breaks on every request after that. Fix by not serializing objects into the cache in the first place — don't "fix" this by loosening `serializable_classes`, that setting should stay as-is.

**Redis is already wired up — reach for it proactively on new endpoints, don't wait to be told.** Any new read endpoint that returns general, non-personalized data (aggregates, reference/listing data, anything shaped like "same response for every visitor") should get the same `CacheService` treatment as `layout`/`home` as part of building it, not as a follow-up once someone notices it's missing. Endpoints that are inherently per-user or highly parameterized (cart, orders, filtered/paginated search with many query combinations) are the exception — caching those needs a deliberate key strategy, not the same blanket `remember()` call, so it's fine to skip caching there and just say so rather than force it.

## Code Style Rules — ALWAYS follow these

**NO alignment spaces.** Single space before `=`, `=>`, `:` — never pad to align columns. This applies to code only (PHP/TS/Vue) — in Markdown docs, alignment/padding for readability (e.g. lining up `|` columns in a table) is fine, since it isn't code.

```php
// WRONG
$output   = trim(...);
'title'   => $this->title,

// RIGHT
$output = trim(...);
'title' => $this->title,
```

```ts
// WRONG
const is_loading   = ref(true)
const page_title   = ref('')

// RIGHT
const is_loading = ref(true)
const page_title = ref('')
```

**NO objects/arrays on one line.** Every property on its own line, always — no exceptions, even for short objects.

```ts
// WRONG
{ name: 'name', model: null, placeholder: 'Name', type: 'text' },
{ key: 'id', text: 'ID' },

// RIGHT
{
    name: 'name',
    model: null,
    placeholder: 'Name',
    type: 'text',
},
{
    key: 'id',
    text: 'ID',
},
...(condition ? [{
    key: 'actions',
    text: '',
}] : []),
```

**snake_case** for all variables, object keys, props, interface fields. camelCase/PascalCase only for functions, components, file names.

**English only** — all code comments, docblocks, and inline notes must be in English. This also applies to every documentation file in the repo (README, `docs/*.md`, CLAUDE.md itself) — nothing checked into the repo should be in a non-English language, regardless of what language is used to address Claude in conversation.

**No explanatory/rationale comments in code, even for non-obvious decisions.** Context about *why* something was built a certain way belongs in `backend/docs/database.md` / `CLAUDE.md`, not in a `//` line next to the code — an inline comment almost always just duplicates what's already documented there. If a decision is worth flagging, say it in the chat reply so it can be routed to docs deliberately, not left as a stray comment.

## Validation — ALWAYS use vee-validate + yup

All form validation in the frontend must use **vee-validate** with **yup** schemas. Never write manual validation logic (custom `computed` flags like `field !== ''`, manual error refs, etc.).

Pattern (matches `ContactForm.vue`):
- `useForm({ validationSchema: object({...}) })` at the component or page level
- Field components use `useField(() => props.name)` internally (like `BaseInput.vue`)
- Schema built with `yup`: use `.min(1, 'message')` for required text fields — **never** `string().required()` alone, because yup v1 passes `''` (empty string) through `required()`. Only `min(1)` reliably catches empty string. For email: `.min(1, '...').email('...')`. For numbers/phones: `.min(N, '...')`.
- Submit fires only when schema is valid (`handleSubmit` from `useForm`)
- Validation messages in English — matches every other UI string: all frontend-facing text (templates, labels, placeholders, validation/error messages) must be in English, regardless of what language the request that produced it was written in

## Notifications — ALWAYS use vue-toastification for API request feedback

Every API call's error feedback goes through `vue-toastification`, never a `console.error`/`alert()`/inline-only error state.

- Plugin registered once in `main.ts` (`app.use(Toast, { position: POSITION.TOP_RIGHT, timeout: 4000 })`), CSS imported there too (`vue-toastification/dist/index.css`)
- **Errors are handled globally, once** — `plugins/axios.ts`'s response interceptor calls `useToast().error(...)` on every failed request (extracts the first message from `{errors: "..."}` or `{errors: {field: [...]}}`, falls back to a generic message for non-JSON/network failures) — don't add a second error toast in the component that made the call, the interceptor already covers it
- **Success messages are per-component** — call `useToast().success('...')` explicitly after an action that should confirm success (e.g. `NewsletterForm.vue` after a successful subscribe). Not every successful request needs one (e.g. the `layout` fetch on app load shouldn't toast) — only ones the user directly triggered and would expect confirmation for
- `useToast()` works outside component setup too (e.g. inside `axios.ts`, not just `<script setup>`) — it's backed by a global event bus, not Vue's `inject()`
