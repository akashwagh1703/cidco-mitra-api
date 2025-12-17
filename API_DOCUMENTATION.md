# CIDCO Mitra API - Complete Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication

All admin endpoints require Bearer token in the Authorization header:
```
Authorization: Bearer {your-token-here}
```

---

## 🔐 Authentication Endpoints

### 1. Login
**POST** `/auth/login`

**Request:**
```json
{
  "email": "admin@cidcomitra.gov.in",
  "password": "admin123"
}
```

**Response:**
```json
{
  "success": true,
  "token": "1|xxxxx",
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@cidcomitra.gov.in",
    "role": "Super Admin",
    "permissions": ["view_dashboard", "manage_leads", ...]
  }
}
```

### 2. Logout
**POST** `/auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### 3. Get Current User
**GET** `/auth/me`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@cidcomitra.gov.in",
    "role": "Super Admin",
    "permissions": [...]
  }
}
```

---

## 📊 Dashboard Endpoint

### Get Dashboard Data
**GET** `/admin/dashboard`

**Permission:** `view_dashboard`

**Response:**
```json
{
  "success": true,
  "data": {
    "total_leads": 248,
    "today_leads": 15,
    "leads_by_status": {
      "new": 35,
      "contacted": 50,
      "follow_up": 30,
      "converted": 20,
      "not_interested": 10
    },
    "leads_last_7_days": [
      {"date": "2024-01-15", "count": 20},
      {"date": "2024-01-16", "count": 18}
    ]
  }
}
```

---

## 📝 Lead Management (Public)

### Submit Lead (Public - No Auth Required)
**POST** `/leads`

**Request:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "9876543210",
  "message": "Interested in your services",
  "source": "website"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Lead submitted successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "9876543210",
    "status": "new",
    "created_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

---

## 📋 Lead Management (Admin)

### 1. Get All Leads
**GET** `/admin/leads`

**Permission:** `manage_leads`

**Query Parameters:**
- `status` - Filter by status (new, contacted, follow_up, converted, not_interested)
- `source` - Filter by source
- `search` - Search by name, email, or phone
- `date_from` - Filter from date (YYYY-MM-DD)
- `date_to` - Filter to date (YYYY-MM-DD)
- `page` - Page number for pagination

**Example:**
```
GET /admin/leads?status=new&search=john&page=1
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "phone": "9876543210",
        "message": "Interested in services",
        "status": "new",
        "source": "website",
        "created_at": "2024-01-15T10:30:00.000000Z"
      }
    ],
    "total": 100,
    "per_page": 10
  }
}
```

### 2. Get Lead Details
**GET** `/admin/leads/{id}`

**Permission:** `manage_leads`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "9876543210",
    "message": "Interested in services",
    "status": "new",
    "source": "website",
    "notes": [...],
    "timeline": [...],
    "created_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### 3. Update Lead
**PUT** `/admin/leads/{id}`

**Permission:** `manage_leads`

**Request:**
```json
{
  "name": "John Doe Updated",
  "email": "john.new@example.com",
  "phone": "9876543211",
  "message": "Updated message"
}
```

### 4. Update Lead Status
**PATCH** `/admin/leads/{id}/status`

**Permission:** `update_lead_status`

**Request:**
```json
{
  "status": "contacted"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Lead status updated successfully",
  "data": {
    "id": 1,
    "status": "contacted"
  }
}
```

### 5. Add Note to Lead
**POST** `/admin/leads/{id}/notes`

**Permission:** `update_lead_status`

**Request:**
```json
{
  "note": "Called customer, will follow up tomorrow"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Note added successfully",
  "data": {
    "id": 1,
    "note": "Called customer, will follow up tomorrow",
    "created_by": 1,
    "creator": {
      "name": "Admin User"
    },
    "created_at": "2024-01-15T11:00:00.000000Z"
  }
}
```

### 6. Get Lead Timeline
**GET** `/admin/leads/{id}/timeline`

**Permission:** `update_lead_status`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "event_type": "status_change",
      "event_data": {
        "old_status": "new",
        "new_status": "contacted"
      },
      "creator": {
        "name": "Admin User"
      },
      "created_at": "2024-01-15T11:00:00.000000Z"
    }
  ]
}
```

### 7. Delete Lead
**DELETE** `/admin/leads/{id}`

**Permission:** `manage_leads`

**Response:**
```json
{
  "success": true,
  "message": "Lead deleted successfully"
}
```

---

## 👥 User Management

### 1. Get All Users
**GET** `/admin/users`

**Permission:** `manage_users`

### 2. Create User
**POST** `/admin/users`

**Permission:** `manage_users`

**Request:**
```json
{
  "name": "New User",
  "email": "user@example.com",
  "password": "password123",
  "role": "Manager",
  "status": true
}
```

### 3. Update User
**PUT** `/admin/users/{id}`

**Permission:** `manage_users`

### 4. Update User Status
**PATCH** `/admin/users/{id}/status`

**Permission:** `manage_users`

**Request:**
```json
{
  "status": false
}
```

### 5. Update User Role
**PATCH** `/admin/users/{id}/role`

**Permission:** `manage_users`

**Request:**
```json
{
  "role": "Admin"
}
```

### 6. Delete User
**DELETE** `/admin/users/{id}`

**Permission:** `manage_users`

---

## 🔐 Role & Permission Management

### 1. Get All Roles
**GET** `/admin/roles`

**Permission:** `manage_roles`

### 2. Create Role
**POST** `/admin/roles`

**Permission:** `manage_roles`

**Request:**
```json
{
  "name": "Custom Role",
  "permissions": ["view_dashboard", "manage_leads"]
}
```

### 3. Update Role
**PUT** `/admin/roles/{id}`

**Permission:** `manage_roles`

### 4. Delete Role
**DELETE** `/admin/roles/{id}`

**Permission:** `manage_roles`

### 5. Get All Permissions
**GET** `/admin/permissions`

**Permission:** `manage_roles`

---

## 🔔 Notifications

### 1. Get All Notifications
**GET** `/admin/notifications`

**Permission:** `view_notifications`

### 2. Mark All as Read
**PATCH** `/admin/notifications/read`

**Permission:** `view_notifications`

### 3. Mark One as Read
**PATCH** `/admin/notifications/{id}/read`

**Permission:** `view_notifications`

### 4. Delete Notification
**DELETE** `/admin/notifications/{id}`

**Permission:** `view_notifications`

---

## ⚙️ Settings Management

### 1. Get All Settings
**GET** `/admin/settings`

**Permission:** `manage_website_settings`

### 2. Update General Settings
**PUT** `/admin/settings/general`

**Permission:** `manage_website_settings`

**Request:**
```json
{
  "site_name": "CIDCO Mitra",
  "contact_email": "contact@cidco.com",
  "contact_phone": "9876543210",
  "address": "Navi Mumbai"
}
```

### 3. Update Branding Settings
**PUT** `/admin/settings/branding`

**Permission:** `manage_website_settings`

### 4. Update Homepage Settings
**PUT** `/admin/settings/homepage`

**Permission:** `manage_website_settings`

### 5. Update SEO Settings
**PUT** `/admin/settings/seo`

**Permission:** `manage_website_settings`

---

## ❌ Error Responses

All errors follow this format:

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

**Common HTTP Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## 🔒 Available Permissions

- `view_dashboard`
- `manage_leads`
- `update_lead_status`
- `view_notifications`
- `manage_website_settings`
- `manage_email_settings`
- `manage_users`
- `manage_roles`
- `access_reports`

---

## 📝 Notes

- All timestamps are in UTC
- Pagination default: 10 items per page
- Soft deletes are used for leads
- All JSON responses include `success` boolean field
