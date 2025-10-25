# Cara Test POS System - WC Clone

## ⚠️ PENTING: Harus Login Dulu!

Halaman POS **memerlukan login** sebagai admin atau cashier. Jika tidak login, halaman akan redirect ke home.

## Langkah-Langkah Test POS

### 1. Login sebagai Cashier
1. Buka browser, kunjungi: `/?url=login`
2. Masukkan credentials:
   - **Username**: `cashier`
   - **Password**: `password`
3. Klik Login
4. Otomatis redirect ke Cashier Dashboard

### 2. Akses POS Cashier
Dari dashboard, klik menu **POS** atau langsung ke: `/?url=cashier/pos`

### 3. Cari Produk
Ada 3 cara mencari produk:

**Cara 1: Ketik nama produk**
- Ketik minimal 2 huruf di search box
- Contoh: ketik `laptop` atau `mouse` atau `nike`
- Produk akan muncul otomatis

**Cara 2: Scan/Ketik Barcode**
- Klik di search box
- Ketik barcode lengkap, contoh: `1234567890123`
- Enter atau tunggu 300ms
- Produk muncul

**Cara 3: Cari SKU**
- Ketik SKU produk, contoh: `LAP-001`
- Produk akan muncul

### 4. Testing dengan Barcode
Coba barcode ini:
```
1234567890123 → Laptop Gaming ASUS ROG
1234567890124 → iPhone 15 Pro Max  
1234567890125 → Samsung Smart TV 55"
1234567890126 → Nike Air Max 270
1234567890127 → Adidas Original Hoodie
1234567890128 → Sony WH-1000XM5
1234567890129 → MacBook Pro M3
1234567890130 → Gaming Mouse Logitech
1234567890131 → Mechanical Keyboard
1234567890132 → Smart Watch Samsung
```

### 5. Tambah ke Cart
- Klik produk yang muncul
- Produk masuk ke cart (panel kanan)
- Gunakan tombol +/- untuk ubah quantity

### 6. Pilih Metode Pembayaran
Pilih salah satu:
- 💵 Cash
- 💳 Card  
- 👛 E-Wallet
- 🏦 Bank Transfer

### 7. Complete Order
- Klik tombol hijau "Complete Order"
- Muncul alert dengan nomor order
- Cart otomatis clear
- Siap transaksi berikutnya

## Troubleshooting

### ❌ Produk Tidak Muncul?

**Cek 1: Apakah sudah login?**
- Jika belum, login dulu sebagai admin/cashier
- Lihat di pojok kanan atas, ada nama user tidak?

**Cek 2: Sudah ketik minimal 2 karakter?**
- Search baru aktif setelah 2+ karakter
- Coba ketik: `lap` atau `mou`

**Cek 3: Ada error di browser console?**
- Buka Developer Tools (F12)
- Lihat tab Console
- Ada error merah? Screenshot dan kirim

**Cek 4: API berjalan?**
- Buka Developer Tools → Network tab
- Ketik di search box
- Lihat request ke `/api/pos.php?action=search&q=...`
- Status harus 200, bukan 401/403/500

### ❌ Tidak Bisa Login?

Pastikan credentials benar:
- Username: `cashier` (huruf kecil semua)
- Password: `password` (huruf kecil semua)

Jika masih gagal, reset password:
```bash
php -r "echo password_hash('password', PASSWORD_DEFAULT);" 
# Copy hasil hash
# Update di database users table
```

### ❌ Halaman Blank/Redirect ke Home?

Ini berarti tidak ada session login. Solusi:
1. Clear browser cache/cookies
2. Login ulang
3. Pastikan cookie enabled di browser

## Test Script Otomatis

Jalankan automated test untuk verify semua fungsi:
```bash
php test_pos_workflow.php
```

Test ini akan:
✓ Test login admin & cashier
✓ Test search produk
✓ Test barcode scanner
✓ Test create order
✓ Verify inventory update
✓ Check inventory logs
✓ Verify dashboard stats

## Login Alternatif: Admin

Jika mau test dengan akses full admin:
- **Username**: `admin`
- **Password**: `password`
- **POS URL**: `/?url=admin/pos/pos`

Admin punya akses lebih banyak termasuk:
- Dashboard lengkap
- Manage products
- Manage users
- View reports
- Dan semua fitur POS

## Video Tutorial (Coming Soon)

Akan ditambahkan screen recording untuk demo lengkap.

## Butuh Bantuan?

Jika masih ada masalah:
1. Screenshot halaman error
2. Screenshot browser console (F12)
3. Screenshot Network tab saat search produk
4. Kirim semua screenshot untuk debugging

---

**Happy Selling! 🛒💰**
