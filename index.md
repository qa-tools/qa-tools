---
layout: default
title: Home
---

It's no secret, that using of the [PageObject](/PageObject-pattern) pattern can
drastically simplify functional test writing, but there was no PHP implementation
at the moment, that will be close enough to the Selenium Java version.

I decided to fill that gap and created this library which uses all the good parts
of the Java implementation, like:

* element selector annotations (e.g. `@find-by('css' => '....')`)
* element proxies (allows to lazy-load elements as you go)
* rock solid architecture

and none of the bad parts, like `selectOptionByIndex` and such methods, that are
there just to preserve backwards compatibility.

## Overview

Library consist of following 3 parts, which one to use it's up to you:

* __PageObject__ - Implementation of Page Object pattern as done in Selenium Java library.
* __HtmlElements__ - Solution of the major problem with PageObject implementation (that original library had) - each element had all methods and even ones, that have no effect on element itself. For example a `setValue` method existed for a `H1` tag.
* __BEM__ - Locating elements on the page according to BEM (block-element-modificator) methodology (http://bem.info/).

Dive into action by looking at [the examples](/examples) or learn more about [the api](/api) first, it's up to you.

## Required dependencies

* [behat/mink](https://github.com/Behat/Mink) - for browser interaction
* [mindplay/annotations](https://github.com/mindplay-dk/php-annotations) - for annotation handling

## Installation using Composer

Define the dependencies in your ```composer.json```:

```json
{
	"require": {
		"aik099/qa-tools": "dev-master",
		"mindplay/annotations": "dev-master"
	}
}
```

Install/update your vendors:

```bash
$ curl http://getcomposer.org/installer | php
$ php composer.phar install
```
