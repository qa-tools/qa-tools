#!/usr/bin/env sh

set -e

echo "Starting XVFB"
sh -e /etc/init.d/xvfb start
sleep 4

SELENIUM_BINARY="$DOWNLOADS_FOLDER/selenium_251.jar"

if [ ! -e "$SELENIUM_BINARY" ]; then
	echo "Downloading Selenium"
	curl -L http://selenium-release.storage.googleapis.com/2.51/selenium-server-standalone-2.51.0.jar > "$SELENIUM_BINARY"
fi

echo "Running Selenium"
java -jar "$SELENIUM_BINARY" > /dev/null 2> /tmp/webdriver_output.txt &
