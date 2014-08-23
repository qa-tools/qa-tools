# QA-Tools

[![Build Status](https://travis-ci.org/qa-tools/qa-tools.png?branch=master)](https://travis-ci.org/qa-tools/qa-tools)
[![HHVM Status](http://hhvm.h4cc.de/badge/qa-tools/qa-tools.png)](http://hhvm.h4cc.de/package/qa-tools/qa-tools)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/qa-tools/qa-tools/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/qa-tools/qa-tools/?branch=master)
[![Coverage Status](https://img.shields.io/coveralls/qa-tools/qa-tools.svg)](https://coveralls.io/r/qa-tools/qa-tools)
[![Dependency Status](https://www.versioneye.com/user/projects/53e1e5d1ebe4a1b38d00000a/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53e1e5d1ebe4a1b38d00000a)

[![Latest Stable Version](https://poser.pugx.org/qa-tools/qa-tools/v/stable.png)](https://packagist.org/packages/qa-tools/qa-tools)
[![Total Downloads](https://poser.pugx.org/qa-tools/qa-tools/downloads.png)](https://packagist.org/packages/qa-tools/qa-tools)

Quality assurance utilities for functional tests written on PHP.

Library implements __PageObject pattern__, used in variety of testing frameworks (e.g. Selenium browser automation framework).

## Website and Documentation

* [http://www.qa-tools.io](http://www.qa-tools.io)

## Asking Questions
Feel free to ask any questions and share your experiences on the [Support Page](http://www.qa-tools.io/support/) and help to improve the documentation.

## Installation using Composer

Define the dependencies in your ```composer.json```:

```json
{
	"require": {
		"qa-tools/qa-tools": "~1.0",
		"mindplay/annotations": "dev-master"
	}
}
```

Install/update your vendors:

```bash
$ curl http://getcomposer.org/installer | php
$ php composer.phar install
```
