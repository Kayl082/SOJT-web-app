# Navigation Structure Guide

## File Locations

### Navigation Files (Sidebars)
```
resources/views/partials/
├── admin-nav.blade.php      (Admin sidebar - emerald #1B4D3E)
├── vendor-nav.blade.php     (Vendor sidebar - emerald-mid #245c4a)
└── customer-nav.blade.php   (Customer sidebar - emerald #1B4D3E)
```

### Layout Files (Include the nav)
```
resources/views/layouts/
├── admin.blade.php          (Includes admin-nav)
├── vendor.blade.php         (Includes vendor-nav)
└── customer.blade.php       (Includes customer-nav)
```

### Dashboard Pages
```
resources/views/
├── admin/
│   └── dashboard.blade.php
├── vendor/
│   └── dashboard.blade.php
└── customer/
    └── dashboard.blade.php
```

## Current Sidebar Features

Each sidebar has:
- **Collapsible** - Click the gold toggle button to hide/show
- **Empty menu** - Ready for you to add menu items
- **Role-specific colors**:
  - Admin: Dark emerald (#1B4D3E)
  - Vendor: Mid emerald (#245c4a)
  - Customer: Dark emerald (#1B4D3E)
- **Gold accents** (#C5A059) for toggle button and headers

## How to Add Menu Items

Edit the sidebar file and add items in the `<div class="sidebar-menu">` section:

```html
<div class="sidebar-menu">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.vendors.index') }}">Vendors</a>
    <a href="{{ route('admin.customers.index') }}">Customers</a>
</div>
```

## Test Users

Login with these accounts to see each sidebar:

- **admin@itinda.com** → Admin sidebar
- **owner@itinda.com** → Vendor sidebar  
- **staff@itinda.com** → Vendor sidebar
- **customer@itinda.com** → Customer sidebar

All passwords: `password`

## Next Steps

1. Add menu links to each sidebar
2. Style the menu items (add CSS in the `<style>` section)
3. Add icons if needed
4. Customize colors per role
