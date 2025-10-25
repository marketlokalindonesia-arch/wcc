# POS System Testing Guide

## Test Results Summary ✓

All POS functionality has been tested and verified:

- ✓ Admin login successful
- ✓ Cashier login successful  
- ✓ Product search working (found products by name)
- ✓ Barcode search working (found product by barcode: 1234567890123)
- ✓ Order creation successful (Order #ORD-20251025-6800)
- ✓ Inventory automatically updated after sale
- ✓ Inventory logs created for audit trail
- ✓ Dashboard statistics reflect real sales data

## Login Credentials

### Admin Account
- **Username**: `admin`
- **Password**: `password`
- **Access**: Full system access including POS

### Cashier Account  
- **Username**: `cashier`
- **Password**: `password`
- **Access**: POS and cashier dashboard only

## Access URLs

- **Admin POS**: `/?url=admin/pos/pos`
- **Cashier POS**: `/?url=cashier/pos`
- **Admin Dashboard**: `/?url=admin/dashboard`
- **Cashier Dashboard**: `/?url=cashier/dashboard`

## Sample Products with Barcodes

All 10 products in the database have barcodes for testing:

| Product | SKU | Barcode | Price |
|---------|-----|---------|-------|
| Laptop Gaming ASUS ROG | LAP-001 | 1234567890123 | $15,000,000 |
| iPhone 15 Pro Max | IPH-001 | 1234567890124 | $18,000,000 |
| Samsung Smart TV 55" | TV-001 | 1234567890125 | $8,000,000 |
| Nike Air Max 270 | SHOE-001 | 1234567890126 | $2,500,000 |
| Adidas Original Hoodie | CLOTH-001 | 1234567890127 | $850,000 |
| Sony WH-1000XM5 | HEAD-001 | 1234567890128 | $5,000,000 |
| MacBook Pro M3 | LAP-002 | 1234567890129 | $25,000,000 |
| Gaming Mouse Logitech | MOUSE-001 | 1234567890130 | $750,000 |
| Mechanical Keyboard | KEY-001 | 1234567890131 | $1,200,000 |
| Smart Watch Samsung | WATCH-001 | 1234567890132 | $4,500,000 |

**Note**: To check current stock levels, run:
```sql
SELECT name, stock_quantity FROM products ORDER BY name;
```

## Testing the Complete POS Workflow

### Step 1: Login
1. Go to `/?url=login`
2. Enter username: `admin` or `cashier`
3. Enter password: `password`
4. Click Login

### Step 2: Access POS
1. Admin users: Navigate to `/?url=admin/pos/pos`
2. Cashier users: Navigate to `/?url=cashier/pos`

### Step 3: Search for Products
**Option A: Text Search**
- Type product name in search box (e.g., "laptop", "mouse", "nike")
- Products appear in grid after 2+ characters

**Option B: Barcode Scan**
- Click in search box or scan barcode directly
- Try barcode: `1234567890123` (Laptop)
- Try barcode: `1234567890130` (Mouse)

### Step 4: Add to Cart
1. Click on any product card to add to cart
2. Product appears in right cart panel
3. Use +/- buttons to adjust quantity
4. Cart updates with totals automatically

### Step 5: Select Payment Method
Choose one of:
- **Cash** (money icon)
- **Card** (credit card icon)
- **E-Wallet** (wallet icon)
- **Bank Transfer** (bank icon)

Selected payment method highlights in purple.

### Step 6: Complete Order
1. Click "Complete Order" button (green gradient)
2. Order processes and shows confirmation with order number
3. Cart clears automatically
4. Ready for next transaction

## Verification After POS Sale

### Check Inventory Update
```sql
SELECT name, stock_quantity FROM products WHERE id IN (1, 8);
```
Stock quantities should decrease after each sale.

### Check Inventory Logs
```sql
SELECT * FROM inventory_logs ORDER BY created_at DESC LIMIT 5;
```
Each sale creates audit log entries.

### Check Dashboard Statistics
Navigate to admin dashboard to see:
- Total orders count
- Total revenue
- Today's sales statistics

## Automated Test Script

Run the automated test:
```bash
php test_pos_workflow.php
```

This script tests:
1. Login functionality
2. Product search
3. Barcode scanning
4. Order creation
5. Inventory updates
6. Inventory logging
7. Statistics tracking

## Known Working Features

✓ Product search by name (case-insensitive)
✓ Product search by SKU
✓ Product search by barcode  
✓ Real-time cart updates
✓ Quantity controls (increase/decrease)
✓ Stock validation (prevents overselling)
✓ Multiple payment methods
✓ Order completion with order number
✓ Automatic inventory reduction
✓ Inventory audit logging
✓ Transaction recording
✓ Cashier attribution
✓ Dashboard statistics updates

## Troubleshooting

**Products not showing?**
- Make sure you're logged in as admin or cashier
- Type at least 2 characters in search
- Check product status is 'publish' in database

**Can't complete order?**
- Make sure you selected a payment method
- Cart must have at least one item
- Check stock availability

**Stock not updating?**
- Check inventory_logs table for audit trail
- Verify order was completed successfully
- Check products table for updated stock_quantity

## Next Steps for Production

1. Add product images
2. Configure receipt printing
3. Set up payment gateway integrations
4. Add customer lookup/creation
5. Implement shift management
6. Add reporting features
