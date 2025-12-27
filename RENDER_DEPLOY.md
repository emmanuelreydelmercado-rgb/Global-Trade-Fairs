# 🚀 Render Deployment Guide

This Laravel application is configured for easy deployment on Render.

## Quick Deploy

1. **Push to GitHub** (already done ✅)
2. **Create Web Service on Render**
3. **Configure environment variables**
4. **Deploy!**

## Render Configuration

### Build Command
```bash
bash build.sh
```

### Start Command
```bash
bash start.sh
```

### Environment Variables Required

Add these in Render Dashboard → Environment:

```
APP_KEY=base64:HoB8lek7cep6IqD6iYqEq7EV2tE7DUcSsp2UGLBAo2E=
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.onrender.com
LOG_CHANNEL=stderr
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Optional: Database Configuration

If using PostgreSQL on Render, add:

```
DB_CONNECTION=pgsql
DB_HOST=<your-db-host>
DB_PORT=5432
DB_DATABASE=<your-db-name>
DB_USERNAME=<your-db-user>
DB_PASSWORD=<your-db-password>
```

### Optional: Redis Configuration (If Available)

```
REDIS_HOST=<your-redis-host>
REDIS_PASSWORD=<your-redis-password>
REDIS_PORT=6379
CACHE_DRIVER=redis
SESSION_DRIVER=redis
```

## Files Included for Deployment

- ✅ `build.sh` - Build script (installs dependencies, sets up storage)
- ✅ `start.sh` - Start script (runs migrations, starts server)
- ✅ `render.yaml` - Render blueprint configuration
- ✅ `bootstrap/app.php` - Laravel bootstrap (critical for Composer)
- ✅ `public/.htaccess` - Apache rewrite rules

## Deployment Checklist

- [ ] Pushed code to GitHub
- [ ] Created Render Web Service
- [ ] Added APP_KEY environment variable
- [ ] Added APP_URL environment variable
- [ ] Set APP_ENV=production
- [ ] Set APP_DEBUG=false
- [ ] Added database credentials (if using DB)
- [ ] Triggered deploy
- [ ] Verified deployment logs
- [ ] Tested the live URL

## Troubleshooting

### Build Fails
- Check that `bootstrap/app.php` exists in repository
- Verify `composer.json` and `composer.lock` are present
- Check build logs for specific error

### Start Fails
- Verify APP_KEY is set
- Check that PORT environment variable is available
- Review start logs for errors

### 500 Error
- Enable APP_DEBUG=true temporarily to see error
- Check storage permissions
- Verify all environment variables are set

### Database Issues
- Confirm database credentials are correct
- Check database is accessible from Render
- Run migrations manually if needed

## Support

- Render Docs: https://render.com/docs
- Laravel Docs: https://laravel.com/docs

---

**Last Updated:** 2025-12-27
**Laravel Version:** 11.x
**PHP Version:** 8.1+
