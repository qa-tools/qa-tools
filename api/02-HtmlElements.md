---
layout:     page
title:      HtmlElements
categories: [api]
gh-file:    api/02-HtmlElements.md
---

## aik099\QATools\HtmlElements
Depends on `\aik099\QATools\PageObject`.

Solution of the major problem with PageObject implementation (that original library had) - each element had all methods and even ones, that have no effect on element itself. For example a `setValue` method existed for a `H1` tag.

### Class Overview

#### Elements (namespace: `\aik099\QATools\HtmlElements\Element`)

| Name | Description |
| ------------- | ------------- |
| `AbstractTypifiedElement` | Base class for all other elements in this library, that wraps around WebElement and only exposes methods, that are common for all elements. |
| `AbstractTypifiedElementCollection` | Base class for all other elements in this library, that operate on a group of typified elements. |
| `AbstractElementContainer` | Base class for creating dedicated classes, that will encapsulate associated elements on a page (typified version of `\aik099\QATools\PageObject\AbstractElementContainer` class). |
| `LabeledElement` | Element, that has associated LABEL element on a page (e.g. radio button or a checkbox). |
| `Form` | Form. |
| `Checkbox` | Checkbox. |
| `Link` | Link. |
| `RadioGroup` | Group of radio buttons (extends `AbstractTypifiedElementCollection`). |
| `RadioButton` | Single radio button. |
| `Select` | Dropdown. |
| `TextBlock` | Div or span. |
| `TextInput` | Text box or text area. |
| `FileInput` | Single file upload. |
| `Button` | Button. |

#### Other

| Name | Description |
| ------------- | ------------- |
| `\aik099\QATools\HtmlElements\TypifiedPage` | Base class for creating dedicated classes for each of website pages, that needs to be tested. |

### Annotation Overview

| Name | Description |
| ------------- | ------------- |
| `@name` | Sets optional element name to be used instead of ClassName in error messages (e.g. when element was not found on a page). |

### Usage

1. create subclass from `\aik099\QATools\HtmlElements\TypifiedPage` class
2. add class properties, that have any element class from described above in their `@var` annotation
3. create public method, that would use properties defined before

[Continue to Examples](/examples/02-HtmlElements)
