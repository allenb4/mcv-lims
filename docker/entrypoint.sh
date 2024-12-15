#!/usr/bin/env sh

role=${CONTAINER_ROLE:-app}

if [ "$role" = *"app"* ]; then 
    #App Entry Point

    echo "Running the role \"$role\" ..."

    php-fpm
 
elif [ "$role" = "worker" ]; then

    echo "Running the role \"$role\" scheduler..."
    /entrypoint-schedule.sh "$@" &
    

    echo "Running the role \"$role\" queue..."
    #Workers Entry Point
    php /var/www/artisan queue:work --verbose --tries=3 --timeout=90;

else
    echo "Could not match the container role \"$role\""
    exit 1
fi