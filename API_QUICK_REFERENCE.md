# API Quick Reference

Quick cheat sheet untuk WC Clone REST API.

---

## 🔐 Authentication

```bash
# Login (required untuk sebagian besar endpoint)
POST /?url=login/authenticate
Body: username=cashier&password=password
```

**Default Accounts:**
- Admin: `admin` / `password`
- Cashier: `cashier` / `password`

---

## 📦 Products API

```bash
# Get all products
GET /api/products.php

# Search products
GET /api/products.php?search=laptop

# Get by category
GET /api/products.php?category_id=1

# Get featured products
GET /api/products.php?featured=1

# Create product (admin only)
POST /api/products.php
Body: name, price, stock_quantity, sku, ...
```

---

## 🛒 POS API

Requires authentication (admin/cashier)

```bash
# Search products
GET /api/pos.php?action=search&q=laptop

# Get by barcode
GET /api/pos.php?action=get_by_barcode&barcode=1234567890123

# Get cashier stats
GET /api/pos.php?action=cashier_stats&date=2025-10-25

# Create order
POST /api/pos.php?action=create_order
Content-Type: application/json
{
  "items": [
    {
      "product_id": 1,
      "name": "Product Name",
      "price": 100000,
      "quantity": 2
    }
  ],
  "payment_method": "Cash",
  "total_amount": 200000
}
```

---

## 🛍️ Cart API

```bash
# Get cart
GET /api/cart.php

# Get cart count
GET /api/cart.php?action=count

# Add to cart
POST /api/cart.php
Content-Type: application/json
{
  "action": "add",
  "product_id": 1,
  "quantity": 2
}

# Update cart
POST /api/cart.php
{
  "action": "update",
  "product_id": 1,
  "quantity": 3
}

# Remove from cart
POST /api/cart.php
{
  "action": "remove",
  "product_id": 1
}
```

---

## 📊 Common Responses

### Success Response
```json
{
  "success": true,
  "data": [...],
  "message": "Operation successful"
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error description"
}
```

---

## 🔢 Sample Barcodes

| Barcode | Product |
|---------|---------|
| 1234567890123 | Laptop Gaming ASUS ROG |
| 1234567890124 | iPhone 15 Pro Max |
| 1234567890125 | Samsung Smart TV 55" |
| 1234567890126 | Nike Air Max 270 |
| 1234567890127 | Adidas Original Hoodie |
| 1234567890128 | Sony WH-1000XM5 |
| 1234567890129 | MacBook Pro M3 |
| 1234567890130 | Gaming Mouse Logitech |
| 1234567890131 | Mechanical Keyboard |
| 1234567890132 | Smart Watch Samsung |

---

## 🧪 Test Commands

```bash
# Run test suite
php test_api_direct.php

# Test with curl
curl "http://localhost:5000/api/pos.php?action=search&q=laptop"

# Test with JavaScript
fetch('/api/pos.php?action=search&q=laptop')
  .then(r => r.json())
  .then(data => console.log(data));
```

---

## 💡 Tips

1. **Authentication:** Semua POS endpoint butuh login
2. **Minimum Search:** Search query minimal 2 karakter
3. **Payment Methods:** Cash, Card, E-Wallet, Bank Transfer
4. **Stock Check:** Selalu cek stock sebelum create order
5. **Error Handling:** Selalu cek field `success` di response

---

## 📚 Full Documentation

Lihat `API_DOCUMENTATION.md` untuk dokumentasi lengkap dengan contoh request/response detail.
