#!/usr/bin/env sh

set -e

echo "Starting XVFB"
sh -e /etc/init.d/xvfb start
sleep 4

SELENIUM_BINARY="$DOWNLOADS_FOLDER/selenium_251.jar"
CHROME_DRIVER_ARCHIVE="$DOWNLOADS_FOLDER/chromedriver_linux64.zip"
CHROME_DRIVER_BINARY="$DOWNLOADS_FOLDER/chromedriver"

if [ ! -e "$CHROME_DRIVER_BINARY" ]; then
	echo "Downloading Chrome Driver"
	curl -L https://chromedriver.storage.googleapis.com/2.32/chromedriver_linux64.zip > "$CHROME_DRIVER_ARCHIVE"
	unzip "$CHROME_DRIVER_ARCHIVE"
fi

if [ ! -e "$SELENIUM_BINARY" ]; then
	echo "Downloading Selenium"
	curl -L http://selenium-release.storage.googleapis.com/2.51/selenium-server-standalone-2.51.0.jar > "$SELENIUM_BINARY"
fi

echo "Running Selenium"
java -jar "$SELENIUM_BINARY" > /dev/null 2> /tmp/webdriver_output.txt &
