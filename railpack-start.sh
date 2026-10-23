#!/bin/bash
set -e

# Railpack uses FrankenPHP and its own /start-container.sh for the web server.
# Keep this app's SQLite volume, scheduler, and queue worker setup alongside it.

DB_PATH="${DB_DATABASE:-$PWD/database/database.sqlite}"
mkdir -p "$(dirname "$DB_PATH")"
touch "$DB_PATH"

if [ -n "${RAILWAY_VOLUME_MOUNT_PATH:-}" ] && [ -d "$RAILWAY_VOLUME_MOUNT_PATH" ]; then
    chmod -R 777 "$RAILWAY_VOLUME_MOUNT_PATH"
fi

# Clear build-time caches before the workers use the runtime environment.
# Railpack's /start-container.sh runs storage:link, optimize:clear and
# optimize again for the web server.
php artisan optimize:clear
php artisan migrate --force

chmod +x run-cron.sh run-worker.sh
./run-cron.sh &
./run-worker.sh &

# Migrations have already run above; Railpack handles optimisation and the
# web server in /start-container.sh.
export RAILPACK_SKIP_MIGRATIONS=true
exec bash /start-container.sh
