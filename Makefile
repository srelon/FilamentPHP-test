up:
	docker compose up -d
	@echo ""
	@echo "  API + Admin:  http://127.0.0.1:8000"
	@echo "  Admin panel:  http://127.0.0.1:8000/admin"
	@echo "  Site:         http://127.0.0.1:8880"
	@echo ""

down:
	docker compose --profile site down

scheduler-logs:
	docker logs -f filament_scheduler

scheduler-restart:
	docker compose restart scheduler

site:
	docker compose --profile site up -d
	@echo ""
	@echo "  Site dev:  http://127.0.0.1:5173"
	@echo ""
