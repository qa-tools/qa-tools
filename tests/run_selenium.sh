#!/usr/bin/env sh

set -e

DOWNLOADS_FOLDER="${HOME}/downloads"

if [ ! -e "${DOWNLOADS_FOLDER}" ]; then
  mkdir "${DOWNLOADS_FOLDER}";
fi;

SELENIUM_BINARY="$DOWNLOADS_FOLDER/selenium_314159.jar"

if [ ! -e "$SELENIUM_BINARY" ]; then
	echo "Downloading Selenium"
	curl -L https://selenium-release.storage.googleapis.com/3.141/selenium-server-standalone-3.141.59.jar > "$SELENIUM_BINARY"
fi

echo "Running Selenium"
java -jar "$SELENIUM_BINARY" > /dev/null 2> /tmp/webdriver_output.txt &
