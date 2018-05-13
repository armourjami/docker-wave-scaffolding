#!/bin/bash

# Always chown webroot for better mounting
#chown -Rf www-data:www-data /var/www

# Start supervisord and services
/usr/bin/supervisord -n -c /etc/supervisord.conf