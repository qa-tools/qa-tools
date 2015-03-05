#!/usr/bin/env sh

set -e

echo "Installing Apache"
apt-get update -qq
apt-get install -qq -y --force-yes apache2 libapache2-mod-php5

echo "Configuring the vhost"
sed -i -e "s,/var/www,$(pwd),g" /etc/apache2/sites-available/default
/etc/init.d/apache2 restart
