# WC Clone - REST API Documentation

Dokumentasi lengkap untuk REST API WC Clone E-Commerce & POS System.

## 📋 Table of Contents

1. [Authentication](#authentication)
2. [Products API](#products-api)
3. [POS API](#pos-api)
4. [Cart API](#cart-api)
5. [Error Handling](#error-handling)
6. [Response Codes](#response-codes)

---

## 🔐 Authentication

Sebagian besar endpoint memerlukan autentikasi. User harus login terlebih dahulu melalui sistem session.

### Login
```
POST /?url=login/authenticate
Content-Type: application/x-www-form-urlencoded

username=cashier&password=password
```

**Credentials:**
- **Admin**: username: `admin`, password: `password`
- **Cashier**: username: `cashier`, password: `password`

**Response:**
- HTTP 302 Redirect ke dashboard yang sesuai

**Session Cookie:**
Setelah login, PHPSESSID cookie akan di-set dan harus disertakan di setiap request selanjutnya.

---

## 📦 Products API

Base URL: `/api/products.php`

### 1. Get All Products

**Endpoint:** `GET /api/products.php`

**Description:** Mengambil semua produk atau produk dengan filter tertentu.

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| category_id | integer | No | Filter berdasarkan kategori |
| search | string | No | Cari produk berdasarkan nama/deskripsi |
| featured | boolean | No | Filter produk unggulan (1 = featured) |

**Example Request:**
```bash
GET /api/products.php
GET /api/products.php?search=laptop
GET /api/products.php?category_id=1
GET /api/products.php?featured=1
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Laptop Gaming ASUS ROG",
      "slug": "laptop-gaming-asus-rog",
      "description": "High performance gaming laptop with RTX 3060",
      "short_description": "ASUS ROG gaming laptop",
      "price": "15000000.00",
      "sale_price": "14500000.00",
      "stock_quantity": 5,
      "sku": "LAP-001",
      "barcode": "1234567890123",
      "status": "publish",
      "created_at": "2025-10-25 17:58:37.609923"
    }
  ]
}
```

**Response Codes:**
- `200 OK` - Success
- `405 Method Not Allowed` - Invalid HTTP method

---

### 2. Create Product

**Endpoint:** `POST /api/products.php`

**Description:** Membuat produk baru (requires admin authentication).

**Headers:**
```
Content-Type: multipart/form-data
```

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| name | string | Yes | Nama produk |
| description | text | No | Deskripsi lengkap |
| short_description | text | No | Deskripsi singkat |
| price | decimal | Yes | Harga normal |
| sale_price | decimal | No | Harga diskon |
| stock_quantity | integer | Yes | Jumlah stok |
| sku | string | Yes | Stock Keeping Unit |
| barcode | string | No | Barcode produk |
| category_id | integer | No | ID kategori |
| images[] | file | No | Upload gambar produk |

**Example Request:**
```bash
POST /api/products.php
Content-Type: multipart/form-data

name=New Product
price=1000000
stock_quantity=10
sku=PROD-001
```

**Example Response:**
```json
{
  "success": true,
  "message": "Product created successfully",
  "product_id": 11
}
```

**Response Codes:**
- `200 OK` - Success
- `400 Bad Request` - Invalid data
- `401 Unauthorized` - Not authenticated
- `405 Method Not Allowed` - Invalid HTTP method

---

## 🛒 POS API

Base URL: `/api/pos.php`

**Authentication Required:** Yes (admin or cashier role)

### 1. Search Products

**Endpoint:** `GET /api/pos.php?action=search&q={query}`

**Description:** Mencari produk berdasarkan nama, SKU, atau barcode.

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| action | string | Yes | Must be "search" |
| q | string | Yes | Search query (min 2 characters) |

**Example Request:**
```bash
GET /api/pos.php?action=search&q=laptop
```

**Example Response:**
```json
{
  "success": true,
  "products": [
    {
      "id": 1,
      "name": "Laptop Gaming ASUS ROG",
      "sku": "LAP-001",
      "barcode": "1234567890123",
      "price": "15000000.00",
      "sale_price": "14500000.00",
      "stock_quantity": 5,
      "final_price": "14500000.00"
    }
  ]
}
```

**Response Codes:**
- `200 OK` - Success (empty array if no results)
- `401 Unauthorized` - Not logged in
- `403 Forbidden` - Invalid role

---

### 2. Get Product by Barcode

**Endpoint:** `GET /api/pos.php?action=get_by_barcode&barcode={barcode}`

**Description:** Mengambil detail produk berdasarkan barcode.

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| action | string | Yes | Must be "get_by_barcode" |
| barcode | string | Yes | Product barcode |

**Example Request:**
```bash
GET /api/pos.php?action=get_by_barcode&barcode=1234567890123
```

**Example Response:**
```json
{
  "success": true,
  "product": {
    "id": 1,
    "name": "Laptop Gaming ASUS ROG",
    "sku": "LAP-001",
    "barcode": "1234567890123",
    "price": "15000000.00",
    "sale_price": "14500000.00",
    "stock_quantity": 5,
    "final_price": "14500000.00"
  }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Product not found"
}
```

**Response Codes:**
- `200 OK` - Success
- `404 Not Found` - Product not found
- `401 Unauthorized` - Not logged in

---

### 3. Create Order

**Endpoint:** `POST /api/pos.php?action=create_order`

**Description:** Membuat order baru dari POS system.

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "name": "Laptop Gaming ASUS ROG",
      "price": 14500000,
      "quantity": 1
    }
  ],
  "payment_method": "Cash",
  "total_amount": 14500000,
  "customer_id": 1
}
```

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| items | array | Yes | Array of order items |
| items[].product_id | integer | Yes | Product ID |
| items[].name | string | Yes | Product name |
| items[].price | decimal | Yes | Product price |
| items[].quantity | integer | Yes | Quantity ordered |
| payment_method | string | Yes | Cash, Card, E-Wallet, Bank Transfer |
| total_amount | decimal | Yes | Total order amount |
| customer_id | integer | No | Customer ID (optional) |

**Example Response:**
```json
{
  "success": true,
  "message": "Order created successfully",
  "order": {
    "order_id": 7,
    "order_number": "ORD-20251025-0007",
    "total_amount": 14500000,
    "payment_method": "Cash",
    "status": "completed",
    "created_at": "2025-10-25 21:30:00"
  }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Invalid order data"
}
```

**Response Codes:**
- `200 OK` - Success
- `400 Bad Request` - Invalid data
- `401 Unauthorized` - Not logged in
- `500 Internal Server Error` - Server error

---

### 4. Get Cashier Stats

**Endpoint:** `GET /api/pos.php?action=cashier_stats&date={date}`

**Description:** Mengambil statistik penjualan kasir untuk tanggal tertentu.

**Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| action | string | Yes | Must be "cashier_stats" |
| date | string | No | Date (Y-m-d format, default: today) |

**Example Request:**
```bash
GET /api/pos.php?action=cashier_stats&date=2025-10-25
```

**Example Response:**
```json
{
  "success": true,
  "stats": {
    "total_orders": 12,
    "total_sales": 48500000,
    "total_items_sold": 25,
    "average_transaction": 4041666.67,
    "payment_methods": {
      "Cash": 5,
      "Card": 4,
      "E-Wallet": 3
    }
  }
}
```

**Response Codes:**
- `200 OK` - Success
- `401 Unauthorized` - Not logged in
- `403 Forbidden` - Invalid role

---

## 🛍️ Cart API

Base URL: `/api/cart.php`

**Note:** Cart API menggunakan session-based cart untuk demo purposes.

### 1. Get Cart Items

**Endpoint:** `GET /api/cart.php`

**Description:** Mengambil semua item dalam cart.

**Example Request:**
```bash
GET /api/cart.php
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "product_id": 1,
      "quantity": 2,
      "added_at": "2025-10-25 21:30:00"
    }
  ]
}
```

**Response Codes:**
- `200 OK` - Success

---

### 2. Get Cart Count

**Endpoint:** `GET /api/cart.php?action=count`

**Description:** Mengambil jumlah total item dalam cart.

**Example Request:**
```bash
GET /api/cart.php?action=count
```

**Example Response:**
```json
{
  "success": true,
  "count": 5
}
```

**Response Codes:**
- `200 OK` - Success

---

### 3. Add to Cart

**Endpoint:** `POST /api/cart.php`

**Description:** Menambah produk ke cart.

**Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "action": "add",
  "product_id": 1,
  "quantity": 2
}
```

**Parameters:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| action | string | Yes | Must be "add" |
| product_id | integer | Yes | Product ID |
| quantity | integer | Yes | Quantity to add |

**Example Response:**
```json
{
  "success": true,
  "message": "Product added to cart successfully",
  "cart_count": 5,
  "cart_items": [
    {
      "product_id": 1,
      "quantity": 2,
      "added_at": "2025-10-25 21:30:00"
    }
  ]
}
```

**Response Codes:**
- `200 OK` - Success
- `400 Bad Request` - Invalid data

---

### 4. Update Cart Item

**Endpoint:** `POST /api/cart.php`

**Description:** Update quantity produk dalam cart.

**Request Body:**
```json
{
  "action": "update",
  "product_id": 1,
  "quantity": 3
}
```

**Example Response:**
```json
{
  "success": true,
  "message": "Cart updated successfully",
  "cart_count": 6,
  "cart_items": [...]
}
```

**Response Codes:**
- `200 OK` - Success
- `400 Bad Request` - Invalid data

---

### 5. Remove from Cart

**Endpoint:** `POST /api/cart.php`

**Description:** Hapus produk dari cart.

**Request Body:**
```json
{
  "action": "remove",
  "product_id": 1
}
```

**Example Response:**
```json
{
  "success": true,
  "message": "Product removed from cart",
  "cart_count": 3,
  "cart_items": [...]
}
```

**Response Codes:**
- `200 OK` - Success
- `400 Bad Request` - Invalid data

---

## ⚠️ Error Handling

Semua API endpoint menggunakan consistent error format:

```json
{
  "success": false,
  "message": "Error description here"
}
```

**Common Error Messages:**
- `"Unauthorized"` - User belum login
- `"Access denied"` - User tidak punya permission
- `"Invalid action"` - Action parameter tidak valid
- `"Product not found"` - Produk tidak ditemukan
- `"Invalid order data"` - Data order tidak lengkap/valid
- `"Method not allowed"` - HTTP method tidak didukung

---

## 📊 Response Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 302 | Redirect - Used for login redirect |
| 400 | Bad Request - Invalid input data |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource not found |
| 405 | Method Not Allowed - Invalid HTTP method |
| 500 | Internal Server Error - Server error |

---

## 🔧 Testing

Untuk menjalankan automated API tests:

```bash
php test_api.php
```

Script ini akan:
1. Login sebagai cashier
2. Test semua endpoint Products API
3. Test semua endpoint POS API
4. Test semua endpoint Cart API
5. Menampilkan summary hasil testing

---

## 💡 Best Practices

1. **Always include authentication** - Gunakan session cookie dari login
2. **Handle errors gracefully** - Check `success` field di response
3. **Validate input** - Server melakukan validation, tapi client juga harus validate
4. **Use appropriate HTTP methods** - GET untuk read, POST untuk write
5. **Check stock availability** - Sebelum create order, pastikan stok cukup
6. **Rate limiting** - Implement rate limiting di production environment
7. **HTTPS only** - Gunakan HTTPS di production untuk security

---

## 📝 Notes

- API menggunakan **session-based authentication**
- Cart API untuk demo menggunakan **PHP sessions**
- POS API memerlukan role **admin** atau **cashier**
- Semua response dalam format **JSON**
- Timestamps menggunakan timezone **server default**
- Price menggunakan format **decimal(10,2)** dalam database

---

## 🚀 Example Usage with JavaScript

### Fetch API Example

```javascript
// Login
async function login(username, password) {
  const response = await fetch('/?url=login/authenticate', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `username=${username}&password=${password}`,
    credentials: 'include' // Include cookies
  });
  return response.ok;
}

// Search products in POS
async function searchProducts(query) {
  const response = await fetch(`/api/pos.php?action=search&q=${query}`, {
    credentials: 'include'
  });
  const data = await response.json();
  return data.products;
}

// Create order
async function createOrder(items, paymentMethod, totalAmount) {
  const response = await fetch('/api/pos.php?action=create_order', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include',
    body: JSON.stringify({
      items,
      payment_method: paymentMethod,
      total_amount: totalAmount
    })
  });
  return await response.json();
}

// Add to cart
async function addToCart(productId, quantity) {
  const response = await fetch('/api/cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include',
    body: JSON.stringify({
      action: 'add',
      product_id: productId,
      quantity
    })
  });
  return await response.json();
}
```

### cURL Examples

```bash
# Login
curl -X POST 'http://localhost:5000/?url=login/authenticate' \
  -H 'Content-Type: application/x-www-form-urlencoded' \
  -d 'username=cashier&password=password' \
  -c cookies.txt

# Search products
curl -X GET 'http://localhost:5000/api/pos.php?action=search&q=laptop' \
  -b cookies.txt

# Create order
curl -X POST 'http://localhost:5000/api/pos.php?action=create_order' \
  -H 'Content-Type: application/json' \
  -b cookies.txt \
  -d '{
    "items": [
      {
        "product_id": 1,
        "name": "Laptop Gaming ASUS ROG",
        "price": 14500000,
        "quantity": 1
      }
    ],
    "payment_method": "Cash",
    "total_amount": 14500000
  }'

# Add to cart
curl -X POST 'http://localhost:5000/api/cart.php' \
  -H 'Content-Type: application/json' \
  -b cookies.txt \
  -d '{
    "action": "add",
    "product_id": 1,
    "quantity": 2
  }'
```

---

**Last Updated:** October 25, 2025  
**Version:** 1.0  
**API Status:** ✅ Fully Functional
