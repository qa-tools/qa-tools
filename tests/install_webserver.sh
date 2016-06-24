#!/usr/bin/env sh

set -e

echo "Starting Web Server"

# Start a webserver for web fixtures. Force using PHP 5.6 to be able to run it on PHP 5.3 and HHVM jobs too
~/.phpenv/versions/5.6/bin/php -S localhost:8000 -t $(pwd) > /dev/null 2> /tmp/webserver_output.txt &
