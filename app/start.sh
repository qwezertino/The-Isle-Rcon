#!/bin/bash

# Run composer install only if the vendor directory is missing
if [ ! -d "vendor" ]; then
  composer install
fi

# # Start the application
# php /var/www/html/index.php

# Start the OpenSwoole server in the background
php /var/www/html/index.php &

# Capture the correct PID of the OpenSwoole master process
MASTER_PID=$!

# Check if the master PID is valid
if ! ps -p $MASTER_PID > /dev/null; then
    echo "Failed to start OpenSwoole server"
    exit 1
fi

echo "OpenSwoole server started with PID $MASTER_PID"

# Wait for the OpenSwoole server to initialize
sleep 2

# Monitor the app directory for changes (excluding vendor and start.sh)
inotifywait -rm -e modify,create,delete --exclude "vendor|start.sh" /var/www/html |
while read -r directory events filename; do
    echo "Change detected in $directory$filename. Reloading OpenSwoole server..."

    # Trigger a reload on the OpenSwoole server
    kill -USR1 $MASTER_PID
done
