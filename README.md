# Web Profile SD

Website profil sekolah dasar berbasis **Laravel 12** + **Vue 3** + **Inertia.js** dengan panel admin untuk pengelolaan konten secara mandiri.

## ✨ Pembaruan Terbaru
- **Sistem Email PPDB**: Orang tua kini otomatis menerima email notifikasi (lengkap dengan Nomor Pendaftaran) saat berhasil submit formulir pendaftaran PPDB.
- **Bug Fix**: Penyesuaian format template email menjadi Markdown (`<x-mail::message>`) pada seluruh notifikasi aplikasi.

## Fitur Utama

### 🌐 Halaman Publik
| Modul | Deskripsi |
|-------|-----------|
| **Beranda** | Hero section, sambutan, informasi ringkas sekolah, dan berita terbaru |
| **Profil Sekolah** | Menampilkan halaman statis seperti Visi Misi, Sejarah, dsb |
| **Guru & Staf** | Direktori tenaga pendidik dan kependidikan beserta posisinya |
| **Ekstrakurikuler** | Daftar kegiatan ekstrakurikuler yang tersedia di sekolah |
| **Berita / Artikel** | Daftar publikasi berita sekolah lengkap dengan halaman detail |
| **Kalender Akademik**| Menampilkan jadwal kegiatan akademik sekolah |
| **Fasilitas** | (Jika diaktifkan) Menampilkan daftar fasilitas pendukung sekolah |
| **PPDB Online** | Formulir pendaftaran siswa baru, cek status pendaftaran, dan cetak bukti pendaftaran |
| **Unduhan** | Repositori file/dokumen publik yang dapat diunduh (misal: brosur, tata tertib) |
| **Kontak** | Formulir pengiriman pesan ke sekolah beserta detail alamat/kontak |

*(Catatan: Modul Galeri saat ini dinonaktifkan secara default pada route)*

### 🔐 Autentikasi & Otorisasi
- Login/Logout menggunakan **Laravel Breeze**
- Role-based access control (RBAC) via **spatie/laravel-permission**
- Memiliki 2 tingkatan Role: `super-admin` dan `operator`

### 📊 Admin Panel (CMS)
Berikut adalah fitur pengelolaan yang tersedia di dashboard admin:

| Modul | Hak Akses | Deskripsi |
|-------|-----------|-----------|
| **Dashboard** | Semua | Statistik ringkasan data (jumlah siswa pendaftar, berita, dll) |
| **Berita** | Semua | Kelola publikasi artikel/berita (CRUD) |
| **Guru & Staf** | Semua | Kelola direktori tenaga pendidik (CRUD) |
| **Ekstrakurikuler**| Semua | Kelola data ekstrakurikuler sekolah (CRUD) |
| **Kalender** | Semua | Kelola jadwal akademik tahunan sekolah (CRUD) |
| **Fasilitas** | Semua | Kelola data fasilitas fisik sekolah (CRUD) |
| **Master Data** | Semua | Kelola data referensi/kategori pendukung aplikasi |
| **Unduhan** | Semua | Unggah dan kelola file dokumen publik |
| **Halaman** | Semua | Kelola teks/konten halaman statis (Profil, Visi Misi) menggunakan Rich Text Editor |
| **Pesan Kontak** | Semua | Kotak masuk untuk membaca pesan dari pengunjung web |
| **Data PPDB** | Semua | Kelola pendaftar PPDB, ubah status (pending/verified/accepted/rejected), cetak/eksport data |
| **Setting PPDB** | Semua | Buka/tutup jalur pendaftaran, atur kuota, dan pengaturan PPDB lainnya |
| **Pengaturan Web**| Semua | Konfigurasi identitas sekolah, logo, kontak, sosial media, dsb |
| **Pengguna** | **Super Admin**| Tambah, edit, hapus akun admin/operator serta penugasan *role* |

### 📧 Email Notifikasi
- **NewContactMessageMail** — Notifikasi ketika ada pengiriman formulir kontak (ke Admin)
- **NewPpdbRegistrationMail** — Notifikasi pendaftaran PPDB baru (ke Admin)
- **PpdbRegistrationSuccessMail** — Notifikasi pendaftaran berhasil dikirim (ke Orang Tua)
- **PpdbStatusChangedMail** — Notifikasi perubahan status PPDB (ke Orang Tua)

### 🎨 Frontend
- **Tailwind CSS** untuk styling
- **Vue 3** + **Inertia.js** untuk SPA experience
- **Vue Quill** rich text editor di halaman admin
- **AOS** untuk animasi scroll
- **Alpine.js** untuk interaktivitas ringan
- **Flowbite** components
- Responsive design untuk mobile & desktop

## Tech Stack

- **Backend:** Laravel 12.x
- **Frontend:** Vue 3, Inertia.js, Tailwind CSS
- **Database:** PostgreSQL
- **Auth:** Laravel Breeze + spatie/laravel-permission
- **Build Tool:** Vite
- **Editor:** @vueup/vue-quill

## 🚀 Panduan Instalasi (Step-by-Step)

Untuk menjalankan proyek ini di komputer lokal Anda, ikuti langkah-langkah di bawah ini:

### Persyaratan Sistem (Prerequisites)
Pastikan komputer Anda sudah terinstal:
- **PHP** (minimal versi 8.3)
- **Composer** (untuk dependensi PHP)
- **Node.js & npm** (untuk dependensi frontend Vue/Tailwind)
- **PostgreSQL** (sebagai database utama)
- **Git** (opsional, untuk clone repository)

### Langkah Instalasi

**1. Clone atau Download Repository**
```bash
git clone <repository-url>
cd web-profile-sd
```
*(Jika tidak menggunakan Git, Anda bisa mengunduh file ZIP dan mengekstraknya, lalu buka terminal/CMD di dalam folder tersebut).*

**2. Install Dependensi (Backend & Frontend)**
```bash
composer install
npm install
```

**3. Konfigurasi Environment (.env)**
Salin file konfigurasi bawaan dan hasilkan application key baru:
```bash
cp .env.example .env
php artisan key:generate
```

**4. Konfigurasi Database & Email**
Buka file `.env` di text editor pilihan Anda (misal: VS Code), lalu sesuaikan pengaturan berikut:

- **Database** (Sesuaikan dengan kredensial PostgreSQL lokal Anda):
  ```env
  DB_CONNECTION=pgsql
  DB_HOST=127.0.0.1
  DB_PORT=5432
  DB_DATABASE=sd_profile
  DB_USERNAME=postgres
  DB_PASSWORD=password_anda
  ```
- **Email** (Penting jika Anda ingin menguji notifikasi PPDB & Kontak):
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp-relay.brevo.com
  MAIL_PORT=587
  MAIL_USERNAME=username_smtp_anda
  MAIL_PASSWORD=password_smtp_anda
  MAIL_FROM_ADDRESS="admin@domainsekolah.com"
  ```
*(Tips: Jika Anda hanya ingin menguji secara lokal tanpa mengirim email sungguhan, ubah `MAIL_MAILER=log`)*

**5. Migrasi Database & Seeder**
Buat struktur tabel di database Anda dan isi dengan data awal (termasuk akun login default):
```bash
php artisan migrate --seed
```

**6. Buat Symbolic Link Storage (Sangat Penting)**
Agar file/foto yang Anda unggah dari panel admin (berita, galeri, dokumen PPDB) dapat dibaca oleh sistem:
```bash
php artisan storage:link
```

**7. Build Assets (Frontend)**
Kompilasi file Vue.js dan Tailwind CSS:
```bash
npm run build
```

**8. Jalankan Aplikasi**
Buka 2 jendela terminal, lalu jalankan perintah berikut di masing-masing terminal:

Terminal 1 (Menjalankan server Laravel backend):
```bash
php artisan serve
```

Terminal 2 (Menjalankan Vite server frontend untuk *live reload* selama development):
```bash
npm run dev
```

Aplikasi kini dapat diakses di browser pada alamat: **http://localhost:8000**

---

### 🔑 Akses Login Admin (Default)
Setelah Anda menjalankan perintah `php artisan migrate --seed`, Anda dapat login ke halaman admin menggunakan akun bawaan:
- **Email:** `admin@admin.com` *(Silakan sesuaikan jika Anda mengubahnya di DatabaseSeeder)*
- **Password:** `password`

## Struktur Database

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Akun pengguna (admin, super-admin) |
| `roles` / `permissions` | Role dan permission (spatie) |
| `teachers` | Data guru dan staf |
| `news` | Berita sekolah |
| `galleries` | Foto galeri |
| `pages` | Halaman statis |
| `downloads` | File unduhan |
| `school_settings` | Pengaturan profil sekolah |
| `academic_calendars` | Kalender akademik |
| `contact_messages` | Pesan dari formulir kontak |
| `youtube` | Link video YouTube |
| `ppdb_settings` | Konfigurasi PPDB |
| `ppdb_registrations` | Data pendaftaran PPDB |

## Security Notes

- Password di-hash menggunakan bcrypt
- Input validation di semua endpoint
- Role-based middleware pada route admin
- CSRF protection aktif di semua form

## License

Proprietary