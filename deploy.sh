#!/bin/bash
# ─────────────────────────────────────────────
#  STAR JASMANI — Deploy Script
#  Jalankan di server: bash deploy.sh
# ─────────────────────────────────────────────

set -e  # stop kalau ada error

PROJECT_DIR="/var/www/starjasmani.id"
PHP="php8.3"

echo ""
echo "======================================"
echo "  STAR JASMANI — DEPLOY"
echo "  $(date '+%d %b %Y %H:%M')"
echo "======================================"
echo ""

cd $PROJECT_DIR

echo "[ 1/7 ] Git pull..."
git pull origin main

echo "[ 2/7 ] Composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "[ 3/7 ] NPM build (CSS + JS)..."
npm ci
npm run build

echo "[ 4/7 ] Migrate database..."
$PHP artisan migrate --force

echo "[ 5/7 ] Clear & rebuild cache..."
$PHP artisan config:clear
$PHP artisan route:clear
$PHP artisan view:clear
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
$PHP artisan optimize

echo "[ 6/7 ] Fix permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "[ 7/7 ] Reload Nginx..."
sudo systemctl reload nginx

echo ""
echo "======================================"
echo "  ✅ DEPLOY SELESAI"
echo "======================================"
echo ""
