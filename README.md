# QA-Tools

[![Join the chat at https://gitter.im/qa-tools/qa-tools](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/qa-tools/qa-tools?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![CI](https://github.com/qa-tools/qa-tools/actions/workflows/tests.yml/badge.svg)](https://github.com/qa-tools/qa-tools/actions/workflows/tests.yml)
[![Docs](https://github.com/qa-tools/qa-tools/actions/workflows/docs.yml/badge.svg)](https://github.com/qa-tools/qa-tools/actions/workflows/docs.yml)
[![Documentation](https://readthedocs.org/projects/qa-tools/badge/?version=latest)](http://qa-tools.readthedocs.org/en/latest/)
[![composer.lock](https://poser.pugx.org/qa-tools/qa-tools/composerlock)](https://packagist.org/packages/qa-tools/qa-tools)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/qa-tools/qa-tools/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/qa-tools/qa-tools/?branch=master)
[![codecov](https://codecov.io/gh/qa-tools/qa-tools/branch/master/graph/badge.svg)](https://codecov.io/gh/qa-tools/qa-tools)
[![Dependency Status](https://www.versioneye.com/user/projects/53e1e5d1ebe4a1b38d00000a/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53e1e5d1ebe4a1b38d00000a)


[![Latest Stable Version](https://poser.pugx.org/qa-tools/qa-tools/v/stable)](https://packagist.org/packages/qa-tools/qa-tools)
[![Total Downloads](https://poser.pugx.org/qa-tools/qa-tools/downloads)](https://packagist.org/packages/qa-tools/qa-tools)
[![Latest Unstable Version](https://poser.pugx.org/qa-tools/qa-tools/v/unstable)](https://packagist.org/packages/qa-tools/qa-tools)
[![License](https://poser.pugx.org/qa-tools/qa-tools/license)](https://packagist.org/packages/qa-tools/qa-tools)

QA-Tools is a set of quality assurance tools to be used during acceptance tests writing on PHP.

Library implements __PageObject pattern__, used in variety of testing frameworks (e.g. Selenium browser automation framework).

## Website and Documentation

* Website: [https://qa-tools.github.io/](https://qa-tools.github.io/)
* Documentation: [https://qa-tools.readthedocs.io/](https://qa-tools.readthedocs.io/)
* Demo using PHPUnit: [https://github.com/qa-tools/phpunit-example/](https://github.com/qa-tools/phpunit-example/)

## Asking Questions

Feel free to ask any questions and share your experiences in the [Chat Room](https://gitter.im/qa-tools/qa-tools) and help to improve the documentation.

## Installation

* execute this command to add dependencies: `php composer.phar require qa-tools/qa-tools:^1.0`

## Requirements

* [Composer](https://getcomposer.org/download/)
* [MinkExtension](https://github.com/Behat/MinkExtension), when [Behat](https://github.com/Behat/Behat) is used.
* [QA-Tools / PHPUnit Extension](https://github.com/qa-tools/phpunit-extension), when [PHPUnit](https://github.com/sebastianbergmann/phpunit) is used.
* [Mink](https://github.com/minkphp/Mink) in other cases.

## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) file.

## License

QA-Tools is released under the BSD-3-Clause License. See the bundled [LICENSE](LICENSE) file for details.
