# ⭐ Star Jasmani

Sistem informasi manajemen kebugaran jasmani berbasis web untuk tracking
performa fisik atlet menggunakan metode POLRI Samapta.

Built with **Laravel 13** · **PHP 8.3** · **MySQL** · **Tailwind CSS**

---

## 📋 Fitur Utama

- Dashboard analitik nilai samapta & BMI
- Manajemen atlet, batch, dan institusi
- Penilaian Samapta (lari, push-up, sit-up, pull-up, shuttle, renang)
- Laporan PDF per atlet / per batch
- Import data atlet via Excel
- Sistem role: Admin & Member
- Rate limiting login (maks. 5 percobaan)

---

## 🚀 Cara Deploy (Production)

### Kebutuhan Server
- PHP >= 8.3 (ext: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, tokenizer, xml, zip)
- MySQL >= 8.0
- Composer >= 2.x
- Node.js >= 20.x & npm
- Web server: Nginx atau Apache

### Langkah Deploy

```bash
# 1. Clone repository
git clone https://github.com/username/star-jasmani.git
cd star-jasmani

# 2. Install PHP dependencies (tanpa package dev)
composer install --no-dev --optimize-autoloader

# 3. Salin dan isi konfigurasi environment
cp .env.example .env
nano .env   # isi APP_KEY, DB_*, ADMIN_EMAIL, ADMIN_PASSWORD

# 4. Generate app key
php artisan key:generate

# 5. Jalankan migrasi database
php artisan migrate --force

# 6. Jalankan seeder (hanya pertama kali)
php artisan db:seed --force

# 7. Build asset frontend
npm ci
npm run build

# 8. Link storage publik
php artisan storage:link

# 9. Cache untuk performa production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 10. Set permission (Linux/VPS)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Variabel .env Wajib Diisi

| Variable | Keterangan |
|---|---|
| `APP_KEY` | Generate via `php artisan key:generate` |
| `APP_URL` | URL production, contoh: `https://starjasmani.com` |
| `DB_DATABASE` | Nama database MySQL |
| `DB_USERNAME` | Username database |
| `DB_PASSWORD` | Password database (gunakan password kuat) |
| `ADMIN_EMAIL` | Email login admin pertama |
| `ADMIN_PASSWORD` | Password admin pertama (min. 12 karakter) |

---

## 💻 Cara Jalankan Lokal (Development)

```bash
composer install
cp .env.example .env
# Edit .env: APP_ENV=local, APP_DEBUG=true, DB_* sesuai lokal

php artisan key:generate
php artisan migrate --seed
npm install
composer run dev   # jalankan semua sekaligus
```

Akses: `http://localhost:8000`

---

## 🔐 Akun Default (Development Only)

| Role | Email | Password |
|---|---|---|
| Admin | admin@starjasmani.com | Sesuai `ADMIN_PASSWORD` di `.env` |

> ⚠️ Segera ganti password setelah login pertama kali di production!

---

## 🗄️ Struktur Database

Lihat folder `database/migrations/` untuk skema lengkap.

Tabel utama:
- `users` — akun admin & member
- `athletes` — data atlet
- `samapta_scores` — hasil tes samapta
- `bmi_records` — rekam BMI
- `batches` — batch/periode pelatihan
- `institutions` — institusi/satuan

---

## 📁 Struktur Folder Penting

```
app/Http/Controllers/Admin/   — Controller fitur admin
app/Http/Controllers/Member/  — Controller fitur member
app/Models/                   — Model Eloquent
database/migrations/          — Skema database
database/seeders/             — Data awal
resources/views/              — Template Blade
routes/web.php                — Semua route
```

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Blade, Tailwind CSS, Chart.js |
| Database | MySQL 8 |
| PDF Export | barryvdh/laravel-dompdf |
| Excel Import | maatwebsite/excel |
| Auth | Laravel built-in + custom RoleMiddleware |

---

## 👨‍💻 Developer

Dikembangkan sebagai project akademik — Universitas Budi Luhur
