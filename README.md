# QA-Tools
[![Build Status](https://travis-ci.org/aik099/qa-tools.png?branch=master)](https://travis-ci.org/aik099/qa-tools)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/aik099/qa-tools/badges/quality-score.png?s=d31359d014d2f9cf76e3734055b2dadd5925eb8e)](https://scrutinizer-ci.com/g/aik099/qa-tools/)
[![Coverage Status](https://coveralls.io/repos/aik099/qa-tools/badge.png?branch=master)](https://coveralls.io/r/aik099/qa-tools?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/52ad65f5ec1375a2870000b2/badge.png)](https://www.versioneye.com/user/projects/52ad65f5ec1375a2870000b2)

[![Latest Stable Version](https://poser.pugx.org/aik099/qa-tools/v/stable.png)](https://packagist.org/packages/aik099/qa-tools)
[![Total Downloads](https://poser.pugx.org/aik099/qa-tools/downloads.png)](https://packagist.org/packages/aik099/qa-tools)

Quality assurance utilities for functional tests.

Library implements [Page Object Pattern](docs/PageObjectPattern.md), used in variety of testing frameworks (e.g. Selenium browser automation framework).

Required dependencies:

* [behat/mink](https://github.com/Behat/Mink) - for browser interaction
* [mindplay/annotations](https://github.com/mindplay-dk/php-annotations) - for annotation handling

## aik099\QATools\PageObject
No internal dependencies.

Implementation of Page Object pattern as done in Selenium Java library.

### Class Overview


| Name | Description |
| ------------- | ------------- |
| `\aik099\QATools\PageObject\Page` | Abstract class for creating dedicated classes for each of the website pages, that needs to be tested (descendant of [DocumentElement](http://mink.behat.org/api/behat/mink/element/documentelement.html)). |
| `\aik099\QATools\PageObject\Element\WebElement` | Class for interacting with __one__ element on page (descendant of [NodeElement](http://mink.behat.org/api/behat/mink/element/nodeelement.html)). |
| `\aik099\QATools\PageObject\Element\ElementContainer` | Abstract class for creating dedicated classes, that will encapsulate associated elements on a page. |

### Annotation Overview

| Name | Description |
| ------------- | ------------- |
| `@find-by` | Tells how to find element on a page. |
| `@page-url` | Sets default url, associated with a Page. |

### Usage

1. create subclass from `\aik099\QATools\PageObject\Page` class
2. add class properties, that have `\aik099\QATools\PageObject\Element\WebElement` or `\aik099\QATools\PageObject\Element\ElementContainer` in their `@var` annotation
3. create public method(-s), that would use properties defined before

[Continue to Examples](docs/PageObject.md)

## aik099\QATools\HtmlElements
Depends on `aik099\QATools\PageObject`.

This library solves major problem with PageObject implementation, that original library had - each element had all methods and even ones, that have no effect on element itself. For example a `setValue` method existed for a H1 tag.

### Class Overview

| Name | Description |
| ------------- | ------------- |
| `\aik099\QATools\HtmlElements\TypifiedPage` | Abstract class for creating dedicated classes for each of website pages, that needs to be tested. |
| `\aik099\QATools\HtmlElements\TypifiedElement` | Base class for all other elements in this library, that wraps around WebElement and only exposes methods, that are common for all elements. |
| `\aik099\QATools\HtmlElements\ElementContainer` | Abstract class for creating dedicated classes, that will encapsulate associated elements on a page (typified version of `\aik099\QATools\PageObject\ElementContainer`). |
| `\aik099\QATools\HtmlElements\LabeledElement` | Element, that has associated LABEL element on a page (e.g. radio button or a checkbox). |
| `\aik099\QATools\HtmlElements\Button` | Button. |
| `\aik099\QATools\HtmlElements\Checkbox` | Checkbox. |
| `\aik099\QATools\HtmlElements\Form` | Form. |
| `\aik099\QATools\HtmlElements\Link` | Link. |
| `\aik099\QATools\HtmlElements\RadioGroup` | Group of radio buttons. |
| `\aik099\QATools\HtmlElements\Select` | Dropdown. |
| `\aik099\QATools\HtmlElements\TextBlock` | Div or span. |
| `\aik099\QATools\HtmlElements\TextInput` | Text box or text area. |
| `\aik099\QATools\HtmlElements\FileInput` | Single file upload. |

### Annotation Overview

| Name | Description |
| ------------- | ------------- |
| `@name` | Sets optional element name to be used instead of ClassName in error messages (e.g. when element was not found on a page). |

### Usage

1. create subclass from `\aik099\QATools\HtmlElements\TypifiedPage` class
2. add class properties, that have `\aik099\QATools\PageObject\Element\WebElement` or any other element class described above in their `@var` annotation
3. create public method, that would use properties defined before

[Continue to Examples](docs/HtmlElements.md)

## aik099\QATools\BEM
Depends on `aik099\QATools\PageObject`.

According to BEM methodology (http://bem.info/) the following restrictions apply:

1. there can't be nested blocks
2. each element must be placed within a block

But single HTML node (or it's element) can be within different blocks at same time.

### Class Overview

| Name | Description |
| ------------- | ------------- |
| `\aik099\QATools\BEM\Element\Element` | Represents a single element on a page, that must be placed within a Block. |
| `\aik099\QATools\BEM\Element\Block` | Abstract class for creating dedicated classes, that will encapsulate associated elements on a page. |

### Annotation Overview

| Name | Description |
| ------------- | ------------- |
| `@bem` | Unified annotation for both Block and Element. |

### Usage

1. create a subclass from `\aik099\QATools\BEM\Element\Block` class to for each block on a page
2. add class properties, that have `\aik099\QATools\BEM\Element\Element` in their `@var` annotation for each individual element in each block (or alternatively use `$this->getElements` method in the `Block` class)
3. create subclass from `\aik099\QATools\BEM\BEMPage` class
4. add class property with previously created block subclass name in it's `@var` annotation for each individual block

[Continue to Examples](docs/BEM.md)

## Using Composer

1. Define the dependencies in your ```composer.json```:
```json
{
	"require": {
		"aik099/qa-tools": "dev-master"
	}
}
```

2. Install/update your vendors:
```bash
$ curl http://getcomposer.org/installer | php
$ php composer.phar install
```
