# Role-Based Routing System Summary

## Overview
A complete role-based authentication and routing system using Laravel, Inertia.js, and Vue 3.

---

## Database Structure

### Users Table
- `role` column: enum('admin', 'vendor_owner', 'vendor_staff', 'customer')
- `shop_id` column: foreign key to shops table (for vendors)

### Shops Table
- Stores vendor shop information
- Links to users via `shop_id`

**Migration Files:**
- `database/migrations/2026_02_18_080813_create_shops_table.php`
- `database/migrations/2026_02_18_080641_add_role_to_users_table.php`

---

## Models

### User Model (`app/Models/User.php`)
Helper methods:
- `isAdmin()` - Check if user is admin
- `isVendorOwner()` - Check if user is vendor owner
- `isVendorStaff()` - Check if user is vendor staff
- `isCustomer()` - Check if user is customer
- `shop()` - Relationship to Shop model

### Shop Model (`app/Models/Shop.php`)
Relationships:
- `users()` - All users in the shop
- `owner()` - Shop owner only
- `staff()` - Shop staff only

---

## Middleware

### 1. RedirectBasedOnRole (`app/Http/Middleware/RedirectBasedOnRole.php`)
**Purpose:** Automatically redirects users to their role-specific dashboard

**How it works:**
- Intercepts requests to `/dashboard`
- Checks user's role
- Redirects to appropriate dashboard:
  - Admin → `/admin/dashboard`
  - Vendor (owner/staff) → `/vendor/dashboard`
  - Customer → `/customer/dashboard`

**Registered in:** `bootstrap/app.php` (web middleware group)

### 2. CheckRole (`app/Http/Middleware/CheckRole.php`)
**Purpose:** Protects routes by checking if user has required role(s)

**Usage:**
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin only routes
});

Route::middleware(['auth', 'role:vendor_owner,vendor_staff'])->group(function () {
    // Vendor routes (both owner and staff)
});
```

**Registered in:** `bootstrap/app.php` (as alias 'role')

---

## Routes Structure

### Admin Routes (`routes/admin.php`)
```php
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', ...)->name('dashboard');
        Route::get('/vendors', ...)->name('vendors.index');
        Route::get('/customers', ...)->name('customers.index');
    });
```

### Vendor Routes (`routes/vendor.php`)
```php
Route::middleware(['auth', 'verified', 'role:vendor_owner,vendor_staff'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        Route::get('/dashboard', ...)->name('dashboard');
        Route::get('/products', ...)->name('products.index');
        Route::get('/reports/sales', ...)->name('reports.sales');
        
        // Owner-only route
        Route::middleware('role:vendor_owner')->group(function () {
            Route::get('/reports/financial', ...)->name('reports.financial');
        });
    });
```

### Customer Routes (`routes/customer.php`)
```php
Route::middleware(['auth', 'verified', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', ...)->name('dashboard');
        Route::get('/search', ...)->name('search');
        Route::get('/orders', ...)->name('orders');
    });
```

**All included in:** `routes/web.php`

---

## Registration Flow

### 1. Register Page (`resources/js/pages/auth/Register.vue`)
- Shows role selection modal (Customer or Vendor)
- User selects role before filling form
- Role is submitted with registration data

### 2. CreateNewUser Action (`app/Actions/Fortify/CreateNewUser.php`)
- Validates role field (must be 'customer' or 'vendor')
- Converts 'vendor' to 'vendor_owner' in database
- Creates user with selected role

### 3. Auto-Redirect After Login
- User logs in
- `RedirectBasedOnRole` middleware checks role
- Redirects to appropriate dashboard

---

## Vue Components

### Sidebar Component (`resources/js/components/Sidebar.vue`)
**Features:**
- Collapsible sidebar
- Role-specific branding
- Default Dashboard link
- Settings link
- Logout button

**Usage:**
```vue
<Sidebar role="customer">
    <!-- Optional: Add custom menu items here -->
</Sidebar>
```

### Dashboard Pages
**Structure:**
```
resources/js/pages/
├── admin/
│   ├── Dashboard.vue
│   ├── Vendors.vue
│   └── Customers.vue
├── vendor/
│   ├── Dashboard.vue
│   ├── Products.vue
│   └── reports/
│       ├── Sales.vue
│       └── Financial.vue
└── customer/
    ├── Dashboard.vue
    ├── Search.vue
    └── Orders.vue
```

---

## How It All Works Together

### Registration Flow:
1. User visits Welcome page
2. Clicks "Start Selling" or "Start Shopping"
3. Register.vue shows role selection modal
4. User selects role and completes form
5. CreateNewUser creates user with role
6. User is logged in
7. RedirectBasedOnRole middleware redirects to role dashboard

### Login Flow:
1. User logs in via Login page
2. Fortify authenticates user
3. RedirectBasedOnRole middleware checks role
4. User redirected to appropriate dashboard

### Route Protection:
1. User tries to access a route
2. CheckRole middleware verifies user has required role
3. If authorized → access granted
4. If not authorized → 403 error

---

## Key Files Reference

**Middleware:**
- `app/Http/Middleware/RedirectBasedOnRole.php`
- `app/Http/Middleware/CheckRole.php`
- `bootstrap/app.php` (middleware registration)

**Routes:**
- `routes/admin.php`
- `routes/vendor.php`
- `routes/customer.php`
- `routes/web.php` (includes all role routes)

**Models:**
- `app/Models/User.php`
- `app/Models/Shop.php`

**Actions:**
- `app/Actions/Fortify/CreateNewUser.php`

**Vue Components:**
- `resources/js/components/Sidebar.vue`
- `resources/js/pages/admin/Dashboard.vue`
- `resources/js/pages/vendor/Dashboard.vue`
- `resources/js/pages/customer/Dashboard.vue`

**Migrations:**
- `database/migrations/2026_02_18_080813_create_shops_table.php`
- `database/migrations/2026_02_18_080641_add_role_to_users_table.php`

**Seeders:**
- `database/seeders/RoleSeeder.php`
- `database/seeders/DatabaseSeeder.php`

---

## Test Accounts

```
Admin:
- Email: admin@itinda.com
- Password: password
- Dashboard: /admin/dashboard

Vendor Owner:
- Email: owner@itinda.com
- Password: password
- Dashboard: /vendor/dashboard
- Access: All vendor features including financial reports

Vendor Staff:
- Email: staff@itinda.com
- Password: password
- Dashboard: /vendor/dashboard
- Access: Limited vendor features (no financial reports)

Customer:
- Email: customer@itinda.com
- Password: password
- Dashboard: /customer/dashboard
```

---

## Quick Commands

```bash
# Run migrations
php artisan migrate

# Seed test users
php artisan db:seed

# Clear sessions (for testing)
php artisan session:flush
```
