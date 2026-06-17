# 🎬 SCRIPT VIDEO PRESENTASI UAS — Personal Portfolio Event Organizer

> **Target Durasi:** 7–8 menit  
> **Format:** Screen recording + voice-over  
> **Tools yang perlu disiapkan sebelum rekam:**
> - Browser: Buka `localhost/UAS_Portofolio` (tab 1) dan `phpMyAdmin` (tab 2)
> - Code Editor (VS Code / Antigravity): Buka file `index.php`, `db.sql`, `api/contact.php`, `dashboard.php`
> - Notepad: Siapkan query SQL untuk copy-paste saat demo database

---

## ⏱️ 0:00 – 0:40 | Pembukaan & Perkenalan Diri

**📺 Layar:** Tampilkan halaman utama website portfolio (hero section terlihat penuh)

**🎙️ Narasi:**
> "Assalamualaikum warahmatullahi wabarakatuh. Selamat [pagi/siang/malam], perkenalkan nama saya **[NAMA LENGKAP]**, dengan NIM **[NIM]**, dari kelas **[KELAS]**, Program Studi **[PRODI]**, Fakultas **[FAKULTAS]**, Universitas **[UNIVERSITAS]**.
>
> Pada video kali ini saya akan mempresentasikan proyek Ujian Akhir Semester mata kuliah **[NAMA MATAKULIAH]** yang diampu oleh **[NAMA DOSEN]**."

---

## ⏱️ 0:40 – 1:40 | Penjelasan Proyek & Permasalahan

**📺 Layar:** Tetap di halaman utama, scroll pelan dari atas ke bawah agar semua section terlihat sekilas

**🎙️ Narasi:**
> "Proyek yang saya buat adalah sebuah website **Personal Portfolio** yang dikhususkan untuk profil seorang **Event Organizer**.
>
> **Permasalahan yang ingin saya selesaikan** adalah: sebagai mahasiswa yang aktif menjadi panitia di berbagai acara kampus, saya sering kesulitan untuk menyajikan rekam jejak pengalaman saya secara profesional. Kebanyakan orang hanya mengandalkan CV dokumen biasa yang statis. Oleh karena itu, saya membuat website ini sebagai solusi agar portofolio saya dapat ditampilkan secara **digital, interaktif, dan mudah diakses** oleh siapa saja melalui browser.
>
> Website ini memiliki beberapa fitur utama:
> 1. Halaman profil dengan foto dan deskripsi diri
> 2. Daftar keahlian atau *skills* beserta persentasenya
> 3. Portofolio acara yang pernah saya tangani, lengkap dengan galeri foto
> 4. Form kontak bagi pengunjung yang ingin menghubungi saya
> 5. Dan sebuah **Admin Panel / CMS** untuk mengelola seluruh konten website tanpa harus mengedit kode"

---

## ⏱️ 1:40 – 3:40 | Demo Website — Halaman Utama

### 1:40 – 2:00 | Navbar & Hero Section
**📺 Layar:** Scroll kembali ke paling atas. Arahkan kursor ke navbar.

**🎙️ Narasi:**
> "Mari kita mulai demo dari bagian atas. Di sini terdapat **navigation bar** yang bersifat *fixed* — artinya akan tetap menempel di atas saat halaman di-scroll. Terdapat menu About, Skills, Portfolio, Contact, dan tombol Admin untuk masuk ke halaman pengelolaan.
>
> Di bawahnya adalah **Hero Section** atau bagian *About*, yang menampilkan sapaan, judul profesi saya sebagai Event Organizer, deskripsi singkat, dua buah tombol aksi, serta foto profil saya."

### 2:00 – 2:30 | Skills Section
**📺 Layar:** Scroll pelan ke section Skills. Hover salah satu skill row agar efek geser terlihat.

**🎙️ Narasi:**
> "Berikutnya adalah section **My Expertise** yang menampilkan keahlian saya. Setiap baris skill memiliki nama dan persentase kemahiran. Saat kursor diarahkan ke salah satu baris, terdapat efek *hover* berupa pergeseran halus ke kanan yang memberikan kesan interaktif."

### 2:30 – 3:10 | Portfolio Section
**📺 Layar:** Scroll ke Portfolio. Hover ke salah satu card agar overlay muncul.

**🎙️ Narasi:**
> "Kemudian, section **Featured Events** menampilkan acara-acara yang pernah saya tangani dalam format *staggered grid* — yaitu grid yang posisinya berselang-seling agar tampilan lebih dinamis. 
>
> Saat kursor diarahkan ke salah satu kartu, foto akan sedikit membesar dan muncul *overlay* berisi nama acara, peran saya, serta deskripsi singkatnya. Data ini semuanya diambil secara dinamis dari database."

### 3:10 – 3:40 | Contact Form
**📺 Layar:** Scroll ke Contact. Isi form: kosongkan nama, isi email dan pesan, lalu klik Send Message. Tunggu snackbar muncul.

**🎙️ Narasi:**
> "Section terakhir adalah **Contact Form**. Pengunjung bisa mengirim pesan kepada saya melalui form ini. Form ini dilengkapi validasi di sisi *client* menggunakan JavaScript, dan pengiriman data dilakukan secara **AJAX** tanpa perlu *reload* halaman. 
>
> Saya akan coba mengirim pesan dengan sengaja mengosongkan kolom nama. Klik *Send Message*... dan pesannya berhasil terkirim. Nanti kita akan lihat, berkat **Trigger** di database, nama pengirim yang kosong ini akan otomatis diisi menjadi 'Guest'."

---

## ⏱️ 3:40 – 4:40 | Demo Website — Admin Panel

### 3:40 – 3:55 | Login
**📺 Layar:** Klik tombol "Admin" di navbar. Tampilkan halaman login. Ketik username: `admin`, password: `admin123`. Klik Authenticate.

**🎙️ Narasi:**
> "Sekarang saya akan masuk ke halaman Admin. Saya login menggunakan akun yang sudah tersimpan di database — passwordnya tersimpan dalam bentuk *hash bcrypt*, bukan *plain text*, demi keamanan."

### 3:55 – 4:15 | Dashboard
**📺 Layar:** Setelah login, dashboard muncul. **Highlight:** angka total events dan total messages, serta tabel Recent Messages.

**🎙️ Narasi:**
> "Ini adalah **Dashboard**. Di sini saya bisa melihat statistik: jumlah event yang terkelola dan total pesan masuk. Angka-angka ini didapatkan dari pemanggilan **Function** MySQL yang sudah saya buat, yaitu `hitung_total_event()` dan `hitung_total_pesan()`.
>
> Di bawahnya terdapat tabel *Recent Messages* yang datanya diambil dari **View** bernama `v_pesan_terbaru`. Bisa kita lihat pesan yang tadi saya kirim sudah muncul, dan perhatikan kolom nama pengirimnya otomatis terisi **'Guest'** — ini adalah hasil kerja Trigger yang tadi saya sebutkan."

### 4:15 – 4:40 | Fitur CRUD Admin
**📺 Layar:** Klik menu "Manage Skills" → tunjukkan tabel. Klik "Featured Events" → tunjukkan tabel & tombol aksi. Klik "Hero Settings" → tunjukkan form pengaturan. Jangan terlalu lama, cukup tunjukkan sekilas masing-masing 5–8 detik.

**🎙️ Narasi:**
> "Admin panel ini juga menyediakan fitur CRUD lengkap. Saya bisa menambah, mengedit, dan menghapus data skill, data event, serta mengubah konten hero section termasuk upload foto profil baru. Semua perubahan ini akan langsung terlihat di halaman utama website."

---

## ⏱️ 4:40 – 5:20 | Penjelasan Kode — Struktur Database (`db.sql`)

**📺 Layar:** Pindah ke Code Editor. Buka file `db.sql`. Scroll pelan sambil menjelaskan.

> [!IMPORTANT]
> **Highlight baris-baris ini saat berbicara:**
> - Baris 8–17: `CREATE TABLE users` — tabel admin
> - Baris 37–42: `CREATE TABLE skills`
> - Baris 50–58: `CREATE TABLE events`
> - Baris 65–72: `CREATE TABLE event_galleries` — **highlight baris 71: `FOREIGN KEY ... ON DELETE CASCADE`**

**🎙️ Narasi:**
> "Sekarang saya akan menunjukkan kode-kode penting dari proyek ini. File pertama adalah `db.sql` yang berisi keseluruhan skema database.
>
> Database ini terdiri dari 6 tabel utama. Yang ingin saya *highlight* adalah tabel `event_galleries` yang memiliki **Foreign Key** ke tabel `events` dengan aturan **ON DELETE CASCADE** — artinya jika sebuah event dihapus, seluruh foto galeri milik event tersebut juga otomatis terhapus dari database."

---

## ⏱️ 5:20 – 6:20 | Penjelasan Kode — Trigger, View, Function (`db.sql`)

**📺 Layar:** Masih di file `db.sql`. Scroll ke bagian bawah.

> [!IMPORTANT]
> **Highlight baris-baris ini:**
> - **Trigger** (~baris 91–110): `CREATE TRIGGER before_contact_insert` — highlight blok `IF NEW.submitted_at IS NULL` dan `IF NEW.sender_name IS NULL`
> - **View 1** (~baris 114–128): `CREATE VIEW v_detail_portofolio_event` — highlight kata `LEFT JOIN`
> - **View 2** (~baris 130–136): `CREATE VIEW v_pesan_terbaru` — highlight `ORDER BY ... DESC LIMIT 5`
> - **Function 1** (~baris 140–153): `CREATE FUNCTION hitung_total_event` — highlight `SELECT COUNT(*)`
> - **Function 2** (~baris 155–163): `CREATE FUNCTION hitung_total_pesan`

**🎙️ Narasi:**
> "Selanjutnya di file yang sama terdapat implementasi **Trigger, View, dan Function**.
>
> **Trigger** `before_contact_insert` berjalan sebelum data pesan masuk ke tabel. Tugasnya ada dua: pertama, jika waktu kirim kosong, otomatis diisi waktu server saat ini. Kedua, jika nama pengirim kosong, otomatis diganti menjadi 'Guest'. Tadi saat demo, kita sudah membuktikan trigger ini bekerja.
>
> Kemudian ada dua **View**: `v_detail_portofolio_event` yang menggabungkan tabel event dengan galerinya menggunakan LEFT JOIN, dan `v_pesan_terbaru` yang menampilkan 5 pesan terakhir.
>
> Lalu ada dua **Function**: `hitung_total_event` dan `hitung_total_pesan` yang mengembalikan angka total menggunakan COUNT. Kedua fungsi ini yang tadi kita lihat hasilnya di dashboard admin."

---

## ⏱️ 6:20 – 7:00 | Penjelasan Kode — Halaman Utama (`index.php`)

**📺 Layar:** Buka file `index.php`.

> [!IMPORTANT]
> **Highlight baris-baris ini:**
> - Baris 5–6: `$pdo->query("SELECT * FROM settings")` — koneksi ke tabel settings
> - Baris 22: `SELECT * FROM v_detail_portofolio_event` — **penggunaan View di PHP**
> - Baris 83: Penggunaan `htmlspecialchars()` untuk keamanan XSS
> - Baris 116–136: Looping portfolio dari database

**🎙️ Narasi:**
> "File `index.php` adalah halaman utama website. Di bagian atas, data profil hero diambil dari tabel `settings`. Kemudian perhatikan di baris 22, saya memanggil **View** `v_detail_portofolio_event` yang sudah kita bahas tadi — jadi di PHP saya tidak perlu menulis query JOIN yang panjang.
>
> Semua output ke HTML menggunakan fungsi `htmlspecialchars` untuk mencegah serangan **XSS** atau *Cross-Site Scripting*. Lalu di bagian portfolio, data di-*loop* menggunakan `foreach` dari array yang sudah dikelompokkan berdasarkan event ID."

---

## ⏱️ 7:00 – 7:30 | Penjelasan Kode — API Kontak (`api/contact.php`) & Dashboard (`dashboard.php`)

**📺 Layar:** Buka file `api/contact.php`. Lalu buka file `dashboard.php`.

> [!IMPORTANT]
> **Highlight di `api/contact.php`:**
> - Baris 26: `INSERT INTO contact_messages (sender_name, sender_email, message)` — **tidak memasukkan `submitted_at`** agar trigger bisa bekerja
> - Baris 28: `$final_name = ($name === '') ? null : $name;` — set null agar trigger mengisi 'Guest'
>
> **Highlight di `dashboard.php`:**
> - Baris 5: `SELECT hitung_total_event() AS total_events, hitung_total_pesan() AS total_messages` — **penggunaan Function di PHP**
> - Baris 9: `SELECT * FROM v_pesan_terbaru` — **penggunaan View di PHP**

**🎙️ Narasi:**
> "File `api/contact.php` adalah endpoint yang menerima data dari form kontak. Perhatikan bahwa saat meng-*insert* data, saya sengaja **tidak memasukkan kolom `submitted_at`** dan meng-*set* nama menjadi `null` jika kosong. Ini agar **Trigger** di database yang kita buat tadi bisa mengisi data tersebut secara otomatis.
>
> Kemudian di file `dashboard.php`, bisa kita lihat bahwa statistik total event dan total pesan diambil dengan memanggil **Function** `hitung_total_event()` dan `hitung_total_pesan()`, dan tabel pesan terbaru diambil dari **View** `v_pesan_terbaru`. Jadi View dan Function yang kita buat benar-benar terpakai nyata di dalam aplikasi."

---

## ⏱️ 7:30 – 8:00 | Penutup

**📺 Layar:** Kembali ke browser, tampilkan halaman utama website.

**🎙️ Narasi:**
> "Demikian presentasi proyek UAS saya. Untuk merangkum, website portofolio ini dibangun menggunakan PHP dan MySQL, dengan implementasi lengkap meliputi:
> - 6 tabel database dengan relasi Foreign Key
> - 1 Trigger untuk validasi data otomatis
> - 2 View untuk menyederhanakan query
> - 2 Function untuk kalkulasi statistik
> - 3 Query kompleks menggunakan JOIN, Subquery, dan GROUP BY
> - Serta fitur CRUD lengkap pada admin panel
>
> Sekian dari saya. Terima kasih atas perhatiannya. Wassalamualaikum warahmatullahi wabarakatuh."

---

## 📋 Checklist Sebelum Rekam

- [ ] Laragon/Apache + MySQL sudah running
- [ ] Database `uas_portofolio` sudah ter-import dan berisi data
- [ ] Website bisa diakses di `localhost/UAS_Portofolio`
- [ ] Login admin berfungsi (username: `admin`, password: `admin123`)
- [ ] Code editor sudah membuka 4 file: `index.php`, `db.sql`, `api/contact.php`, `dashboard.php`
- [ ] Microphone sudah di-test, tidak ada noise
- [ ] Screen recorder sudah siap (OBS / Windows Game Bar)
- [ ] Resolusi layar disarankan **1920×1080** agar teks terbaca jelas

> [!TIP]
> **Pro tip:** Lakukan 1× gladi bersih tanpa rekam, sambil stopwatch. Jika terlalu cepat, tambahkan jeda 2-3 detik saat transisi antar section. Jika terlalu lama, percepat bagian demo admin (cukup 5 detik per halaman).
