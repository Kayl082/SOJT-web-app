# iTinda – System Architecture

## Overview

iTinda is a Laravel 12 + Inertia (Vue 3) multi-store marketplace application.

The system uses **store-based multi-tenancy**, where:

> Each store is treated as a tenant (row-level isolation via `store_id`).

This is NOT database-per-tenant and NOT subdomain tenancy.
Isolation is enforced at the model level.

---

# Multi-Tenancy Model

## Tenancy Rule

- Each **store = tenant**
- Isolation enforced via `store_id`
- Vendors operate only within their assigned store
- Customers can purchase from multiple stores
- Admin can access all stores

---

# Core Architecture

## Global Models (Not Scoped)

These are shared across all stores:

- `categories`
- `products` (global product catalog)
- `users` (all roles)

---

## Tenant-Scoped Models (Scoped via store_id)

These models use the `BelongsToStore` trait:

- `inventory`
- `orders`
- `expenses`
- `stores` (indirectly via user)

Isolation is enforced using:

- `StoreContext`
- `ResolveStoreTenant` middleware
- `BelongsToStore` global scope

---

## Indirect Isolation

### Order Items

`order_items` does NOT have `store_id`.

Instead:


OrderItem → belongsTo Order → has store_id


Isolation is guaranteed through the Order relationship.

Schema:

- `order_id`
- `inventory_id`
- `quantity`
- `unit_price`
- `subtotal`

---

# Roles (Spatie)

Roles implemented using `spatie/laravel-permission`.

Roles:

- `admin`
- `vendor_owner`
- `vendor_staff`
- `customer`

### Behavior

| Role | Store Scoped? | Notes |
|------|---------------|-------|
| admin | ❌ No | Can see all stores |
| vendor_owner | ✅ Yes | Restricted to assigned store |
| vendor_staff | ✅ Yes | Restricted to assigned store |
| customer | ❌ No | Not store-bound |

Admin bypass implemented inside `BelongsToStore` trait.

---

# StoreContext

`StoreContext` is a request-scoped service that:

- Stores current tenant `store_id`
- Is set via `ResolveStoreTenant` middleware
- Is used by `BelongsToStore` trait

---

# Middleware

## ResolveStoreTenant

Executed for vendor routes.

Logic:
- If authenticated user has vendor role
- Set StoreContext using `$user->store_id`
- Abort if store missing or inactive

---

# BelongsToStore Trait

Applied to tenant-bound models.

Features:

- Global scope filters by `store_id`
- Automatically assigns `store_id` on create
- Admin bypass supported
- Uses null-safe operator and typed user

---

# Testing Strategy

Tenancy is regression-protected with feature tests:

### Inventory Isolation Test
Vendor cannot access another store’s inventory.

### Order Isolation Test
Vendor cannot see another store’s orders.

### Order Item Isolation Test
Order items isolated indirectly via Order relationship.

### Admin Bypass Test
Admin can view all stores.

All tests passing.

---

# Database Relationships


Store
├── Users (vendor_owner, vendor_staff)
├── Inventory
│ └── Product (global)
├── Orders
│ └── OrderItems
│ └── Inventory
└── Expenses


Customers:

Customer
└── Orders (multi-store allowed)


---

# Cart & Checkout

Cart supports multi-store grouping.

Checkout process:
- Cart grouped by store
- One Order created per store
- OrderItems reference inventory

(Implementation ongoing)

---

# Security Model

- Row-level store isolation
- Role-based access control
- Policy layer (where applicable)
- No cross-store data exposure
- SQLite constraint validation in tests
- Global scopes enforce tenant boundary

---

# Current Status

✅ Store-based multi-tenancy implemented  
✅ Middleware tenant resolution  
✅ Global scope enforcement  
✅ Admin bypass logic  
✅ Order item indirect isolation  
✅ Regression tests passing  
✅ Schema aligned with factories  

---

# Future Improvements

- HTTP-level tenancy isolation tests
- Tenant-aware caching strategy
- Performance optimization
- Checkout order splitting refinement
- Store analytics module
- Background job tenant scoping

---

# Notes

This architecture is designed for:

- Multi-store marketplace scalability
- Single database, row-level isolation
- Clear tenant boundaries
- Strong regression safety
- Role-aware data access