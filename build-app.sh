#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x build-app.sh`

# Exit the script if any command fails
set -e

# Build assets using bun
bun run build

echo "$DB_DATABASE";

if [ ! -f "$DB_DATABASE" ]; then
    echo "Database file does not exist. Creating at: $DB_DATABASE"
    
    touch "$DB_DATABASE"
    
    # Ensure proper permissions
    chmod 644 "$DB_DATABASE"
    
    echo "Database file created successfully."
else
    echo "Database file already exists at: $DB_DATABASE"
fi


# Cache the various components of the Laravel application
php artisan optimize
