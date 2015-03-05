#!/usr/bin/env sh

set -e

echo "Starting XVFB"
sh -e /etc/init.d/xvfb start
sleep 4

echo "Downloading Selenium"
curl -L http://selenium-release.storage.googleapis.com/2.44/selenium-server-standalone-2.44.0.jar > selenium.jar

echo "Running Selenium"
java -jar selenium.jar > /dev/null 2> /tmp/webdriver_output.txt &
