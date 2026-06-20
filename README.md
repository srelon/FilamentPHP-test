# dashboard.loco

Full-stack CRM with real-time features. Laravel + FilamentPHP admin + Vue 3 public site.

## Stack

| Layer          | Technology                                          |
|----------------|-----------------------------------------------------|
| Backend        | Laravel 13, PHP 8.3                                 |
| Admin panel    | FilamentPHP (Livewire + Alpine.js + Tailwind CSS)   |
| Site frontend  | Vue 3 + TypeScript, Vite                            |
| Database       | MySQL 8.0                                           |
| Cache/Pub-Sub  | Redis 7                                             |
| WebSocket      | Node.js 20 (ws + ioredis)                           |
| Infrastructure | Docker Compose, Nginx, PHP-FPM                      |

## Requirements

- Docker + Docker Compose
- Make
- WSL2 (Ubuntu)

## Setup

```bash
cp .env.example .env && cp backend/.env.example backend/.env
docker compose up -d --build
docker exec -it filament_app sh -c 'cd /var/www/backend && php artisan db:seed'
```

## Commands

```bash
make up    # Start core services
make down  # Stop everything
make site  # Start Vue dev server → http://127.0.0.1:5173
```

## Key URLs

| Service         | URL                        |
|-----------------|----------------------------|
| API             | http://127.0.0.1:8000      |
| Filament Admin  | http://127.0.0.1:8000/admin |
| Site            | http://127.0.0.1:8880      |
| phpMyAdmin      | http://127.0.0.1:8080      |
| WebSocket       | ws://127.0.0.1:6001        |

**phpMyAdmin credentials:** `root` / `root`

## Real-time Architecture

PHP publishes → Redis subscribes → Node.js broadcasts → Browser

**Channels:**
- `tags.updated`           — all connected clients
- `article.{id}`           — article viewers
- `notification.{user_id}` — specific authenticated user
