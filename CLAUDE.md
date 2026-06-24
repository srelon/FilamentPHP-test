# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Architecture

Monorepo with three independent services:

- `backend/` — Laravel 13 + FilamentPHP (API + Admin panel at `/admin`)
- `frontend/site/` — Vue 3 + TypeScript + Vite (public site)
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

**Ports:** API + Filament Admin `8000`, public site `8880`, Vue dev `5173`, phpMyAdmin `8080`, WebSocket `6001`, MySQL `8101`

## Running commands in the backend container

```bash
docker exec -it filament_app bash

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

## Frontend (Vue 3 — `frontend/site/`)

### Structure

```
src/
  assets/scss/        ← global styles only
    _variables.scss   ← design tokens ($color-primary, $color-accent, $color-dark, etc.)
    _reset.scss       ← Google Fonts import, base reset
    _helpers.scss     ← .container, .section, .section__title, .dots
    main.scss         ← @import (NOT @use — @use scopes variables per file)
  components/
    layout/           ← AppHeader, AppFooter, Layout
    ui/base/          ← BaseButton, BaseInput, BaseTabs, BaseSlider
    ui/shop/          ← all page-section components (PageHero, ProductCard, etc.)
  views/Pages/        ← Home.vue (assembles components only, no UI logic)
  stores/shop.ts      ← Pinia: cart_count
  routes/router.ts    ← Vue Router history mode
public/images/        ← static images served at /images/*.png
```

### SCSS rules

- Vite `additionalData` auto-injects `@use "@/assets/scss/variables" as *` into every component
- **Never redeclare `$color-*` variables inside component `<style>`** — they come from global injection
- `main.scss` uses `@import` (not `@use`) so that `_reset.scss` and `_helpers.scss` can access variables
- All component styles: `<style lang="scss" scoped>` with BEM naming

### Component rules

- All reusable components in `src/components/ui/` — never tie components to a specific page
- `ui/base/` — generic (BaseButton, BaseInput, BaseTabs, BaseSlider)
- `ui/shop/` — shop-specific but still reusable (ProductCard, ProductSlider, PageHero, etc.)
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
- **HomeHero** — `hero__top` (grid 1fr 1fr: H1 left, desc right) + `hero__bottom` (grid 2fr 1fr 1fr, min-height 500px); image cards with gradient overlay + newsletter block in mid column
- **AppHeader** — book SVG logo + "BookStore" text; Categories mega-menu is a separate element LEFT of Home nav link, controlled by `cats_open` ref + mouseenter/mouseleave on wrapper
- **CategoryStrip** — CSS carousel (transform translateX), shows 8 items, auto-advances every 10s; reset without jump uses double `requestAnimationFrame` to skip transition for one frame
- **ProductCard** — extracted reusable component; hover slides action icons in from right (`translateX(60px) → 0`); `aspect-ratio: 2/3` on figure
- **BestAuthorSection** — award badges are 72px circles with `border: 2px solid $color-primary` and text inside — NO SVG icons
- **BestsellersSection** — title and description configurable via props with defaults

**SVG fill in scoped styles** — CSS `fill` on `<svg>` does not reliably cascade to `<path>` in Vue scoped CSS. Always target child elements directly:
```scss
&__icon {
    path, circle { fill: $color-primary; }
}
```

**Card overlays** — always use `position: absolute; inset: 0` on the overlay, never `position: relative; height: 100%` — the latter collapses when the parent gets its height from flex.

## Environment

Root `.env` controls Docker (ports, MySQL credentials). Backend has its own `backend/.env` for Laravel (DB_HOST=`db`, REDIS_HOST=`redis`).

## Real-time

Redis pub/sub connects backend to websocket server. PHP publishes to Redis → `websocket/server.js` subscribes and broadcasts to connected clients via ws.

## Code Style Rules — ALWAYS follow these

**NO alignment spaces.** Single space before `=`, `=>`, `:` — never pad to align columns.

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

**English only** — all code comments, docblocks, and inline notes must be in English.
