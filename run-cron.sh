#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-cron.sh`

# Run the Laravel scheduler at the start every minute
while [ true ]; do
    current_second=$(date +%S)
    sleep_time=$((60 - current_second))

#    echo "Waiting $sleep_time seconds for the next full minute..."
    sleep $sleep_time  # Wait until `XX:YY:00`

#    echo "Running scheduler at: $(date +'%Y-%m-%d %H:%M:%S')"
    php artisan schedule:run --verbose --no-interaction
done
