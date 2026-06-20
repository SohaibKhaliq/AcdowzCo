# Support

## Documentation
- 📖 [README.md](README.md) — setup, configuration, deployment
- 🔍 [Existing Issues](https://github.com/SohaibKhaliq/AcdowzCo/issues)

## How to Get Help

| Channel | Best For |
|---------|----------|
| [🐛 Bug Report](https://github.com/SohaibKhaliq/AcdowzCo/issues/new) | Confirmed bugs |
| [💬 Discussions](https://github.com/SohaibKhaliq/AcdowzCo/discussions) | Questions & ideas |

## Quick Troubleshooting

```bash
# Cache not updating
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Admin panel inaccessible
# Check ADMIN_DIR in .env — try default "admin"

# Database connection fails
# Verify DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env

# 500 error after deployment
# Set APP_DEBUG=true temporarily to see the error
# Check storage/logs/laravel.log
```
