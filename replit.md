# WC Clone - E-Commerce POS System

## Overview

WC Clone is a comprehensive e-commerce platform with an integrated Point of Sale (POS) system. The application combines traditional online shopping functionality with retail store operations, featuring role-based access control (Admin and Cashier roles), inventory management, and real-time sales processing.

The system is built as a PHP MVC application with a modern Bootstrap 5 frontend, supporting multi-role user management, product catalog operations, order processing, and barcode-enabled POS transactions.

## User Preferences

Preferred communication style: Simple, everyday language.

## System Architecture

### Application Structure

**MVC Pattern Implementation**
- The application follows a Model-View-Controller architecture with URL-based routing
- Routes are handled through a `?url=` parameter system (e.g., `/?url=admin/dashboard`)
- Controllers manage business logic and data flow
- Views render the UI using PHP templates with inline Bootstrap 5 styling
- No explicit Models layer mentioned - likely uses direct database queries or simple data access patterns

**Authentication & Authorization**
- Role-based access control with two primary roles: `admin` and `cashier`
- Admin users have full system access including dashboard, POS, products, and orders management
- Cashier users have restricted access limited to POS operations and cashier dashboard
- Login system with role-based redirection after authentication
- Session-based authentication managed through AuthController

**Frontend Architecture**
- Bootstrap 5 for responsive UI components and styling
- Custom CSS with CSS variables for theming (primary colors, transitions, shadows)
- Vanilla JavaScript for interactive features (no heavy frameworks)
- Chart.js for data visualization in dashboards
- Gradient-based modern design system with custom color schemes
- Mobile-responsive layouts optimized for tablets (especially POS interface)

**POS System Design**
- Dual interface: separate admin POS (`/admin/pos/pos`) and cashier POS (`/cashier/pos`)
- Real-time product search with live filtering
- Barcode scanner integration using keyboard input detection
- Touch-friendly UI for tablet-based retail operations
- Shopping cart with quantity controls and payment method selection
- Supports multiple payment methods: Cash, Card, E-Wallet, Bank Transfer
- Automatic inventory updates upon sale completion
- Order numbering system with format: `ORD-YYYYMMDD-XXXX`

**Inventory Management**
- Real-time stock tracking with automatic updates on sales
- Low stock alerts and threshold settings
- Inventory audit trail through inventory logs
- Stock settings configuration per outlet
- Multi-outlet support architecture

**Product Management**
- Comprehensive product catalog with categories, brands, tags, and attributes
- Product variations support (Color, Size, etc.)
- Image upload and gallery management (multiple images per product)
- SKU and barcode assignment for retail operations
- Product review and moderation system
- Pricing and stock quantity tracking

**Dashboard & Reporting**
- Admin dashboard with comprehensive statistics:
  - Total Revenue, Orders, Products, Customers
  - Today's Sales and Pending Orders
  - Low Stock Items monitoring
- 7-day sales chart visualization using Chart.js
- Recent orders table with status tracking
- Top products performance metrics
- Cashier dashboard with simplified, role-appropriate metrics

**Order Management**
- Order lifecycle tracking: Pending → Processing → Completed
- Order status badges for visual status identification
- Recent orders display with details
- Order creation through POS with automatic inventory deduction

### File Organization

**View Structure**
- `views/auth/login.php` - Standalone authentication page
- `views/admin/dashboard.php` - Admin statistics and overview
- `views/admin/pos.php` - Admin POS interface
- `views/admin/products/` - Product management views (add, categories, brands, tags, attributes, reviews)
- `views/admin/pos/` - POS submodules (roles, outlet, stock-settings, payment)
- `views/cashier/dashboard.php` - Cashier-specific dashboard
- `views/product-form.html` - Product creation form

**Asset Organization**
- `assets/css/style.css` - Main stylesheet with CSS variables
- `assets/css/footer.css` - Modular footer styling
- `assets/js/main.js` - Core JavaScript functionality (cart, wishlist, tabs, modals)

**API Structure**
- `api/pos.php` - POS transaction endpoints
- `api/products.php` - Product CRUD operations
- RESTful-style endpoints for AJAX operations

### Key Design Decisions

**Barcode System**
- All products assigned unique barcodes (13-digit format)
- Keyboard-based barcode scanner detection for rapid checkout
- Sample data includes 10 products with pre-assigned barcodes for testing

**Sidebar Navigation**
- Collapsible sidebar menu with dropdown submenus
- Active state detection using URL pattern matching
- Custom JavaScript toggle function (no Bootstrap collapse dependency)
- Inline logic for better performance and type-checking compliance

**Database Approach**
- No explicit ORM mentioned - likely uses native PHP database functions
- Inventory logs table for audit trail
- Structured product data with relationships (categories, brands, tags, attributes)

**Error Handling & Code Quality**
- LSP (Language Server Protocol) compliance achieved (0 errors after refactoring)
- Inline logic preferred over helper functions for better type-checking
- Clean variable naming conventions for readability

## External Dependencies

**Frontend Libraries**
- Bootstrap 5 - UI framework for responsive design and components
- Chart.js - Data visualization for sales charts and analytics
- Font Awesome / Icons library (implied from footer.css usage)

**JavaScript Features**
- Native JavaScript (ES6+) - No jQuery or heavy frameworks
- Event delegation for dynamic content handling
- Modal and tab management
- Image gallery and quantity controls
- Wishlist functionality

**Backend Technologies**
- PHP - Server-side language (MVC implementation)
- Session management for authentication
- File upload handling for product images

**Potential Database**
- Relational database structure implied (products, orders, inventory_logs, categories, brands, etc.)
- Likely MySQL/MariaDB or PostgreSQL based on typical PHP stack

**Testing Data**
- Pre-seeded database with 10 sample products
- Default admin and cashier accounts for testing
- Sample barcodes and SKUs for POS testing