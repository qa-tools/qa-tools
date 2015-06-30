# QA-Tools

[![Join the chat at https://gitter.im/qa-tools/qa-tools](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/qa-tools/qa-tools?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Build Status](https://travis-ci.org/qa-tools/qa-tools.svg?branch=master)](https://travis-ci.org/qa-tools/qa-tools)
[![HHVM Status](http://hhvm.h4cc.de/badge/qa-tools/qa-tools.svg)](http://hhvm.h4cc.de/package/qa-tools/qa-tools)
[![Documentation](https://readthedocs.org/projects/qa-tools/badge/?version=latest)](http://docs.qa-tools.io/en/latest/)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/qa-tools/qa-tools/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/qa-tools/qa-tools/?branch=master)
[![Coverage Status](https://img.shields.io/coveralls/qa-tools/qa-tools.svg)](https://coveralls.io/r/qa-tools/qa-tools)
[![Dependency Status](https://www.versioneye.com/user/projects/53e1e5d1ebe4a1b38d00000a/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53e1e5d1ebe4a1b38d00000a)

[![Latest Stable Version](https://poser.pugx.org/qa-tools/qa-tools/v/stable.png)](https://packagist.org/packages/qa-tools/qa-tools)
[![Total Downloads](https://poser.pugx.org/qa-tools/qa-tools/downloads.png)](https://packagist.org/packages/qa-tools/qa-tools)
[![Latest Unstable Version](https://poser.pugx.org/qa-tools/qa-tools/v/unstable.svg)](https://packagist.org/packages/qa-tools/qa-tools)
[![License](https://poser.pugx.org/qa-tools/qa-tools/license.svg)](https://packagist.org/packages/qa-tools/qa-tools)

QA-Tools is a set of quality assurance tools to be used during acceptance tests writing on PHP.

Library implements __PageObject pattern__, used in variety of testing frameworks (e.g. Selenium browser automation framework).

## Website and Documentation

* [http://www.qa-tools.io](http://www.qa-tools.io)

## Asking Questions

Feel free to ask any questions and share your experiences in the [Chat Room](https://gitter.im/qa-tools/qa-tools) and help to improve the documentation.

## Installation

Define the dependencies in your ```composer.json```:

```json
{
	"require": {
		"qa-tools/qa-tools": "~1.0",
		"mindplay/annotations": "~1.2@dev"
	}
}
```

Install/update your vendors:

```bash
$ curl http://getcomposer.org/installer | php
$ php composer.phar install
```

## Requirements

* If [Behat](https://github.com/Behat/Behat) is used, then [MinkExtension](https://github.com/Behat/MinkExtension).
* If [PHPUnit](https://github.com/sebastianbergmann/phpunit) is used, then [PHPUnit-Mink](https://github.com/minkphp/phpunit-mink).
* If other testing solution is used, then [Mink](https://github.com/minkphp/Mink).

## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) file.

## License

QA-Tools is released under the BSD-3-Clause License. See the bundled LICENSE file for details.
