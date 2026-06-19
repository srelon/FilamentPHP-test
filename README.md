# dashboard.loco

Full-stack news/blog platform with real-time features. Laravel 11 API + Vue 3 admin & site frontends.

## Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 11, PHP 8.2 |
| Database | MySQL 8.0 |
| Cache/Pub-Sub | Redis 7 (Predis) |
| WebSocket | Node.js 20 (ws + iorelis) |
| Admin frontend | Vue 3 + TypeScript, Vite, Pinia, Tailwind CSS, VeeValidate |
| Site frontend | Vue 3 + TypeScript, Vite, Pinia, Bootstrap 4.6 |
| Infrastructure | Docker Compose, Nginx, PHP-FPM |

## Requirements

- Docker + Docker Compose
- Make
- WSL2 (Ubuntu)

## Setup

```bash
cp .env.example .env && cp backend/.env.example backend/.env
docker compose up -d --build
make up
docker exec -w /var/www/backend dashboard_app php artisan db:seed
```

## Commands

```bash
make up      # Start core services
make down    # Stop everything
make admin   # Start admin frontend dev server → http://127.0.0.1:5200
make site    # Start site frontend dev server → http://127.0.0.1:5173
```

## Key URLs

| Service | URL |
|---------|-----|
| Site | http://127.0.0.1:8880 |
| Admin panel | http://127.0.0.1:8881 |
| API | http://127.0.0.1:8000 |
| phpMyAdmin | http://127.0.0.1:8080 |
| WebSocket | ws://127.0.0.1:6001 |

**phpMyAdmin credentials:** `root` / `root`

## Real-time Architecture

PHP (Predis) publishes → Redis subscribes → Node.js broadcasts → Browser

**Broadcast Channels:**
- `tags.updated` - All connected clients
- `article.{id}` - Article viewers
- `notification.{user_id}` - Specific authenticated user
