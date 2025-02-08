#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x start.sh`

# Exit the script if any command fails
set -e

if [ ! -f "$DB_DATABASE" ]; then
    echo "Database file does not exist. Creating at: $DB_DATABASE"

    touch "$DB_DATABASE"

    # Ensure proper permissions
    chmod 644 "$DB_DATABASE"

    echo "Database file created successfully."
else
    echo "Database file already exists at: $DB_DATABASE"
fi

# Run pending migrations
php artisan migrate

# Cache the various components of the Laravel application
php artisan optimize

# Set up nginx conf and run nginx
node /assets/scripts/prestart.mjs /assets/nginx.template.conf /nginx.conf

(
    php-fpm -y /assets/php-fpm.conf &
    nginx -c /nginx.conf
)
