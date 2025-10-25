# 🚀 Quick Start Guide - WC Clone POS

## Login Cepat

### Cashier Account
```
URL: /?url=login
Username: cashier
Password: password
```

### Admin Account
```
URL: /?url=login  
Username: admin
Password: password
```

## Test POS dalam 30 Detik

1. **Login** → username: `cashier`, password: `password`
2. **Klik POS** menu atau ke `/?url=cashier/pos`
3. **Ketik** `laptop` di search box
4. **Klik** produk yang muncul → masuk cart
5. **Pilih** payment method (Cash/Card/E-Wallet)
6. **Klik** "Complete Order" → Done! ✅

## Test Barcode Scanner

Di halaman POS, ketik barcode ini di search box:
```
1234567890123
```
Produk "Laptop Gaming ASUS ROG" akan langsung muncul.

## Verifikasi Semua Fitur

Jalankan automated test:
```bash
php test_pos_workflow.php
```

Jika semua ✓ (centang hijau), sistem ready to use!

## Halaman Penting

- Login: `/?url=login`
- Admin Dashboard: `/?url=admin/dashboard`
- Admin POS: `/?url=admin/pos/pos`
- Cashier Dashboard: `/?url=cashier/dashboard`
- Cashier POS: `/?url=cashier/pos`

## Database Info

- PostgreSQL sudah setup ✓
- 10 sample products ✓
- 2 test users (admin, cashier) ✓
- Semua products punya barcode ✓

## Perlu bantuan detail?

Baca: `CARA_TEST_POS.md` atau `POS_TESTING_GUIDE.md`
