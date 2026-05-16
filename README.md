# Academia Reads - Online BookStore Application

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

## 📖 Deskripsi Projek

**Academia Reads** adalah aplikasi *e-commerce* berbasis web (Platform Penjualan Buku Fisik) yang dikembangkan menggunakan *framework* **Laravel 13** dan **Tailwind CSS**. Aplikasi ini dibangun untuk mempermudah civitas akademika (mahasiswa, dosen, peneliti) dalam mencari, memesan, dan membeli buku-buku literatur akademik secara daring.

Sistem pembayaran yang diterapkan pada aplikasi ini berfokus pada metode transaksi **Cash on Delivery (COD)** atau *Payment at Delivery*, di mana pembeli akan membayar langsung kepada kurir saat buku diterima. Aplikasi ini juga telah dilengkapi dengan sistem manajemen hak akses (*Role-Based Access Control*) dan manajemen stok buku secara dinamis.

Projek ini disusun untuk memenuhi kualifikasi standar uji kompetensi Sertifikasi BNSP Skema Junior Web Developer.

---

## 🛠 Kebutuhan Sistem (Prerequisites)

Sebelum menjalankan aplikasi ini, pastikan sistem Anda telah menginstal beberapa perangkat lunak berikut:

- **PHP** versi 8.3 atau 8.4 (Sesuai dengan syarat minimal sistem Laravel 13).
- **Composer** (Dependency Manager untuk PHP).
- **Node.js & NPM** (Dibutuhkan untuk memproses dan *compile* *asset* frontend Tailwind CSS via Vite).
- **MySQL / MariaDB** (Sebagai sistem manajemen basis data relasional).

---

## 🏗 Arsitektur Database & Hak Akses (Roles)

Aplikasi ini membagi hak akses ke dalam **2 Aktor Utama** untuk memisahkan logika manajemen dan logika konsumen.

### 1. Hak Akses (Roles)
* **Admin:** Memiliki hak penuh atas sistem (*Back-Office*). Admin dapat mengelola inventaris (Tambah/Edit/Hapus Buku & Kategori), memantau dan mengubah status pesanan (Checkout), serta mengelola daftar *user* yang terdaftar di aplikasi (CRUD User).
* **User / Buyer:** Aktor yang berperan sebagai konsumen (*Front-Office*). Dapat melakukan registrasi, *login*, melakukan pencarian buku, memasukkan buku ke dalam *Shopping Cart* (Keranjang Belanja), melakukan proses *Checkout* (COD), hingga membatalkan pesanan jika berstatus *pending*.

### 2. Gambaran Relasi Tabel Singkat
Database aplikasi terdiri dari beberapa tabel utama yang saling berelasi:
* `users` : Menyimpan data autentikasi dan peran (*role*) akun.
* `categories` : Menyimpan data master kategori buku.
* `books` : Menyimpan katalog buku (berelasi dengan `categories`).
* `orders` : Menyimpan data induk transaksi/faktur (*invoice*) dari pembeli (berelasi dengan `users`).
* `order_items` : Menyimpan rincian buku apa saja yang dibeli di dalam satu nomor faktur/pesanan (berelasi dengan `orders` dan `books`).
* `messages` : Menyimpan masukan/pesan dari form *Contact Us*.

---

## 🚀 Langkah-Langkah Setup Projek (Installation)

Ikuti langkah-langkah di bawah ini secara berurutan untuk menjalankan projek Academia Reads di lingkungan komputer (*local environment*) Anda:

1. **Clone atau Ekstrak File Projek**
   Pindahkan folder projek ke dalam direktori lokal server Anda (contoh: `htdocs` untuk XAMPP atau `www` untuk WAMP).

2. **Buka Terminal / Command Prompt**
   Arahkan path terminal ke dalam folder utama projek.
   ```bash
   cd /path/ke/folder/academia-reads
   ```

3. **Install Dependencies Backend (PHP/Laravel)**
   Jalankan perintah Composer untuk mengunduh seluruh *vendor package* yang dibutuhkan.
   ```bash
   composer install
   ```

4. **Install & Compile Dependencies Frontend (Tailwind CSS)**
   Jalankan perintah Node.js untuk mengunduh pustaka *frontend* dan melakukan kompilasi *asset* *styling*.
   ```bash
   npm install
   npm run build
   ```

5. **Konfigurasi Environment Database (`.env`)**
   Salin file konfigurasi bawaan Laravel, lalu buka file `.env` yang baru dibuat dengan teks editor Anda.
   ```bash
   cp .env.example .env
   ```
   Sesuaikan baris koneksi database berikut pada file `.env` dengan pengaturan MySQL lokal Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=academia_reads
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Pastikan Anda telah membuat database kosong bernama `academia_reads` di phpMyAdmin / database client Anda).*

6. **Generate Application Key**
   Hasilkan kunci keamanan unik untuk instalasi Laravel ini.
   ```bash
   php artisan key:generate
   ```

7. **Migrasi Tabel & Seeding Data Dasar**
   Jalankan perintah migrasi ini untuk membentuk struktur tabel di database MySQL Anda sekaligus mengisi data (*seeding*) akun demo otomatis.
   ```bash
   php artisan migrate --seed
   ```

8. **Buat Symbolic Link Storage (Sangat Penting)**
   Jalankan perintah ini agar aset gambar (seperti gambar *cover* buku dan foto profil yang diunggah) dapat diakses dan ditampilkan di sisi halaman web (publik).
   ```bash
   php artisan storage:link
   ```

9. **Jalankan Lokal Development Server**
   ```bash
   php artisan serve
   ```
   Aplikasi sekarang dapat diakses melalui browser pada alamat: `http://localhost:8000`

---

## 🔑 Kredensial Akun Demo (Demo Accounts)

Untuk mempermudah pengujian fitur oleh asesor tanpa harus melakukan registrasi manual dari awal, Anda dapat langsung melakukan prosedur *Login* menggunakan data kredensial berikut (yang telah otomatis dibuat melalui proses *seeding*):

### Akun Admin (Full Access)
Digunakan untuk menguji fungsionalitas panel kontrol, mengelola inventaris, memperbarui status pesanan (*order*), dan CRUD *users*.
- **Email:** `admin@example.com`
- **Password:** `password`

### Akun User / Buyer
Digunakan untuk menguji alur konsumen: menambah barang ke keranjang (*Add to Cart*), pengujian profil, simulasi transaksi (*Checkout* COD), dan pembatalan pesanan.
- **Email:** `user@example.com`
- **Password:** `password`

---
*Dibuat untuk keperluan sertifikasi uji kompetensi BNSP Junior Web Developer.*