# Web Profile SD

Website profil sekolah dasar berbasis **Laravel 12** + **Vue 3** + **Inertia.js** dengan panel admin untuk pengelolaan konten secara mandiri.

## Fitur Utama

### 🌐 Halaman Publik
| Modul | Deskripsi |
|-------|-----------|
| **Beranda** | Hero section, statistik sekolah, berita terbaru, galeri foto, link YouTube |
| **Profil** | Visi, misi, sejarah, dan informasi sekolah |
| **Guru & Staf** | Daftar guru dan tenaga pendidik dengan foto dan informasi |
| **Berita** | List berita dengan kategori + halaman detail |
| **Galeri** | Foto-foto kegiatan sekolah dengan filter kategori |
| **PPDB** | Informasi dan formulir pendaftaran peserta didik baru online |
| **Unduhan** | Daftar dokumen yang dapat diunduh oleh pengunjung |
| **Kontak** | Formulir kontak + informasi lokasi sekolah |

### 🔐 Autentikasi & Otorisasi
- Login/Logout menggunakan **Laravel Breeze**
- Role-based access control via **spatie/laravel-permission**
- Role: `super-admin`, `admin`

### 📊 Admin Panel
| Modul | Fitur |
|-------|-------|
| **Dashboard** | Statistik ringkasan (berita, guru, galeri, pendaftaran PPDB) |
| **Berita** | CRUD berita dengan gambar dan kategori |
| **Galeri** | CRUD foto galeri dengan kategori |
| **Guru & Staf** | CRUD data guru dan tenaga pendidik |
| **Halaman** | CRUD halaman statis (profil, dll) dengan rich text editor |
| **PPDB** | Kelola data pendaftar, ubah status (pending/approved/rejected) |
| **Unduhan** | CRUD file unduhan |
| **Pengguna** | Kelola pengguna dan assign role |
| **Pengaturan** | Konfigurasi data sekolah, kalender akademik, sosial media |
| **Kalender Akademik** | CRUD jadwal dan kegiatan akademik |

### 📧 Email Notifikasi
- **NewContactMessageMail** — Notifikasi ketika ada pengiriman formulir kontak
- **NewPpdbRegistrationMail** — Notifikasi pendaftaran PPDB baru
- **PpdbStatusChangedMail** — Notifikasi perubahan status PPDB ke pendaftar

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

## Instalasi

```bash
# 1. Clone repository
git clone <repository-url>
cd web-profile-sd

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=web_profile_sd
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# 5. Migrasi dan seeder
php artisan migrate --seed

# 6. Build assets
npm run build

# 7. Jalankan aplikasi
php artisan serve
```

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