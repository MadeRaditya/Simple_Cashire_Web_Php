# Simple_Cahire_Web_Php

## Deskripsi

**Simple_Cahire_Web_Php** adalah sebuah aplikasi kasir restoran sederhana berbasis web yang dibangun dengan menggunakan **PHP**, **MySQL**, **HTML**, **CSS**, dan **JavaScript**. Aplikasi ini mendukung tiga jenis peran (role) pengguna dengan fitur yang disesuaikan:

- **Admin**:
  - Mengelola pengguna, menu, dan meja.
  - Mengakses dan mengelola riwayat pesanan serta transaksi.
  - Memiliki akses ke dashboard khusus.

- **Kasir**:
  - Mengelola pesanan.
  - Memproses pembayaran dan mencetak invoice.

- **Pelayan**:
  - Membuat, mengedit, dan mengelola pesanan.

---

## Teknologi yang Digunakan

- PHP
- MySQL
- HTML
- CSS
- JavaScript

---

## Instalasi

1. Clone repository ini atau unduh sebagai ZIP.
2. Jika menggunakan ZIP, ekstrak ke dalam folder `htdocs` (jika menggunakan XAMPP) atau ke folder web server lokal Anda.
3. Aktifkan Apache dan MySQL melalui XAMPP (atau alternatif lainnya).
4. Buka phpMyAdmin dan buat database baru dengan nama: `kasir-app`.
5. Import file `kasir-app.sql` dari folder `database/`.

---

## Cara Menjalankan

Akses melalui browser dengan URL (jika menggunaka XAMPP): http://localhost/project-kasir/


---

## Fitur Utama

- 3 Role Pengguna: Admin, Kasir, Pelayan
- Manajemen Pesanan dan Pembayaran
- Cetak Invoice Pembayaran oleh Kasir
- Dashboard Admin
- CRUD Menu, User, dan Meja
- Riwayat Pesanan dan Transaksi

---

<pre>
## Struktur Folder
```
/project-kasir
│
├── index.php
│
├── /public/
│   └── /assets/
│       └── /img/
│
├── /includes/
│   ├── auth.php
│   ├── db.php
│   ├── footer.php
│   └── header.php
│
├── /database/
│   └── project-kasir.sql
│
├── /pages/
│   ├── login.php
│   ├── logout.php
│   ├── register.php
│   ├── order_create.php
│   ├── order_list.php
│   ├── edit_order.php
│   ├── menu_list.php
│   ├── invoice.php
│   ├── payment.php
│   └── /admin/
│       ├── dashboard.php
│       ├── /meja/         # CRUD Meja
│       ├── /user/         # CRUD User
│       ├── /menu/         # CRUD Menu
│       ├── /order/        # Read Order
│       └── /payment/      # Read Payment
```
</pre>
---

## Struktur Database

Struktur tabel utama yang digunakan:

- `users` – Menyimpan data pengguna dan role
- `tables` – Meja restoran
- `menu_items` – Menu makanan/minuman
- `orders` – Data pesanan
- `order_items` – Item yang dipesan dalam pesanan
- `payments` – Pembayaran yang dilakukan


## Login Testing :

- **Admin**:
  - username: admin
  - password: Admin#1234

- **Kasir**:
  - username: kasir
  - password: Kasir#1234

- **Pelayan**:
  - username: pelayan
  - password: Pelayan#1234



