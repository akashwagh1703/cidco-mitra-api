# CIDCO Mitra API - Installation Guide

## ✅ Complete Installation Steps

### Step 1: Install Composer Dependencies

```bash
cd cidco-mitra-api
composer install
```

This will install:
- Laravel 11
- Laravel Sanctum
- Spatie Laravel Permission
- All other dependencies

### Step 2: Environment Configuration

```bash
# Copy environment file
copy .env.example .env
```

Edit `.env` file and configure:

```env
APP_NAME="CIDCO Mitra API"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cidco_mitra
DB_USERNAME=root
DB_PASSWORD=your_password

SANCTUM_STATEFUL_DOMAINS=localhost:3001,127.0.0.1:3001
```

### Step 3: Generate Application Key

```bash
php artisan key:generate
```

### Step 4: Database Connection

The database is already configured to connect to PostgreSQL at:
- Host: 115.124.98.61
- Port: 5432
- Database: cidco-mitra

No need to create a database. Just ensure PHP PostgreSQL extension is enabled.

### Step 5: Run Migrations

```bash
php artisan migrate
```

This creates all necessary tables:
- users
- roles
- permissions
- leads
- lead_notes
- lead_timelines
- notifications
- settings

### Step 6: Seed Database

```bash
php artisan db:seed
```

This creates:
- 9 permissions
- 4 roles (Super Admin, Admin, Manager, Agent)
- 1 admin user (admin@cidcomitra.gov.in / admin123)

### Step 7: Start Development Server

```bash
php artisan serve
```

API will be available at: **http://localhost:8000**

### Step 8: Test API

Test the login endpoint:

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@cidcomitra.gov.in","password":"admin123"}'
```

You should receive a token in the response.

## 🔧 Troubleshooting

### Issue: Composer install fails

**Solution:**
```bash
composer update
composer install --no-scripts
php artisan key:generate
composer install
```

### Issue: Migration fails

**Solution:**
- Check database credentials in `.env`
- Ensure MySQL is running
- Verify database exists
- Check user permissions

### Issue: Permission denied errors

**Solution:**
```bash
# Windows
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T

# Linux/Mac
chmod -R 775 storage bootstrap/cache
```

### Issue: CORS errors from frontend

**Solution:**
- Update `config/cors.php` with your frontend URL
- Clear config cache: `php artisan config:clear`

## ✅ Verification Checklist

- [ ] Composer dependencies installed
- [ ] .env file configured
- [ ] Application key generated
- [ ] Database created
- [ ] Migrations run successfully
- [ ] Database seeded
- [ ] Server starts without errors
- [ ] Login API works
- [ ] Can access http://localhost:8000

## 🎉 Success!

Your CIDCO Mitra API is now ready to use!

**Default Admin Credentials:**
- Email: admin@cidcomitra.gov.in
- Password: admin123

**Next Steps:**
1. Update admin panel `.env` to point to this API
2. Test all endpoints
3. Configure email settings
4. Deploy to production

## 📞 Need Help?

Check the main README.md for API documentation and usage examples.
