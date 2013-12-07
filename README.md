# QA-Tools
[![Build Status](https://travis-ci.org/aik099/qa-tools.png?branch=master)](https://travis-ci.org/aik099/qa-tools)
[![Coverage Status](https://coveralls.io/repos/aik099/qa-tools/badge.png?branch=master)](https://coveralls.io/r/aik099/qa-tools?branch=master)

Quality assurance utilities for functional tests.

Library implements [Page Object Pattern](docs/PageObjectPattern.md), used in variety of testing frameworks (e.g. Selenium browser automation framework).

Required dependencies:

* [Behat/Mink](https://github.com/Behat/Mink) - for browser interaction
* [Behat/MinkSelenium2Driver](https://github.com/Behat/MinkSelenium2Driver) - for using Selenium Grid
* [mindplay/annotations](https://github.com/aik099/php-annotations) - for annotation handling

## aik099\QATools\PageObject
No internal dependencies.

Implementation of Page Object pattern as done in Selenium Java library.

### Class Overview

* `\aik099\QATools\PageObject\Page` (descendant of [Behat\Mink\Element\DocumentElement](http://mink.behat.org/api/behat/mink/element/documentelement.html)) - abstract class for creating dedicated classes for each of website pages, that needs to be tested
* `\aik099\QATools\PageObject\Elements\WebElement` (descendant of [Behat\Mink\Element\NodeElement](http://mink.behat.org/api/behat/mink/element/nodeelement.html)) - class for interacting with __one__ element on page.
* `\aik099\QATools\PageObject\Elements\HtmlElement` - abstract class for creating dedicated classes, that will encapsulate associated elements on a page.

### Annotation Overview

* `@find-by`
* `@page-url`


### Usage

1. create subclass from `\aik099\QATools\PageObject\Page` class
2. add class properties, that have `\aik099\QATools\PageObject\Elements\WebElement` or `\aik099\QATools\PageObject\Elements\HtmlElement` in their `@var` annotation
3. create public method, that would use properties defined before

[Continue to Examples](docs/PageObject.md)

## aik099\QATools\HtmlElements
Depends on `aik099\QATools\PageObject`.

This library solves major problem with PageObject implementation, that original library had - each element had all methods and even ones, that have no effect on element itself. For example a `setValue` method existed for a H1 tag.

### Class Overview

* `\aik099\QATools\HtmlElements\TypifiedPage` - abstract class for creating dedicated classes for each of website pages, that needs to be tested
* `\aik099\QATools\HtmlElements\TypifiedElement` - base class for all other elements in this library, that wraps around WebElement and only exposes methods, that are common for all elements
* `\aik099\QATools\HtmlElements\HtmlElement` - abstract class for creating dedicated classes, that will encapsulate associated elements on a page (typified version of `\aik099\QATools\PageObject\HtmlElement`)
* `\aik099\QATools\HtmlElements\LabeledElement` - element, that has associated LABEL element on a page (e.g. radio button or a checkbox)
* `\aik099\QATools\HtmlElements\Button` - button
* `\aik099\QATools\HtmlElements\Checkbox` - checkbox
* `\aik099\QATools\HtmlElements\Form` - form
* `\aik099\QATools\HtmlElements\Link` - link
* `\aik099\QATools\HtmlElements\RadioGroup` - group of radio buttons
* `\aik099\QATools\HtmlElements\Select` - dropdown
* `\aik099\QATools\HtmlElements\TextBlock` - div or span
* `\aik099\QATools\HtmlElements\TextInput` - text box or text area
* `\aik099\QATools\HtmlElements\FileInput` - single file upload


### Annotation Overview

* `@name`

### Usage

1. create subclass from `\aik099\QATools\HtmlElements\TypifiedPage` class
2. add class properties, that have `\aik099\QATools\PageObject\Elements\WebElement` or any other element class described above in their `@var` annotation
3. create public method, that would use properties defined before

[Continue to Examples](docs/HtmlElements.md)

## aik099\QATools\BEM
Depends on `aik099\QATools\PageObject`.

### Class Overview

### Annotation Overview

### Usage

* TODO

## Using Composer

1. Define the dependencies in your ```composer.json```:
```json
{
	"require": {
		"aik099/qa-tools": "dev-master",
		"mindplay/php-annotations": "dev-master"
	},
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/aik099/qa-tools"
		},
		{
			"type": "vcs",
			"url": "https://github.com/aik099/php-annotations"
		}
	]
}
```

2. Install/update your vendors:
```bash
$ curl http://getcomposer.org/installer | php
$ php composer.phar install
```