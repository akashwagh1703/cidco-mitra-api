<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Controllers\Api\V1\PublicController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Api\V1\Admin\NotificationController;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\ServiceController;
use App\Http\Controllers\Api\V1\Admin\SettingController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Api\V1\Admin\AdoController;
use App\Http\Controllers\Api\V1\Admin\ServiceScheduleController;
use Illuminate\Support\Facades\Route;

// Public API Routes
Route::prefix('v1')->group(function () {
    // Public lead submission
    Route::post('/leads', [LeadController::class, 'store'])->middleware('throttle:10,1');
    
    // Public website APIs
    Route::get('/public/settings', [PublicController::class, 'getSettings'])->middleware('throttle:60,1');
    Route::post('/public/contact', [PublicController::class, 'submitLead'])->middleware('throttle:8,1');
    Route::get('/public/stats', [PublicController::class, 'getStats'])->middleware('throttle:30,1');
    Route::get('/public/services', [PublicController::class, 'getServices'])->middleware('throttle:60,1');
    
    // Public appointment booking
    Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('throttle:8,1');
    Route::get('/services/{serviceId}/available-slots', [AppointmentController::class, 'availableSlots'])->middleware('throttle:60,1');

    // Authentication
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

// Protected API Routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Admin routes
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::middleware('permission:view_dashboard')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index']);
            Route::get('/dashboard/advance-lead-pnl', [DashboardController::class, 'advanceLeadPnl']);
            Route::get('/dashboard/insights', [DashboardController::class, 'insights']);
        });

        // Leads Management
        Route::middleware('permission:manage_leads')->group(function () {
            Route::get('/leads', [AdminLeadController::class, 'index']);
            Route::get('/leads/export', [AdminLeadController::class, 'export']);
            Route::post('/leads', [AdminLeadController::class, 'store']);
            Route::get('/leads/{id}', [AdminLeadController::class, 'show']);
            Route::put('/leads/{id}', [AdminLeadController::class, 'update']);
            Route::delete('/leads/{id}', [AdminLeadController::class, 'destroy']);

            // ADO Master
            Route::get('/ados', [AdoController::class, 'index']);
            Route::post('/ados', [AdoController::class, 'store']);
            Route::put('/ados/{id}', [AdoController::class, 'update']);
            Route::delete('/ados/{id}', [AdoController::class, 'destroy']);
        });

        Route::middleware('permission:update_lead_status')->group(function () {
            Route::patch('/leads/{id}/status', [AdminLeadController::class, 'updateStatus']);
            Route::post('/leads/{id}/notes', [AdminLeadController::class, 'addNote']);
            Route::get('/leads/{id}/timeline', [AdminLeadController::class, 'timeline']);
        });

        // Notifications
        Route::middleware('permission:view_notifications')->group(function () {
            Route::get('/notifications', [NotificationController::class, 'index']);
            Route::patch('/notifications/read', [NotificationController::class, 'markAllAsRead']);
            Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
        });

        // Users Management
        Route::middleware('permission:manage_users')->group(function () {
            Route::get('/users', [UserController::class, 'index']);
            Route::post('/users', [UserController::class, 'store']);
            Route::put('/users/{id}', [UserController::class, 'update']);
            Route::patch('/users/{id}/status', [UserController::class, 'updateStatus']);
            Route::patch('/users/{id}/role', [UserController::class, 'updateRole']);
            Route::delete('/users/{id}', [UserController::class, 'destroy']);
        });

        // Roles & Permissions
        Route::middleware('permission:manage_roles')->group(function () {
            Route::get('/roles', [RoleController::class, 'index']);
            Route::post('/roles', [RoleController::class, 'store']);
            Route::put('/roles/{id}', [RoleController::class, 'update']);
            Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
            Route::get('/permissions', [RoleController::class, 'permissions']);
        });

        // Settings
        Route::middleware('permission:manage_website_settings')->group(function () {
            Route::get('/settings', [SettingController::class, 'index']);
            Route::put('/settings/general', [SettingController::class, 'updateGeneral']);
            Route::post('/settings/branding', [SettingController::class, 'updateBranding']);
            Route::put('/settings/homepage', [SettingController::class, 'updateHomepage']);
            Route::post('/settings/seo', [SettingController::class, 'updateSeo']);
            Route::put('/settings/email', [SettingController::class, 'updateEmail']);
        });

        // Services
        Route::middleware('permission:manage_website_settings')->group(function () {
            Route::get('/services', [ServiceController::class, 'index']);
            Route::post('/services', [ServiceController::class, 'store']);
            Route::get('/services/{id}', [ServiceController::class, 'show']);
            Route::put('/services/{id}', [ServiceController::class, 'update']);
            Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
            
            // Service Schedules
            Route::get('/services/{serviceId}/schedules', [ServiceScheduleController::class, 'index']);
            Route::post('/services/{serviceId}/schedules', [ServiceScheduleController::class, 'store']);
            Route::put('/services/{serviceId}/schedules/{id}', [ServiceScheduleController::class, 'update']);
            Route::delete('/services/{serviceId}/schedules/{id}', [ServiceScheduleController::class, 'destroy']);
            Route::get('/services/{serviceId}/available-slots', [ServiceScheduleController::class, 'availableSlots']);
        });
        
        // Appointments
        Route::middleware('permission:manage_leads')->group(function () {
            Route::get('/appointments', [AdminAppointmentController::class, 'index']);
            Route::get('/appointments/stats', [AdminAppointmentController::class, 'stats']);
            Route::get('/appointments/calendar', [AdminAppointmentController::class, 'calendar']);
            Route::get('/appointments/{id}', [AdminAppointmentController::class, 'show']);
            Route::put('/appointments/{id}', [AdminAppointmentController::class, 'update']);
            Route::delete('/appointments/{id}', [AdminAppointmentController::class, 'destroy']);
        });
    });
});
