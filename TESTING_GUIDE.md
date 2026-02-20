# Testing the Role-Based System

## Current Behavior

When you're already logged in and click "Login" or "Register" from the Welcome page, you get redirected to your dashboard. This is correct Laravel behavior - authenticated users can't access guest-only pages.

## How to Test Registration

### Option 1: Logout First
1. If logged in, logout from your current session
2. Go back to the Welcome page
3. Click "Start Selling" or "Start Shopping"
4. Complete registration
5. You'll be redirected to the appropriate dashboard

### Option 2: Use Incognito/Private Window
1. Open an incognito/private browser window
2. Visit your site
3. Click "Start Selling" or "Start Shopping"
4. Complete registration

### Option 3: Clear Session
Run this command to clear all sessions:
```bash
php artisan session:flush
```

## Test Accounts

Login with these to test different dashboards:

- **admin@itinda.com** / password → Admin Dashboard
- **owner@itinda.com** / password → Vendor Dashboard (with Financial Reports)
- **staff@itinda.com** / password → Vendor Dashboard (no Financial Reports)
- **customer@itinda.com** / password → Customer Dashboard

## Expected Flow

1. **Not logged in** → Click Login/Register → See auth pages
2. **Already logged in** → Click Login/Register → Redirect to your role's dashboard
3. **After login** → Automatically redirect to role-specific dashboard:
   - Admin → `/admin/dashboard`
   - Vendor → `/vendor/dashboard`
   - Customer → `/customer/dashboard`

## Logout

To logout, you need to add a logout button to your dashboards or visit `/logout` with a POST request.
