---
layout:     page
title:      HtmlElements
categories: [api]
gh-file:    api/02-HtmlElements.md
---

## aik099\QATools\HtmlElements
Depends on `aik099\QATools\PageObject`.

Solution of the major problem with PageObject implementation (that original library had) - each element had all methods and even ones, that have no effect on element itself. For example a `setValue` method existed for a `H1` tag.

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

[Continue to Examples](/examples/02-HtmlElements)
