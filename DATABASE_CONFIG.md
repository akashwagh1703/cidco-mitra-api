# PostgreSQL Database Configuration

## Database Details

```
Host: 115.124.98.61
Port: 5432
Database: cidco-mitra
Username: postgres
Password: Supp0rt@123
```

## Setup Instructions

### 1. Copy Environment File

```bash
copy .env.example .env
```

### 2. Update .env File

The `.env.example` is already configured with PostgreSQL settings:

```env
DB_CONNECTION=pgsql
DB_HOST=115.124.98.61
DB_PORT=5432
DB_DATABASE=cidco-mitra
DB_USERNAME=postgres
DB_PASSWORD=Supp0rt@123
```

### 3. Install PHP PostgreSQL Extension

Ensure you have the PostgreSQL extension enabled in PHP:

**Windows:**
- Open `php.ini`
- Uncomment: `extension=pdo_pgsql`
- Uncomment: `extension=pgsql`
- Restart web server

**Linux:**
```bash
sudo apt-get install php-pgsql
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Seed Database

```bash
php artisan db:seed
```

## Verification

Test the connection:

```bash
php artisan tinker
DB::connection()->getPdo();
```

If successful, you'll see the PDO connection object.

## Default Admin User

After seeding:
- Email: admin@cidcomitra.gov.in
- Password: admin123

## Notes

- PostgreSQL is configured as the default database driver
- SSL mode is set to 'prefer'
- Schema is set to 'public'
- The database is hosted remotely at 115.124.98.61
