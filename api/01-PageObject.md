---
layout:     page
title:      PageObject
categories: [api]
gh-file:    api/01-PageObject.md
---

## aik099\QATools\PageObject
No internal dependencies.

Implementation of Page Object pattern as done in Selenium Java library.

### Class Overview

#### Elements (namespace: `\aik099\QATools\PageObject\Element`) 

| Name | Description |
| ------------- | ------------- |
| `WebElement` | Class for interacting with __one__ element on page (descendant of [NodeElement](http://mink.behat.org/api/behat/mink/element/nodeelement.html)). |
| `WebElementCollection` | Class for interacting with __several__ elements on page via iterator. |
| `AbstractElementContainer` | Base class for creating dedicated classes, that will encapsulate associated elements on a page. |

#### Other

| Name | Description |
| ------------- | ------------- |
| `\aik099\QATools\PageObject\Page` | Base class for creating dedicated classes for each of the website pages, that needs to be tested (descendant of [DocumentElement](http://mink.behat.org/api/behat/mink/element/documentelement.html)). |

### Annotation Overview

| Name | Description |
| ------------- | ------------- |
| `@find-by` | Tells how to find element on a page. |
| `@page-url` | Sets default url, associated with a Page. |

### Usage

1. create subclass from `\aik099\QATools\PageObject\Page` class
2. add class properties, that have any element class from described above in their `@var` annotation
3. create public method(-s), that would use properties defined before

[Continue to Examples](/examples/01-PageObject)
