# 📚 LAPORAN TEKNIS & DOKUMENTASI SISTEM
## APLIKASI PENJUALAN BUKU DIGITAL "ACADEMIA READS"
**Sertifikasi Kompetensi: Junior Web Developer (BNSP)**

---

## 📑 DAFTAR ISI
1. [Deskripsi Proyek](#1-deskripsi-proyek)
2. [Spesifikasi Teknologi Utama (Stack)](#2-spesifikasi-teknologi-utama-stack)
3. [Algoritma & Logika Backend](#3-algoritma--logika-backend)
4. [Skema & Struktur Relasi Database (MySQL)](#4-skema--struktur-relasi-database-mysql)
5. [Alur Autentikasi Multi-Actor & Keamanan](#5-alur-autentikasi-multi-actor--keamanan)
6. [Optimasi Performa & User Experience (UX)](#6-optimasi-performa--user-experience-ux)
7. [Panduan Menjawab Pertanyaan Asesor](#7-panduan-menjawab-pertanyaan-asesor)

---

## 1. DESKRIPSI PROYEK
**Academia Reads** adalah aplikasi e-commerce berbasis web yang berfokus pada penjualan literatur akademis, jurnal, dan buku edukatif. Sistem ini memfasilitasi dua jenis aktor utama:
*   **User/Pelanggan:** Dapat mencari buku, melihat detail katalog, memasukkan buku ke keranjang (*cart*), melakukan *checkout* (sistem COD), dan melacak status pesanan mereka.
*   **Admin:** Memiliki akses ke Dashboard khusus untuk mengelola data master (Buku, Kategori, User), memproses status transaksi pesanan, dan membaca pesan masuk dari halaman "Contact Us".

---

## 2. SPESIFIKASI TEKNOLOGI UTAMA (STACK)

Aplikasi dibangun menggunakan arsitektur *Monolith Modern Server-Side Rendering (SSR)* dengan detail teknologi:
*   **Framework Backend:** Laravel 13.x (Versi LTS Terbaru). Menggunakan pola desain arsitektur MVC (Model-View-Controller).
*   **Database Engine:** MySQL / MariaDB (via XAMPP Environment).
*   **Sistem Autentikasi:** Laravel Breeze Starter Kit (Varian Blade Engine).
*   **Frontend Engine:** Laravel Blade Templates terintegrasi dengan **Tailwind CSS Utility-First Framework**.
*   **Asset Bundler:** Vite JS (Untuk kompilasi dan kompresi aset CSS/JS).

---

## 3. ALGORITMA & LOGIKA BACKEND

Bagian ini menjelaskan bagaimana data diproses di dalam Controller untuk menjamin integritas data dan kecepatan sistem:

### A. Algoritma State Management & Otomatisasi Stok
*   **Lokasi Kode:** `App\Http\Controllers\Admin\OrderController@updateStatus`
*   **Mekanisme:** Logika pemotongan stok dilakukan secara otomatis ketika admin mengubah status pesanan.
*   **Keamanan Mutasi Data:** Sistem menerapkan validasi kondisional. Pengurangan stok hanya dieksekusi **jika dan hanya jika** status *sebelumnya* bukan `Completed`, dan status *barunya* diubah menjadi `Completed`. Hal ini mencegah *double-counting* (stok terus berkurang jika admin menekan tombol simpan berulang kali).
*   **Logika Pengurangan:** `Stok Akhir = Stok Awal - Jumlah Beli (Quantity)`. Menggunakan method `decrement('stock', $quantity)` milik Eloquent.

### B. Konsep Data Fetching: Eager Loading `with()` (Mencegah N+1 Query)
*   **Lokasi Kode:** `App\Http\Controllers\Admin\BookController@index`
*   **Mekanisme:** Saat memuat halaman katalog buku yang memiliki relasi ke tabel Kategori, aplikasi menggunakan **Eager Loading** lewat method `Book::with('category')->paginate(10);`.
*   **Analisis Performa:** Jika menggunakan *Lazy Loading* biasa, sistem akan mengalami *N+1 Query problem* (mengeksekusi 1 query untuk mengambil buku, dan N query untuk mengambil kategorinya). Dengan Eager Loading, sistem hanya mengeksekusi maksimal **2 Query** SQL, yang memotong konsumsi memori server secara drastis.

### C. Logika Penyimpanan Keranjang Belanja (Session-Based Cart)
*   **Lokasi Kode:** `App\Http\Controllers\CartController`
*   **Mekanisme:** Fitur keranjang belanja (*Shopping Cart*) pada halaman publik tidak langsung disimpan ke database, melainkan menggunakan memori sementara server (RAM) melalui fitur bawaan `session()` milik Laravel.
*   **Alur Kerja:**
    1.  Saat user menekan tombol "Add to Cart", sistem menjalankan fungsi `session()->get('cart')` untuk mengambil array keranjang yang ada.
    2.  Jika buku sudah ada di dalam *array* keranjang, sistem hanya akan menambah *quantity* (+1).
    3.  Jika buku belum ada, sistem akan mendorong (*push*) data buku baru ke dalam *array*.
    4.  Terakhir, array yang sudah diubah disimpan kembali ke memori menggunakan `session()->put('cart', $cart)`.
*   **Alasan Teknis (Bahan Wawancara):** Kenapa menggunakan Session? Karena operasi penambahan keranjang sangat sering terjadi. Menyimpan keranjang belanja sementara ke *Session* (RAM) jauh lebih cepat dan tidak membebani I/O Database (Hardisk) dibandingkan harus melakukan `INSERT` ke database setiap kali user mengklik tombol beli. Data keranjang baru dipindahkan secara permanen ke Database (tabel `orders` & `order_items`) ketika user benar-benar melakukan proses **Checkout**.

### D. Algoritma Manajemen Pencarian Data (Searching)
*   **Mekanisme:** Fitur pencarian buku memanfaatkan klausa SQL `LIKE` yang dibungkus oleh Query Builder:
    `Book::where('title', 'LIKE', '%' . $request->search . '%')->paginate(10);`
*   **Optimasi:** Kolom `title` pada tabel `books` telah dipasang **Database Indexing** di file migrasi untuk mempercepat proses *lookup* teks di database.

---

## 4. SKEMA & STRUKTUR RELASI DATABASE (MYSQL)

Aplikasi ini menggunakan database dengan **6 Tabel Utama**. Berikut adalah struktur relasinya:

1.  **`users`**: Menyimpan data pengguna.
    *   Relasi: `1:M` ke tabel `orders` (Satu user bisa melakukan banyak order).
2.  **`categories`**: Menyimpan kategori buku.
    *   Relasi: `1:M` ke tabel `books` (Satu kategori memiliki banyak buku).
3.  **`books`**: Menyimpan data katalog buku.
    *   Relasi: `1:M` ke tabel `order_items` (Buku direferensikan dalam banyak detail order). Dibatasi dengan `category_id` (Foreign Key).
4.  **`orders`**: Menampung data transaksi induk (*Checkout*).
    *   Relasi: `1:M` ke tabel `order_items`. Dibatasi dengan `user_id` (Foreign Key).
5.  **`order_items`**: Tabel *pivot/detail* untuk menampung item keranjang belanja yang telah di-checkout.
    *   Relasi: Memiliki Foreign Key `order_id` dan `book_id` (Menciptakan hubungan M:M antara Order dan Book).
6.  **`messages`**: Menyimpan pesan dari halaman *Contact Us*.
    *   **Catatan:** Tabel ini **Standalone (Tidak berelasi)** karena dirancang agar pengunjung umum (*guest* yang tidak login) dapat mengirim pesan. Oleh karena itu, identitas disimpan murni sebagai string.

---

## 5. ALUR AUTENTIKASI MULTI-ACTOR & KEAMANAN

### A. Konsep Keamanan Session-Based Authentication
Aplikasi menggunakan **Session-Based Authentication**. Sangat cocok dan aman untuk arsitektur Monolith karena token sesi disimpan terenkripsi di dalam *cookie* browser, menghindari pencurian token yang sering terjadi pada JWT di sisi *client-storage*.

### B. Proteksi Middleware `RoleManager`
*   **Lokasi Kode:** `App\Http\Middleware\RoleManager`
*   **Cara Kerja:** Hak akses rute Admin dikunci rapat menggunakan *Custom Middleware*. Middleware bertindak sebagai "satpam" di tengah request; mengecek apakah user sudah login dan mengecek kolom `auth()->user()->role`. Jika bukan `'admin'`, request diblokir dengan status `403 Unauthorized`.

### C. Keamanan Data (CSRF Protection)
Setiap form input dengan method `POST/PUT/DELETE` wajib dilindungi dengan token `@csrf` (*Cross-Site Request Forgery*). Ini memastikan bahwa data yang masuk benar-benar dikirim dari UI aplikasi kita, bukan dari *script* eksternal yang diinjeksi oleh peretas.

---

## 6. OPTIMASI PERFORMA & USER EXPERIENCE (UX)

Sistem menerapkan optimasi di sisi tampilan:
1.  **Client-Side Asset Minification (Vite):** Seluruh file CSS (Tailwind) dibundel dan di-kompresi (*minify*) menggunakan Vite. Ukuran file menjadi sangat kecil, mempercepat *loading* halaman.
2.  **Konsistensi Visual & UI/UX:** Menggunakan standarisasi margin dan padding (misalnya pada halaman About dan Contact) agar tidak bentrok dengan navbar di ukuran *Desktop*, menggunakan kelas responsif dari Tailwind (seperti `md:py-24`, `lg:py-32`).

---
