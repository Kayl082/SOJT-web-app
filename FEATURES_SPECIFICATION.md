# iTinda Features Specification

## 🧍 CUSTOMER FEATURES

### Account Management
- ✅ Register and log in to the system
- ✅ Manage personal profile
- ⏳ View order history

### Store Discovery
- ⏳ Search nearby stores by location
- ⏳ View store details (address, operating hours)

### Product Browsing & Comparison
- ⏳ Browse products by category
- ⏳ Compare prices across different vendors
- ⏳ View product availability per store

### Pre-Ordering
- ⏳ Pre-order products from selected vendors
- ⏳ View order status (pending, accepted, ready, completed)
- ⏳ Modify or cancel pre-orders within allowed time

---

## 🏪 VENDOR FEATURES (COMMON TO OWNER & STAFF)

### Store Operations
- ✅ Access assigned store dashboard
- ⏳ View store profile and product listings

### Product Management
- ⏳ Add and update product stock quantities
- ⏳ Mark products as available or out of stock

### Order Management
- ⏳ View incoming customer pre-orders
- ⏳ Update order status (pending, preparing, ready)

### Inventory Monitoring
- ⏳ View current stock levels
- ⏳ Receive low-stock alerts

---

## 👑 VENDOR – OWNER FEATURES (FULL ACCESS)

### Store Management
- ⏳ Create and update store profile
- ⏳ Add, edit, and remove products
- ⏳ Set and modify product prices

### Inventory Control
- ⏳ Full control of stock records
- ⏳ Track restocking history

### Financial Management
- ⏳ Track total sales and revenue
- ⏳ Record operational expenses
- ⏳ View profit and loss summaries

### Advanced Analytics
- ⏳ View complete sales analytics
- ⏳ Analyze product performance
- ⏳ Monitor revenue trends over time

### Staff Management
- ⏳ Create, edit, and remove staff accounts
- ⏳ Assign operational permissions
- ⏳ Monitor staff activity logs

---

## 👷 VENDOR – STAFF FEATURES (LIMITED ACCESS)

### Inventory Operations
- ⏳ Update product stock quantities
- ⏳ Assist in restocking operations

### Order Handling
- ⏳ Process customer pre-orders
- ⏳ Update order preparation status

### Operational Analytics
- ⏳ View stock movement reports
- ⏳ View product sales count (without revenue values)

---

## 🚫 STAFF RESTRICTIONS

Staff accounts CANNOT:
- ❌ View profits, expenses, or revenue
- ❌ Change product prices
- ❌ Create or manage staff accounts
- ❌ Access full analytics reports

---

## Database Schema Needed

### Products Table
```
- id
- shop_id (foreign key)
- name
- description
- price
- category_id (foreign key)
- stock_quantity
- is_available (boolean)
- created_by (user_id)
- updated_by (user_id)
- timestamps
```

### Categories Table
```
- id
- name
- slug
- description
- timestamps
```

### Orders Table
```
- id
- customer_id (foreign key to users)
- shop_id (foreign key)
- total_amount
- status (pending, accepted, preparing, ready, completed, cancelled)
- notes
- timestamps
```

### Order Items Table
```
- id
- order_id (foreign key)
- product_id (foreign key)
- quantity
- price_at_order
- subtotal
- timestamps
```

### Expenses Table (Owner only)
```
- id
- shop_id (foreign key)
- amount
- category
- description
- recorded_by (user_id)
- date
- timestamps
```

### Stock History Table
```
- id
- product_id (foreign key)
- user_id (who made the change)
- previous_quantity
- new_quantity
- change_type (restock, sale, adjustment)
- notes
- timestamps
```

### Activity Logs Table
```
- id
- user_id (foreign key)
- shop_id (foreign key)
- action
- description
- ip_address
- timestamps
```

---

## Implementation Priority

### Phase 1: Core Product & Inventory (Week 1-2)
1. Create Products table and model
2. Create Categories table and model
3. Product CRUD for Vendor Owner
4. Stock management for all vendors
5. Product listing pages

### Phase 2: Customer Features (Week 3-4)
1. Store discovery and search
2. Product browsing by category
3. Price comparison view
4. Store details page

### Phase 3: Order System (Week 5-6)
1. Create Orders and Order Items tables
2. Pre-order functionality for customers
3. Order management for vendors
4. Order status tracking

### Phase 4: Financial & Analytics (Week 7-8)
1. Expenses tracking (Owner only)
2. Sales analytics dashboard
3. Revenue reports
4. Profit/loss calculations

### Phase 5: Staff Management (Week 9-10)
1. Staff account creation by owner
2. Permission system refinement
3. Activity logging
4. Staff monitoring dashboard

---

## Next Steps

1. **Create database migrations** for all tables
2. **Create models** with relationships
3. **Build API routes** for each feature
4. **Create Vue components** for UI
5. **Implement role-based permissions** in controllers
6. **Add validation** and error handling
7. **Write tests** for critical features

Would you like to start with Phase 1 (Products & Inventory)?
