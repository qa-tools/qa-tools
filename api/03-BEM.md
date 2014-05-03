---
layout:     page
title:      BEM
categories: [api]
gh-file:    api/03-BEM.md
---

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

[Continue to Examples](/examples/03-BEM)
