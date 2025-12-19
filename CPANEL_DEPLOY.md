# cPanel Laravel API Deployment

## 1. File Structure
```
public_html/
├── api/              # Laravel files here
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   ├── vendor/
│   └── .env
└── index.php         # Point to api/public/index.php
```

## 2. Upload Files
- Upload all Laravel files to `public_html/api/`
- Move `public` folder contents to `public_html/`

## 3. Update .env
```env
APP_URL=https://yourdomain.com
DB_HOST=localhost
DB_DATABASE=your_cpanel_db_name
DB_USERNAME=your_cpanel_db_user
DB_PASSWORD=your_cpanel_db_password
```

## 4. Update index.php
```php
<?php
require __DIR__.'/api/public/index.php';
```

## 5. Run via cPanel Terminal
```bash
cd public_html/api
composer install --no-dev
php artisan key:generate
php artisan migrate --force
php artisan config:cache
```