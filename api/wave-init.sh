#!/bin/bash

cd /var/www/scaffold

chmod -R +x ../vendor/wave-framework/wave/bin

php ../vendor/wave-framework/wave/bin/generate/routes
php ../vendor/wave-framework/wave/bin/generate/views

chown -R www-data /var/www/api-mobile