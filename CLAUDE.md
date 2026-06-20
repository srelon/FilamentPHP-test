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
