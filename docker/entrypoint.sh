#!/bin/sh
# =====================================================================
# docker/entrypoint.sh - persiapan Laravel saat container "app" dimulai
# Dijalankan setiap container start, lalu menyerahkan proses ke Apache.
# =====================================================================
set -e

cd /var/www/html

# Menjalankan artisan sebagai www-data agar file di storage/ dan
# bootstrap/cache tidak menjadi milik root (menghindari permission denied).
artisan() {
    runuser -u www-data -- php artisan "$@"
}

# ---------------------------------------------------------------------
# 1. Pastikan APP_KEY sudah diisi
# ---------------------------------------------------------------------
if [ -z "${APP_KEY:-}" ]; then
    echo "[entrypoint] ERROR: APP_KEY kosong. Isi APP_KEY pada file .env." >&2
    echo "[entrypoint] Buat key: docker run --rm php:8.4-cli php -r 'echo \"base64:\".base64_encode(random_bytes(32)).PHP_EOL;'" >&2
    exit 1
fi

# ---------------------------------------------------------------------
# 2. Siapkan direktori penyimpanan dan hak akses
#    (volume app_storage dibuat Docker sebagai root, jadi perlu chown)
# ---------------------------------------------------------------------
mkdir -p storage/app/public \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# ---------------------------------------------------------------------
# 3. Tunggu MySQL menerima koneksi
#    (depends_on + healthcheck sudah menunggu, ini lapisan pengaman kedua)
# ---------------------------------------------------------------------
DB_WAIT_RETRIES="${DB_WAIT_RETRIES:-30}"
DB_WAIT_INTERVAL="${DB_WAIT_INTERVAL:-2}"
attempt=1

echo "[entrypoint] Menunggu database di ${DB_HOST:-database}:${DB_PORT:-3306} ..."
until php -r '
    try {
        new PDO(
            "mysql:host=" . (getenv("DB_HOST") ?: "database") . ";port=" . (getenv("DB_PORT") ?: "3306"),
            getenv("DB_USERNAME"),
            getenv("DB_PASSWORD"),
            [PDO::ATTR_TIMEOUT => 3]
        );
        exit(0);
    } catch (Throwable $e) {
        fwrite(STDERR, $e->getMessage() . PHP_EOL);
        exit(1);
    }
' 2>/dev/null; do
    if [ "$attempt" -ge "$DB_WAIT_RETRIES" ]; then
        echo "[entrypoint] ERROR: database tidak siap setelah ${DB_WAIT_RETRIES} percobaan." >&2
        exit 1
    fi
    attempt=$((attempt + 1))
    sleep "$DB_WAIT_INTERVAL"
done
echo "[entrypoint] Database siap."

# ---------------------------------------------------------------------
# 4. Daftarkan package Laravel
#    (composer install di Dockerfile memakai --no-scripts)
# ---------------------------------------------------------------------
artisan package:discover --ansi

# ---------------------------------------------------------------------
# 5. Migrasi dan seeder (dikendalikan lewat .env)
#    Seeder dibuat idempoten (updateOrCreate), jadi aman dijalankan ulang.
# ---------------------------------------------------------------------
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "[entrypoint] Menjalankan migrasi ..."
    artisan migrate --force
fi

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "[entrypoint] Menjalankan seeder ${SEEDER_CLASS:-AdminSeeder} ..."
    artisan db:seed --class="${SEEDER_CLASS:-AdminSeeder}" --force
fi

# ---------------------------------------------------------------------
# 6. Symbolic link public/storage dan cache untuk produksi
#    Catatan: route:cache sengaja tidak dipakai karena routes/web.php
#    memuat route berbentuk Closure yang tidak bisa di-cache.
# ---------------------------------------------------------------------
artisan storage:link --force
artisan config:cache
artisan view:cache

# ---------------------------------------------------------------------
# 7. Serahkan proses utama ke perintah CMD (apache2-foreground)
# ---------------------------------------------------------------------
echo "[entrypoint] Aplikasi siap. Menjalankan: $*"
exec "$@"
