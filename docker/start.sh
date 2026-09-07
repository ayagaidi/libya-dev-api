#!/bin/sh
set -eu

PORT="${PORT:-8080}"

# Normalize Apache MPMs at runtime. Railway may reuse or alter the final
# container environment, so do not rely only on build-time module state.
rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf
a2enmod mpm_prefork >/dev/null

sed -ri "s/^Listen [0-9]+$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

if [ -z "${APP_KEY:-}" ]; then
  echo "APP_KEY is required. Generate one with: php artisan key:generate --show" >&2
  exit 1
fi

php artisan config:cache
php artisan route:cache

apache2ctl configtest
exec apache2-foreground
