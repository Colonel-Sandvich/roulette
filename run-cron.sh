#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-cron.sh`

# Run the Laravel scheduler at the start every minute
while [ true ]; do
    # Get current time as a floating-point number (seconds + nanoseconds)
    current_time=$(date +%S.%N)

    # Calculate time to sleep until the next full minute
    sleep_time=$(awk "BEGIN {print 60 - $current_time}")

    echo "Waiting $sleep_time seconds for the next full minute..."
    sleep $sleep_time # Wait until `XX:YY:00.000`

    echo "Running scheduler at: $(date +'%F %T.%3N')"
    php artisan schedule:run --verbose --no-interaction &
done
