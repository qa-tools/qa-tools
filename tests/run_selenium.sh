#!/usr/bin/env sh

set -e

SELENIUM_BINARY="$DOWNLOADS_FOLDER/selenium_360.jar"
CHROME_DRIVER_ARCHIVE="$DOWNLOADS_FOLDER/chromedriver_linux64.zip"
CHROME_DRIVER_BINARY="$DOWNLOADS_FOLDER/chromedriver"

if [ ! -e "$CHROME_DRIVER_BINARY" ]; then
	echo "Downloading Chrome Driver"
	curl -L https://chromedriver.storage.googleapis.com/2.32/chromedriver_linux64.zip > "$CHROME_DRIVER_ARCHIVE"
	unzip "$CHROME_DRIVER_ARCHIVE"
fi

if [ ! -e "$SELENIUM_BINARY" ]; then
	echo "Downloading Selenium"
	curl -L http://selenium-release.storage.googleapis.com/3.6/selenium-server-standalone-3.6.0.jar > "$SELENIUM_BINARY"
fi

echo "Running Selenium"
java -jar "$SELENIUM_BINARY" > /dev/null 2> /tmp/webdriver_output.txt &
