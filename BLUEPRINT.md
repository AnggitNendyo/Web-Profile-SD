# Blueprint Aplikasi — Website Profil Sekolah Dasar Negeri

## 1. Ringkasan
Website profil & manajemen konten untuk Sekolah Dasar Negeri: halaman publik (profil, berita, galeri, PPDB) + panel admin untuk operator/guru mengelola konten.

## 2. Stack Teknis
| Layer | Teknologi |
|---|---|
| Backend | Laravel 12 |
| Frontend | Vue 3 (Composition API) + Tailwind CSS |
| Integrasi BE-FE | Inertia.js (satu project, tanpa REST API terpisah) |
| Database | PostgreSQL |
| Auth starter | Laravel Breeze (stack: vue) |
| Build tool | Vite |
| Role & permission | spatie/laravel-permission |
| Animasi interaktif | AOS (Animate On Scroll) atau @vueuse/motion |

> Alasan pakai Inertia: memenuhi syarat "BE dan FE dalam satu project" — Vue jadi full frontend, routing & controller tetap di Laravel, tanpa perlu membangun REST API + auth token terpisah.

## 3. Struktur Folder Project

```
Web Profile SD/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Public/
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   ├── NewsController.php
│   │   │   │   ├── GalleryController.php
│   │   │   │   ├── TeacherController.php
│   │   │   │   ├── PpdbController.php
│   │   │   │   ├── DownloadController.php
│   │   │   │   └── ContactController.php
│   │   │   └── Admin/
│   │   │       ├── DashboardController.php
│   │   │       ├── NewsController.php
│   │   │       ├── GalleryController.php
│   │   │       ├── TeacherController.php
│   │   │       ├── PageController.php
│   │   │       ├── PpdbRegistrationController.php
│   │   │       ├── DownloadController.php
│   │   │       ├── UserController.php
│   │   │       └── SettingController.php
│   │   ├── Middleware/
│   │   │   └── EnsureRole.php
│   │   └── Requests/
│   │       ├── StoreNewsRequest.php
│   │       ├── StoreGalleryRequest.php
│   │       ├── StorePpdbRegistrationRequest.php
│   │       └── ...
│   └── Models/
│       ├── User.php
│       ├── Teacher.php
│       ├── News.php
│       ├── Gallery.php
│       ├── PpdbRegistration.php
│       ├── PpdbSetting.php
│       ├── Page.php
│       ├── Download.php
│       ├── SchoolSetting.php
│       └── AcademicCalendar.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── RoleSeeder.php
│       ├── SchoolSettingSeeder.php
│       ├── TeacherSeeder.php
│       ├── NewsSeeder.php
│       └── GallerySeeder.php
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Public/
│   │   │   │   ├── Home.vue
│   │   │   │   ├── Profile.vue
│   │   │   │   ├── News/Index.vue
│   │   │   │   ├── News/Show.vue
│   │   │   │   ├── Gallery.vue
│   │   │   │   ├── Teachers.vue
│   │   │   │   ├── Ppdb.vue
│   │   │   │   ├── Downloads.vue
│   │   │   │   └── Contact.vue
│   │   │   └── Admin/
│   │   │       ├── Dashboard.vue
│   │   │       ├── News/Index.vue
│   │   │       ├── News/Form.vue
│   │   │       ├── Gallery/Index.vue
│   │   │       ├── Teachers/Index.vue
│   │   │       ├── Pages/Edit.vue
│   │   │       ├── Ppdb/Index.vue
│   │   │       ├── Downloads/Index.vue
│   │   │       ├── Users/Index.vue
│   │   │       └── Settings/Edit.vue
│   │   ├── Layouts/
│   │   │   ├── PublicLayout.vue
│   │   │   └── AdminLayout.vue
│   │   ├── Components/
│   │   │   ├── Public/
│   │   │   │   ├── Navbar.vue
│   │   │   │   ├── Footer.vue
│   │   │   │   ├── HeroSection.vue
│   │   │   │   ├── StatCounter.vue
│   │   │   │   ├── NewsCard.vue
│   │   │   │   ├── TeacherCard.vue
│   │   │   │   ├── GalleryGrid.vue
│   │   │   │   └── Lightbox.vue
│   │   │   └── Admin/
│   │   │       ├── Sidebar.vue
│   │   │       ├── Topbar.vue
│   │   │       ├── DataTable.vue
│   │   │       └── ConfirmModal.vue
│   │   └── app.js
│   └── css/
│       └── app.css (Tailwind entry)
├── routes/
│   └── web.php
├── .env.example
└── README.md
```

## 4. Skema Database (PostgreSQL)

### users
| Kolom | Tipe | Ket |
|---|---|---|
| id | bigint PK | |
| name | varchar | |
| email | varchar unique | |
| password | varchar | |
| timestamps | | |

Role dikelola via tabel pivot `model_has_roles` (spatie/laravel-permission) — role: `super-admin`, `operator`, `guru`.

### teachers
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| name | varchar |
| position | varchar |
| subject | varchar nullable |
| nip | varchar nullable |
| photo | varchar nullable |
| sort_order | integer default 0 |
| timestamps | |

### news
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| title | varchar |
| slug | varchar unique |
| content | text |
| thumbnail | varchar nullable |
| category | varchar nullable |
| published_at | timestamp nullable |
| author_id | bigint FK → users |
| timestamps | |

### galleries
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| title | varchar |
| type | enum(foto,video) |
| file_path | varchar |
| category | varchar nullable |
| timestamps | |

### ppdb_registrations
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| nama_siswa | varchar |
| nama_ortu | varchar |
| alamat | text |
| no_hp | varchar |
| dokumen_upload | varchar nullable |
| status | enum(pending,verified,rejected) default pending |
| timestamps | |

### ppdb_settings
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| tahun_ajaran | varchar |
| jalur | varchar |
| kuota | integer |
| jadwal_buka | date |
| jadwal_tutup | date |
| timestamps | |

### pages
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| slug | varchar unique |
| title | varchar |
| content | text |
| updated_by | bigint FK → users nullable |
| timestamps | |

### downloads
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| title | varchar |
| file_path | varchar |
| category | varchar nullable |
| uploaded_by | bigint FK → users nullable |
| timestamps | |

### school_settings
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| key | varchar unique |
| value | text nullable |

Contoh key: `school_name`, `address`, `phone`, `email`, `instagram_url`, `facebook_url`, `youtube_url`, `map_embed_url`.

### academic_calendar
| Kolom | Tipe |
|---|---|
| id | bigint PK |
| title | varchar |
| description | text nullable |
| start_date | date |
| end_date | date nullable |
| category | varchar nullable |
| timestamps | |

## 5. Struktur Routing (routes/web.php)

```php
// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
Route::get('/guru-staf', [TeacherController::class, 'index'])->name('teachers');
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery');
Route::get('/ppdb', [PpdbController::class, 'index'])->name('ppdb');
Route::post('/ppdb', [PpdbController::class, 'store'])->name('ppdb.store');
Route::get('/unduhan', [DownloadController::class, 'index'])->name('downloads');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::post('/kontak', [ContactController::class, 'store'])->name('contact.store');

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:super-admin,operator')->group(function () {
        Route::resource('berita', NewsController::class);
        Route::resource('galeri', GalleryController::class);
        Route::resource('guru', TeacherController::class);
        Route::resource('unduhan', DownloadController::class);
        Route::resource('ppdb', PpdbRegistrationController::class)->only(['index','show','update']);
        Route::get('halaman/{slug}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('halaman/{slug}', [PageController::class, 'update'])->name('pages.update');
        Route::get('pengaturan', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('pengaturan', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('role:super-admin')->group(function () {
        Route::resource('pengguna', UserController::class);
    });
});

require __DIR__.'/auth.php'; // dari Breeze
```

## 6. Modul & Fitur

### A. Publik
1. **Home** — hero, statistik animasi, berita terbaru, CTA PPDB
2. **Profil** — visi misi, sejarah, struktur organisasi, akreditasi
3. **Guru & Staf** — grid card foto + jabatan
4. **Berita** — listing + detail, kategori, pencarian
5. **Galeri** — grid filter kategori + lightbox
6. **PPDB** — info jalur, syarat, jadwal, form pendaftaran online
7. **Kalender Akademik**
8. **Unduhan** — dokumen per kategori
9. **Kontak** — form + peta lokasi

### B. Admin
1. Login (role: super-admin, operator, guru)
2. CRUD Berita (rich text editor, upload gambar)
3. CRUD Galeri (upload multi foto/video)
4. CRUD Guru & Staf
5. CRUD Halaman statis (profil, visi-misi)
6. Manajemen Pendaftar PPDB (verifikasi, export Excel/PDF)
7. Manajemen Dokumen Unduhan
8. Manajemen Pengguna & Role
9. Dashboard ringkasan statistik

## 7. Kebutuhan UI/UX
- Tipografi: Poppins / Inter / Nunito
- Navbar sticky, transparan → solid saat scroll
- Scroll-reveal animation (AOS)
- Hover effect pada card (guru, berita, galeri)
- Counter animation statistik homepage
- Galeri filter kategori + lightbox modal
- Mobile-first, fully responsive
- Dark/light mode (opsional)
- Inertia progress bar dikustomisasi sesuai tema

## 8. Non-Fungsional
- Validasi: Form Request (server) + feedback interaktif Inertia (client)
- Middleware role-based access control
- Optimasi gambar: resize/compress saat upload, lazy loading di frontend
- SEO dasar: meta tag dinamis per halaman via Inertia Head
- Keamanan: CSRF protection (built-in Laravel), rate limiting form publik (kontak, PPDB)
- Aksesibilitas dasar: alt text gambar, kontras warna WCAG AA

## 9. Package Composer & NPM yang Dibutuhkan

**Composer:**
```
laravel/breeze
spatie/laravel-permission
maatwebsite/excel      # export data PPDB
barryvdh/laravel-dompdf # export PDF (opsional)
intervention/image     # resize/compress gambar upload
```

**NPM:**
```
@inertiajs/vue3
vue@^3
tailwindcss
aos                    # scroll-reveal animation
```

## 10. Urutan Implementasi (Milestone)
1. Scaffold Laravel 12 project + set `.env` ke PostgreSQL
2. Install Breeze stack `vue` (Inertia + Vue 3 + Tailwind sudah termasuk)
3. Install spatie/laravel-permission, seed role
4. Buat migration & model sesuai skema di atas
5. Buat routes (public & admin) + middleware role
6. Buat controller & Form Request tiap modul
7. Buat Layouts (PublicLayout, AdminLayout) + Components reusable
8. Buat halaman publik satu per satu (Home dulu, lalu modul lain)
9. Buat panel admin CRUD per modul
10. Tambahkan animasi interaktif (AOS) di halaman publik
11. Buat seeder data dummy untuk testing
12. Migrate + testing end-to-end di browser
