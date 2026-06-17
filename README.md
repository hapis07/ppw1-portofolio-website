# UAS Praktikum Pemrograman Web 1
## Aplikasi Portofolio Dinamis dengan Admin Panel (Bootstrap 5)

Aplikasi web portofolio ini dibangun menggunakan PHP Native (PDO) dan Bootstrap 5, memenuhi kriteria ujian akhir semester. 
Aplikasi memiliki dua bagian utama:
1. **Frontend (Portofolio Publik):** Menampilkan data yang ditarik secara dinamis dari database (Hero section, Skills, Featured Events, Contact Form).
2. **Backend (Admin Panel):** Menyediakan fitur login dan manajemen data CRUD (Create, Read, Update, Delete) untuk mengelola konten frontend.

### Identitas Mahasiswa
* **Nama:** (Silakan isi nama Anda)
* **NIM:** (Silakan isi NIM Anda)
* **Prodi:** Teknologi Rekayasa Perangkat Lunak
* **Mata Kuliah:** Praktikum Pemrograman Web 1

### Fitur Utama
* **Dashboard Admin:** Statistik ringkas menggunakan Bootstrap Cards.
* **Manajemen Portofolio (CRUD):** Tambah, edit, dan hapus event/proyek.
* **Manajemen Skills (CRUD):** Tambah, edit, dan hapus keahlian.
* **Manajemen Pesan:** Menerima pesan dari form kontak (Read & Delete) dengan Bootstrap Modal untuk melihat detail.
* **Pengaturan Tema:** Mengubah teks pada bagian Hero.
* **Keamanan:** Autentikasi dengan hashing `password_verify` dan perlindungan SQL Injection menggunakan PDO Prepared Statements.
* **UI/UX dengan Bootstrap 5:** Memanfaatkan Grid System (`container`, `row`, `col`), Cards, Alerts, Badges, Modals, dan Buttons, diracik dengan Custom Dark Theme.

### Panduan Instalasi
1. Pastikan Anda telah menginstal server lokal seperti **XAMPP** atau **Laragon**.
2. Pindahkan folder proyek ke dalam direktori root server (`htdocs` untuk XAMPP, `www` untuk Laragon).
3. Buat database baru di MySQL (misal: `uas_portofolio`).
4. Import struktur database dari file `database.sql` (jika tersedia) menggunakan phpMyAdmin.
5. Konfigurasikan koneksi database pada file `includes/config.php` sesuai dengan environment lokal Anda.
6. Akses aplikasi melalui web browser di `http://localhost/UAS_Portofolio`.
7. Untuk mengelola data, akses **Admin Panel** melalui halaman utama atau `http://localhost/UAS_Portofolio/pages/login.php`.
   - **Default Login Admin:** (sesuai akun yang didaftarkan di database, misal: `admin` / `admin123`)

### Struktur Proyek Utama
* `assets/` : File aset pendukung (css, js, img)
* `includes/` : File konfigurasi database dan potongan layout (header/footer)
* `pages/` : Halaman-halaman PHP khusus administrator (CRUD dan auth)
* `index.php` : Halaman pendaratan publik (Frontend)
* `database.sql` : Skema database untuk di-import
* `.gitignore` & `README.md` : Standarisasi dokumentasi & manajemen versi Git
