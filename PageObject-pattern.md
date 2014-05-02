---
layout: page
title: Page Object Pattern
---

## Purpose

* Consolidates the code for interacting with any given UI element
* Allows you to __model the UI__ in your tests
* Exposes methods that reflect the things a user __see__ and __do__ on that page, e.g.
 * addItemToCart(), getPrice()
 * getEntryTitle(), saveDraft(), publishEntry()
* Hides the details of telling the browser __how__ to do those things

## Results

* Test code is very readable
* No duplication of Selenium API calls
* Interactive documentation via Ctrl-Space
* Eminently reusable code
* Improved maintainability
* Provide opportunity to make test creation __extremely__ quick (and maybe even fun!)

## What else

* All Page Object methods must return a reference to Page Object
 * If the "user action" moves the focus to a different page, the method should return that page object
 * Otherwise, return the same page object

## More Reading
* [Page Objects in Selenium](https://code.google.com/p/selenium/wiki/PageObjects)
* [Page Object](http://martinfowler.com/bliki/PageObject.html) by Martin Fowler
* [Using the Page Object Pattern to Improve Functional Test Maintainability](http://www.slideshare.net/dantebriones/using-the-page-object-pattern) by Dante Briones
