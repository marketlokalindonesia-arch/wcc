# WC Clone - Admin Panel Menu Structure

**Status**: ✅ Completed  
**Date**: October 25, 2025  
**Total Admin Files**: 29

## Sidebar Menu Overview

Admin panel dengan **collapsible sidebar menu** yang modern dan responsif, sesuai dengan 4 gambar referensi dari `attached_assets`.

---

## 1. 🏠 Dashboard
**Route**: `/admin/dashboard`  
**File**: `views/admin/dashboard.php`  
**Features**:
- Statistics cards (Revenue, Orders, Products, Customers, Today's Sales, Pending Orders, Low Stock)
- Sales chart (Chart.js - 7 days)
- Recent orders table
- Top products section

---

## 2. 💰 POS (Point of Sale)
**Main Route**: `/admin/pos`  
**Submenu**:

| Menu | Route | File | Description |
|------|-------|------|-------------|
| **POS Dashboard** | `/admin/pos` | `pos.php` | Main POS interface with barcode scanner |
| Roles | `/admin/pos/roles` | `pos/roles.php` | User roles & permissions management |
| Outlet | `/admin/pos/outlet` | `pos/outlet.php` | Multi-outlet management |
| Stock Settings | `/admin/pos/stock-settings` | `pos/stock-settings.php` | Stock threshold & alerts |
| Payment | `/admin/pos/payment` | `pos/payment.php` | Payment methods config |

---

## 3. 📦 Products
**Main Route**: `/admin/products`  
**Submenu**:

| Menu | Route | File | Description |
|------|-------|------|-------------|
| **All Products** | `/admin/products` | `products.php` | Product list with DataTables |
| Add New Product | `/admin/products/add` | `products/add.php` | Add product form |
| Categories | `/admin/products/categories` | `products/categories.php` | Category management |
| Brands | `/admin/products/brands` | `products/brands.php` | Brand management |
| Tags | `/admin/products/tags` | `products/tags.php` | Product tags |
| Attributes | `/admin/products/attributes` | `products/attributes.php` | Variations (Color, Size, etc) |
| Reviews | `/admin/products/reviews` | `products/reviews.php` | Product reviews moderation |

---

## 4. 🛍️ Orders
**Main Route**: `/admin/orders`  
**Submenu**:

| Menu | Route | File | Description |
|------|-------|------|-------------|
| **All Orders** | `/admin/orders` | `orders.php` | All orders with filters |
| Pending Orders | `/admin/orders/pending` | `orders/pending.php` | Orders awaiting processing |
| Processing | `/admin/orders/processing` | `orders/processing.php` | Orders being prepared |
| Completed | `/admin/orders/completed` | `orders/completed.php` | Finished orders |

---

## 5. 👥 Customers
**Route**: `/admin/customers`  
**File**: `views/admin/customers.php`  
**Features**:
- Customer list with search
- Order history per customer
- Customer statistics

---

## 6. 🎟️ Coupons
**Route**: `/admin/coupons`  
**File**: `views/admin/coupons.php`  
**Features**:
- Coupon code management
- Discount types (percentage/fixed)
- Usage limits & expiry dates

---

## 7. 🏭 Inventory
**Main Route**: `/admin/inventory`  
**Submenu**:

| Menu | Route | File | Description |
|------|-------|------|-------------|
| **Stock Overview** | `/admin/inventory` | `inventory.php` | Current stock levels |
| Inventory Logs | `/admin/inventory/logs` | `inventory/logs.php` | Stock movement history |
| Low Stock Alert | `/admin/inventory/low-stock` | `inventory/low-stock.php` | Products needing restock |

---

## 8. 📊 Analytics
**Main Route**: `/admin/reports` (Overview)  
**Submenu**:

| Menu | Route | File | Description |
|------|-------|------|-------------|
| **Overview** | `/admin/reports` | `reports.php` | General analytics dashboard |
| Products | `/admin/analytics/products` | `analytics/products.php` | Product performance |
| Revenue | `/admin/analytics/revenue` | `analytics/revenue.php` | Revenue trends |
| Orders | `/admin/analytics/orders` | `analytics/orders.php` | Order analytics |
| Customers | `/admin/analytics/customers` | `analytics/customers.php` | Customer insights |
| Stock | `/admin/analytics/stock` | `analytics/stock.php` | Stock analytics |

---

## 9. ⚙️ Settings
**Route**: `/admin/settings`  
**File**: `views/admin/settings.php`  
**Features**:
- General settings
- Payment gateway config
- Email settings
- Tax settings

---

## Technical Features

### ✅ Reusable Sidebar Component
**File**: `views/partials/admin_sidebar.php`
- Auto-detect active menu
- Collapsible submenus with smooth animation
- User profile display
- Bootstrap 5 integration

### ✅ Routing System
**File**: `index.php`
- 37 admin routes configured
- Clean URL structure
- Role-based access control

### ✅ Design Features
- **Purple gradient sidebar** (#667eea → #764ba2)
- **Collapsible menus** with Font Awesome icons
- **Active state highlighting**
- **Responsive design** (mobile-friendly)
- **Smooth transitions** & hover effects

---

## Database Integration

All menu pages are connected to PostgreSQL database:
- 14 tables imported
- 5 users (admin, cashier, customers)
- 10 sample products
- 6 sample orders
- Real-time data display

---

## Menu Icons Reference

| Menu | Icon Class |
|------|------------|
| Dashboard | `fa-home` |
| POS | `fa-cash-register` |
| Products | `fa-box` |
| Orders | `fa-shopping-bag` |
| Customers | `fa-users` |
| Coupons | `fa-ticket-alt` |
| Inventory | `fa-warehouse` |
| Analytics | `fa-chart-bar` |
| Settings | `fa-cog` |

---

## Authentication & Security

✅ All pages protected with `requireRole('admin')`  
✅ Session management via `config/session.php`  
✅ User profile display in sidebar  
✅ Logout functionality

---

## Next Steps (Optional Enhancements)

1. **CRUD Operations**: Add create/edit/delete functionality
2. **API Endpoints**: Build REST APIs for AJAX operations
3. **Real-time Updates**: WebSocket for live order notifications
4. **Export Functions**: PDF/Excel export for reports
5. **Advanced Filters**: Date range, status filters
6. **Image Upload**: Product image management
7. **Barcode Generator**: Auto-generate product barcodes

---

## File Structure

```
views/admin/
├── dashboard.php                 # Main dashboard
├── pos.php                       # POS system
├── products.php                  # Product list
├── orders.php                    # Order list
├── customers.php                 # Customer list
├── coupons.php                   # Coupon management
├── inventory.php                 # Stock overview
├── reports.php                   # Analytics overview
├── settings.php                  # Settings
├── pos/
│   ├── roles.php                # POS roles
│   ├── outlet.php               # Outlets
│   ├── stock-settings.php       # Stock config
│   └── payment.php              # Payment methods
├── products/
│   ├── add.php                  # Add product
│   ├── categories.php           # Categories
│   ├── brands.php               # Brands
│   ├── tags.php                 # Tags
│   ├── attributes.php           # Attributes
│   └── reviews.php              # Reviews
├── orders/
│   ├── pending.php              # Pending orders
│   ├── processing.php           # Processing
│   └── completed.php            # Completed
├── inventory/
│   ├── logs.php                 # Stock logs
│   └── low-stock.php            # Low stock alert
└── analytics/
    ├── products.php             # Product analytics
    ├── revenue.php              # Revenue analytics
    ├── orders.php               # Order analytics
    ├── customers.php            # Customer analytics
    └── stock.php                # Stock analytics
```

---

**Created by**: Replit Agent  
**Based on**: 4 reference images from attached_assets  
**Framework**: PHP + PostgreSQL + Bootstrap 5 + Font Awesome + Chart.js
