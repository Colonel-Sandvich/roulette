#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x /app/start.sh`

# Exit the script if any command fails
set -e

chmod -R 777 "$RAILWAY_VOLUME_MOUNT_PATH"

ls -lah "$RAILWAY_VOLUME_MOUNT_PATH"

# TODO: remove after reset
rm "$DB_DATABASE"

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

# Set up nginx conf
node /assets/scripts/prestart.mjs /assets/nginx.template.conf /nginx.conf

echo "Starting server and workers..."

(
    run-cron.sh &
    run-worker.sh &
    php-fpm -y /assets/php-fpm.conf &
    nginx -c /nginx.conf
)
