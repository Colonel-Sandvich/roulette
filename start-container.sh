#!/bin/bash
set -e

# Custom Railpack start script for Larasino. Replaces Railpack's default
# Laravel script so the scheduler and queue worker run alongside FrankenPHP.

DB_PATH="${DB_DATABASE:-$PWD/database/database.sqlite}"
mkdir -p "$(dirname "$DB_PATH")"
touch "$DB_PATH"

if [ -n "${RAILWAY_VOLUME_MOUNT_PATH:-}" ] && [ -d "$RAILWAY_VOLUME_MOUNT_PATH" ]; then
    chmod -R 777 "$RAILWAY_VOLUME_MOUNT_PATH"
fi

# Make sure migrations use the runtime env instead of build-time cached config.
# optimize:clear is deliberately run after migrating: on a fresh volume the
# database cache table does not exist yet.
php artisan config:clear

echo "Running migrations ..."
php artisan migrate --force

php artisan storage:link

echo "Caching Laravel ..."
php artisan optimize:clear
php artisan optimize

chmod +x run-cron.sh run-worker.sh
./run-cron.sh &
./run-worker.sh &

echo "Starting Laravel server ..."
exec docker-php-entrypoint --config /Caddyfile --adapter caddyfile 2>&1
