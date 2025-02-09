#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x run-cron.sh`

# Run the Laravel scheduler at the start every minute
while [ true ]; do
    # Get current time as a floating-point number (seconds + nanoseconds)
    current_time=$(date +%S.%N)

    # Compute how many seconds to sleep until the next full minute
    sleep_time=$(echo "60 - $current_time" | bc)

#    echo "Waiting $sleep_time seconds for the next full minute..."
    sleep $sleep_time  # Wait until `XX:YY:00.000`

#    echo "Running scheduler at: $(date +'%Y-%m-%d %H:%M:%S.%3N')"
    php artisan schedule:run --verbose --no-interaction &
done
