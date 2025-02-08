#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x pre-deploy.sh`

# Exit the script if any command fails
set -e

#
ls . /db

echo "What is the value of DB_DATABASE";
echo "$DB_DATABASE";

if [ ! -d "/db"]; then
    echo "/db does not exist!???!?!???!?!?!??!"
    exit 1
fi

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
