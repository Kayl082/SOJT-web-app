# Role-Based System Setup

## What Was Created

### Database Structure
- **Shops table**: Stores vendor shop information
- **Users table**: Added `role` and `shop_id` columns
  - Roles: `admin`, `vendor_owner`, `vendor_staff`, `customer`

### Models
- `Shop` model with relationships
- `User` model updated with role helper methods

### Middleware
- `RedirectBasedOnRole`: Auto-redirects users to their role-specific dashboard
- `CheckRole`: Protects routes based on user roles

### Routes (Organized by Role)
- `routes/admin.php` - Admin routes
- `routes/vendor.php` - Vendor routes (owner & staff)
- `routes/customer.php` - Customer routes

### Views (Blade Templates)
- Admin: dashboard, vendors, customers
- Vendor: dashboard, products, reports (sales & financial)
- Customer: dashboard, search, orders

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Test Users
```bash
php artisan db:seed
```

### 3. Test Login Credentials
- **Admin**: admin@itinda.com / password
- **Vendor Owner**: owner@itinda.com / password
- **Vendor Staff**: staff@itinda.com / password
- **Customer**: customer@itinda.com / password

## How It Works

1. User logs in
2. Middleware checks their role
3. Redirects to appropriate dashboard:
   - Admin → `/admin/dashboard`
   - Vendor → `/vendor/dashboard`
   - Customer → `/customer/dashboard`

## Role Permissions

### Admin
- Full platform access
- Manage vendors and customers

### Vendor Owner
- Full shop management
- View financial reports
- Manage products and staff

### Vendor Staff
- Manage products
- View sales reports
- NO access to financial reports

### Customer
- Search shops
- Compare prices
- Place orders

## Next Steps

1. Customize the blade templates with your design
2. Add controllers for CRUD operations
3. Implement product management
4. Add order functionality
