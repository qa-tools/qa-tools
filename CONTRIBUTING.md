# Contributing
QA-Tools is an open source, community-driven project. If you'd like to contribute, feel free to do this, but remember to follow these few simple rules:

## Asking Questions
Feel free to ask any questions and share your experiences in the [Chat Room](https://gitter.im/qa-tools/qa-tools) and help to improve the documentation.

## Submitting an issues
- A reproducible example is required for every bug report, otherwise it will most probably be __closed without warning__.
- If you are going to make a big, substantial change, let's discuss it first.

## Working with Pull Requests
1. Create your feature addition or a bug fix branch based on __`master`__ branch in your repository's fork.
2. Make necessary changes, but __don't mix__ code reformatting with code changes on topic.
3. Write documentation in `/docs` folder (is applicable).
4. Add entry in `CHANGELOG.md` file following http://keepachangelog.com/ format (if applicable).
5. Add tests for those changes (please look into `tests/` folder for some examples). This is important so we don't break it in a future version unintentionally.
6. Check your code using "Coding Standard" (see below).
7. Commit your code.
8. Squash your commits by topic to preserve a clean and readable log.
9. Create Pull Request.

## Running the Tests

### Installation/Configuration

1. download "Selenium Standalone Server" from [http://www.seleniumhq.org/download/](http://www.seleniumhq.org/download/) url
2. at cloned repository root copy `phpunit.xml.dist` into `phpunit.xml` and set values for environment variables defined in there:
 * `WEB_FIXTURE_BROWSER` - browser to use
 * `WEB_FIXTURE_HOST` - hostname/ip address of computer, where "Selenium Standalone Server" will be running
 * `WEB_FIXTURE_PORT` - port on which Selenium Server will be running
 * `WEB_FIXTURE_URL` - url pointing at location of cloned repository root in the web browser

### Usage

1. start "Selenium Standalone Server" (only when not running already):
 * go to folder, where "Selenium Standalone Server" was downloaded
 * execute this command: `java -jar selenium-server-standalone-X.Y.Z.jar` (replace `selenium-server-standalone-X.Y.Z.jar` with downloaded file name)
2. make sure that you don't break anything with your changes by running:

```bash
$> phpunit
```

## Checking coding standard violations

This library uses [Coding Standard](https://github.com/aik099/CodingStandard) to ensure consistent formatting across the code base. Make sure you haven't introduced any Coding Standard violations by running following command in the root folder of the library:

```bash
$> phpcs --standard="vendor/aik099/coding-standard/CodingStandard" library tests
```

or by making your IDE ([instructions for PhpStorm](http://www.jetbrains.com/phpstorm/webhelp/using-php-code-sniffer-tool.html)) to check them automatically.

## Contributor Code of Conduct

Please note that this project is released with a [Contributor Code of
Conduct](http://contributor-covenant.org/). By participating in this project
you agree to abide by its terms. See [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) file.
