# WC Clone - E-Commerce & POS System

Sistem E-Commerce dan Point of Sale (POS) modern yang dibangun dengan PHP dan PostgreSQL.

## 🚀 Quick Start

### 1. Login
```
URL: /?url=login
```

**Akun Cashier:**
- Username: `cashier`
- Password: `cashier123`

**Akun Admin:**
- Username: `admin`  
- Password: `admin123`

### 2. Akses POS
Setelah login sebagai cashier:
```
URL: /?url=cashier/pos
```

### 3. Cari Produk
Di halaman POS, ketik di search box:
- Nama produk (min 2 huruf): `laptop`, `mouse`, `nike`
- Barcode: `1234567890123` (Laptop), `1234567890130` (Mouse)
- SKU: `LAP-001`, `MOUSE-001`

### 4. Testing & Debugging

**Debug Session:**
```
URL: /debug_session.php
```
Halaman ini menampilkan:
- Status session login
- User info  
- Test API langsung dari browser

**Run Automated Tests:**
```bash
php test_pos_workflow.php
```

## 📋 Fitur Utama

✅ **POS System**
- Search produk by nama/SKU/barcode
- Barcode scanner support
- Cart management dengan quantity controls
- Multiple payment methods (Cash, Card, E-Wallet, Bank Transfer)
- Auto inventory update
- Order numbering system

✅ **Inventory Management**
- Real-time stock tracking
- Automatic stock deduction on sales
- Inventory audit logs
- Low stock alerts

✅ **User Roles**
- Admin: Full access ke semua fitur
- Cashier: Access terbatas ke POS & dashboard

✅ **Dashboard & Reports**
- Sales statistics
- Revenue tracking
- Order management
- Top products

## 🗄️ Database

PostgreSQL database dengan 14 tables:
- users (admin, cashier accounts)
- products (10 sample products dengan barcodes)
- orders & order_items
- inventory_logs
- transactions
- cashier_shifts
- coupons, reviews, wishlist, cart

## 🛠️ Development

**PHP Error Logs:**
Error logs sudah dikonfigurasi keluar di console untuk debugging.

**Sample Products dengan Barcodes:**
- 1234567890123 → Laptop Gaming ASUS ROG
- 1234567890124 → iPhone 15 Pro Max
- 1234567890125 → Samsung Smart TV 55"
- 1234567890126 → Nike Air Max 270
- 1234567890127 → Adidas Original Hoodie  
- 1234567890128 → Sony WH-1000XM5
- 1234567890129 → MacBook Pro M3
- 1234567890130 → Gaming Mouse Logitech
- 1234567890131 → Mechanical Keyboard
- 1234567890132 → Smart Watch Samsung

## 📚 Documentation

- `QUICK_START.md` - Panduan cepat 30 detik
- `CARA_TEST_POS.md` - Panduan lengkap testing POS (Bahasa Indonesia)
- `POS_TESTING_GUIDE.md` - Comprehensive testing guide (English)
- `ADMIN_CREDENTIALS.md` - Login credentials & menu structure

## ⚠️ Troubleshooting

**Produk tidak muncul di POS?**
1. Pastikan sudah login sebagai admin/cashier
2. Ketik minimal 2 karakter di search box
3. Buka `/debug_session.php` untuk cek status session
4. Cek browser console (F12) untuk error JavaScript
5. Cek PHP error di Replit console

**Tidak bisa login?**
- Pastikan username & password lowercase: `cashier` / `password`
- Clear browser cookies jika perlu

## 🎯 Next Steps

Untuk production:
1. Ganti password default
2. Add product images
3. Setup payment gateway
4. Configure receipt printing
5. Add customer management
6. Implement shift management
7. Add comprehensive reporting

---

**Status**: ✅ Fully functional & tested
**Tech Stack**: PHP 8.2, PostgreSQL, Bootstrap 5, Vanilla JavaScript
