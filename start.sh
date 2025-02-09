#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x /app/start.sh`

# Exit the script if any command fails
set -e

ls -lah "$RAILWAY_VOLUME_MOUNT_PATH"

chmod -R 664 "$RAILWAY_VOLUME_MOUNT_PATH"
chown -R www-data:www-data "$RAILWAY_VOLUME_MOUNT_PATH"

ls -lah "$RAILWAY_VOLUME_MOUNT_PATH"

if [ ! -f "$DB_DATABASE" ]; then
    echo "Database file does not exist. Creating at: $DB_DATABASE"

    touch "$DB_DATABASE"

    echo "Database file created successfully."
else
    echo "Database file already exists at: $DB_DATABASE"
fi

# Run pending migrations
php artisan migrate --force

# Cache the various components of the Laravel application
php artisan optimize

chmod +x run-cron.sh
chmod +x run-worker.sh

echo "Starting server and workers..."

node /assets/scripts/prestart.mjs /assets/nginx.template.conf /nginx.conf && (
    run-cron.sh &
    run-worker.sh &
    php-fpm -y /assets/php-fpm.conf &
    nginx -c /nginx.conf
)

# # Set up nginx conf and run nginx
# (
#     node /assets/scripts/prestart.mjs /assets/nginx.template.conf /nginx.conf &
#     php-fpm -y /assets/php-fpm.conf &
#     nginx -c /nginx.conf &
#     run-cron.sh &
#     run-worker.sh &
# )
