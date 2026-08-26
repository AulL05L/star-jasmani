#!/bin/bash
# ─────────────────────────────────────────────
#  STAR JASMANI — Deploy Script
#  Jalankan di server sebagai pengguna login (yang punya sudo):
#
#      bash deploy.sh
#
#  JANGAN dijalankan sebagai root, dan jangan pula sebagai www-data:
#  www-data tidak punya sudo, sehingga reload Nginx di langkah terakhir gagal.
# ─────────────────────────────────────────────

set -e  # stop kalau ada error

PROJECT_DIR="/var/www/star-jasmani"
PHP="php8.3"

# Seluruh isi PROJECT_DIR dimiliki www-data, bukan pengguna login. Karena itu
# setiap perintah yang MENYENTUH BERKAS dijalankan sebagai www-data.
#
# Tanpa ini ada dua kegagalan, dan keduanya pernah terjadi:
#   - dijalankan sebagai pengguna biasa, `git pull` berhenti dengan
#     "cannot open '.git/FETCH_HEAD': Permission denied"
#   - dijalankan sebagai root, composer dan npm meninggalkan berkas milik root
#     yang kemudian tidak dapat ditulis php-fpm, dan gejalanya muncul belakangan
#     di tempat lain: sesi atau log aplikasi yang tiba-tiba gagal ditulis.
JADI_WWW="sudo -u www-data"

# npm menulis cache ke $HOME, dan HOME milik www-data belum tentu dapat ditulis.
# Diarahkan ke dalam storage/ yang sudah pasti milik www-data dan sudah diabaikan git.
NPM_ENV="env npm_config_cache=$PROJECT_DIR/storage/.npm"

echo ""
echo "======================================"
echo "  STAR JASMANI — DEPLOY"
echo "  $(date '+%d %b %Y %H:%M')"
echo "======================================"
echo ""

if [ "$(id -u)" = "0" ]; then
    echo "GAGAL: jangan jalankan sebagai root. Jalankan sebagai pengguna login biasa;"
    echo "       skrip ini sudah memakai sudo di tempat yang memerlukannya."
    exit 1
fi

[ -d "$PROJECT_DIR" ] || { echo "GAGAL: $PROJECT_DIR tidak ada."; exit 1; }

cd "$PROJECT_DIR"

echo "[ 1/7 ] Git pull..."
$JADI_WWW git pull origin main

# composer install ikut menyusun ulang peta autoload. Itu WAJIB sesudah pull yang
# membawa kelas PHP baru: peta dari --optimize-autoloader bersifat statis, dan
# kelas yang belum terdaftar membuat SELURUH situs balas HTTP 500 — bukan hanya
# halaman yang memakai kelas barunya.
echo "[ 2/7 ] Composer install..."
$JADI_WWW composer install --no-dev --optimize-autoloader --no-interaction

echo "[ 3/7 ] NPM build (CSS + JS)..."
$JADI_WWW $NPM_ENV npm ci
$JADI_WWW $NPM_ENV npm run build

echo "[ 4/7 ] Migrate database..."
$JADI_WWW $PHP artisan migrate --force

echo "[ 5/7 ] Clear & rebuild cache..."
$JADI_WWW $PHP artisan config:clear
$JADI_WWW $PHP artisan route:clear
$JADI_WWW $PHP artisan view:clear
$JADI_WWW $PHP artisan config:cache
$JADI_WWW $PHP artisan route:cache
$JADI_WWW $PHP artisan view:cache
$JADI_WWW $PHP artisan optimize

# chown butuh root, jadi yang ini lewat sudo biasa, bukan sudo -u www-data.
echo "[ 6/7 ] Fix permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# SETGID pada direktori, dan ini bukan pemanis.
#
# Tanpa bit setgid, berkas yang LAHIR SESUDAH deploy — log hari berikutnya, cache
# view, berkas sesi — mengambil grup utama pengguna yang membuatnya, bukan grup
# direktori induknya. Jadi chmod di atas hanya membetulkan yang sudah ada, dan
# besok paginya masalahnya kembali sendiri: php-fpm tidak dapat menulis berkas
# yang baru dibuat cron, atau sebaliknya, dan gejalanya muncul jauh dari sebabnya.
#
# Dengan setgid, tiap berkas baru mewarisi grup www-data selamanya.
sudo find storage bootstrap/cache -type d -exec chmod 2775 {} \;

echo "[ 7/7 ] Reload Nginx..."
sudo systemctl reload nginx

echo ""
echo "======================================"
echo "  ✅ DEPLOY SELESAI"
echo "======================================"
echo ""
echo "Periksa sesudah ini:"
echo "  - buka https://starjasmani.id"
echo "  - $JADI_WWW $PHP artisan migrate:status | tail -5"
echo ""
