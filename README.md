# CIDCO Mitra API

Laravel 11 REST API for CIDCO Mitra Admin Panel

## 🚀 Features

- ✅ Laravel 11 (Latest)
- ✅ Laravel Sanctum Authentication
- ✅ Role & Permission Management (Spatie)
- ✅ Lead Management System
- ✅ Notification System
- ✅ Settings Management
- ✅ RESTful API Design
- ✅ CORS Configured
- ✅ Database Migrations & Seeders

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or PostgreSQL
- Laravel 11

## 🔧 Installation

### 1. Install Dependencies

```bash
composer install
```

### 2. Environment Setup

```bash
copy .env.example .env
```

Edit `.env` and configure:
- Database credentials
- APP_URL
- SANCTUM_STATEFUL_DOMAINS

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Migrations

```bash
php artisan migrate
```

### 5. Seed Database

```bash
php artisan db:seed
```

This creates:
- Default permissions
- Default roles (Super Admin, Admin, Manager, Agent)
- Admin user (admin@cidcomitra.gov.in / admin123)

### 6. Start Development Server

```bash
php artisan serve
```

API will be available at: `http://localhost:8000`

## 🔐 Default Credentials

```
Email: admin@cidcomitra.gov.in
Password: admin123
```

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api/v1
```

### Authentication

All admin endpoints require Bearer token authentication.

#### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "admin@cidcomitra.gov.in",
  "password": "admin123"
}
```

Response:
```json
{
  "success": true,
  "token": "1|xxxxx",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@cidcomitra.gov.in",
    "role": "Super Admin",
    "permissions": [...]
  }
}
```

#### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

### Public Endpoints

#### Submit Lead (Public)
```http
POST /api/v1/leads
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "9876543210",
  "message": "Interested in services",
  "source": "website"
}
```

### Admin Endpoints (Protected)

#### Dashboard
```http
GET /api/v1/admin/dashboard
Authorization: Bearer {token}
```

#### Leads Management
```http
GET /api/v1/admin/leads?status=new&search=john&page=1
GET /api/v1/admin/leads/{id}
PUT /api/v1/admin/leads/{id}
PATCH /api/v1/admin/leads/{id}/status
POST /api/v1/admin/leads/{id}/notes
GET /api/v1/admin/leads/{id}/timeline
DELETE /api/v1/admin/leads/{id}
```

#### Users Management
```http
GET /api/v1/admin/users
POST /api/v1/admin/users
PUT /api/v1/admin/users/{id}
PATCH /api/v1/admin/users/{id}/status
PATCH /api/v1/admin/users/{id}/role
DELETE /api/v1/admin/users/{id}
```

#### Roles & Permissions
```http
GET /api/v1/admin/roles
POST /api/v1/admin/roles
PUT /api/v1/admin/roles/{id}
DELETE /api/v1/admin/roles/{id}
GET /api/v1/admin/permissions
```

#### Notifications
```http
GET /api/v1/admin/notifications
PATCH /api/v1/admin/notifications/read
PATCH /api/v1/admin/notifications/{id}/read
DELETE /api/v1/admin/notifications/{id}
```

#### Settings
```http
GET /api/v1/admin/settings
PUT /api/v1/admin/settings/general
PUT /api/v1/admin/settings/branding
PUT /api/v1/admin/settings/homepage
PUT /api/v1/admin/settings/seo
```

## 🔒 Permissions

- `view_dashboard` - View dashboard
- `manage_leads` - Full lead management
- `update_lead_status` - Update lead status only
- `view_notifications` - View notifications
- `manage_website_settings` - Manage website settings
- `manage_email_settings` - Manage email settings
- `manage_users` - User management
- `manage_roles` - Role management
- `access_reports` - Access reports

## 👥 Default Roles

### Super Admin
- All permissions

### Admin
- view_dashboard
- manage_leads
- update_lead_status
- view_notifications
- manage_website_settings
- manage_email_settings
- manage_users

### Manager
- view_dashboard
- manage_leads
- update_lead_status
- view_notifications
- access_reports

### Agent
- view_dashboard
- update_lead_status
- view_notifications

## 📊 Database Schema

### Users
- id, name, email, password, status, timestamps

### Leads
- id, name, email, phone, message, status, source, timestamps, soft_deletes

### Lead Notes
- id, lead_id, note, created_by, timestamps

### Lead Timeline
- id, lead_id, event_type, event_data, created_by, timestamps

### Notifications
- id, user_id, type, title, message, data, read_at, timestamps

### Settings
- id, key, value (JSON), timestamps

### Roles & Permissions
- Managed by Spatie Laravel Permission package

## 🧪 Testing

```bash
php artisan test
```

## 📝 Additional Commands

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Create new migration
php artisan make:migration create_table_name

# Create new controller
php artisan make:controller ControllerName

# Create new model
php artisan make:model ModelName
```

## 🔄 CORS Configuration

CORS is configured in `config/cors.php` to allow requests from:
- http://localhost:3001 (Admin Panel)
- http://127.0.0.1:3001

Update this for production domains.

## 🚀 Production Deployment

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure production database
4. Run migrations: `php artisan migrate --force`
5. Seed database: `php artisan db:seed --force`
6. Optimize: `php artisan optimize`
7. Configure web server (Apache/Nginx)

## 📞 Support

For issues or questions, contact the development team.

## 📄 License

Proprietary - CIDCO Mitra
