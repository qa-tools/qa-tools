# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
- Added support for url parameter unmasking (e.g. `{param}` text in the url of `@page-url` annotation) by [@slde-gorillaman].

### Changed
...

### Fixed
...

## [1.1.0] - 2015-10-24
### Added
- Added `Page::getPageFactory` method by [@evangelion1204].
- Added `PageFactory::setSession` method.
- Added documentation on the ReadTheDocs service.
- Added `label` search option to the `@find-by` annotation by [@fonsecas72].
- Added `page_namespace_prefix` config setting (defaults to `\`) to be used during automatic page class detection by it's name by [@evangelion1204].
- Added `PageFactory::getPage` method, that would return page instance found by it's class or name (by default "Home page" page name would be mapped to "HomePage" page class) by [@evangelion1204].
- Added support for ports in the `base_url` config setting by [@evangelion1204].

### Changed
- When more then expected HTML tags were found by `@find-by` annotation, then exception is thrown by [@evangelion1204].
- Attempt to add non-supported HTML tags to the element (e.g. checkbox instead of radio button) now will result in exception by [@evangelion1204].

### Fixed
- Attempt to add several `@find-by` annotations to single page/element property resulted in exception by [@evangelion1204].
- When `path` and `query` from `base_url` config setting (when present) were overwritten by ones from `@page-url` annotation.
- The name of `TypyfiedElementCollection` sub-class wasn't set by proxy.
- The `WebElementCollection` was proxied by `WebElementProxy` instead of `WebElementCollectionProxy` resulting in Fatal Error.

## [1.0.1] - 2014-09-26 [YANKED]
### Fixed
- When `@timeout` annotation was used the element was looked for 1000 times longer period, then set by user (e.g. 5000 seconds instead 1 second).

## [1.0.0] - 2014-08-23
### Added
- Added unique error codes to all thrown exceptions.
- Added `HtmlElement::find` and `HtmlElement::findAll` methods for searching elements within that element.
- Added `TypifiedElement::isValid` method for checking element presence in DOM for cases, when it might have been deleted dynamically.
- Added `HtmlElement::getPageFactory` method to be used for nested `HtmlElement` instantiation.
- Added support for GET parameters to the `Page::getAbsoluteUrl` method by [@evangelion1204].
- Added `Radio::getValue` method for getting value of the radio button.
- Added support for passing extra URL parameters in the `@page-url` annotation via `params` annotation parameter by [@evangelion1204].
- Added `Property::isDataTypeArray` method for detecting if page/element property can store several HTML tags (e.g. `Checkbox[]`) by [@evangelion1204].
- Added `@timeout` annotation for elements, to allow continuously searching for missing element (e.g. when it's added after page loaded via AJAX).
- Added `WaitingElementLocator` element location that uses `@timeout` annotation for element searching.
- Added `AbstractElementCollection` base class that allows for several HTML tags on the page to match single property on page/element class.
- Added `Config` class to allow having library-wide configuration abilities by [@evangelion1204].
- Added ability to use relative page urls in `@page-url` annotations thanks to new `base_url` config setting by [@evangelion1204].
- Added support for forcing secure/non-secure url in the `@page-url` annotation via new `secure` annotation parameter by [@evangelion1204].
- Added `composer.lock` file for library contributors to use same dependency versions.
- Added support for multiple `@find-by` annotation usage on single page/element property (works by combining found HTML tags by each annotation into single array) by [@evangelion1204].
- Added `linkText`, `partialLinkText` and `idOrName` search options to the `@find-by` annotation.

### Changed
- Class namespaces changed - renamed all folder to singular form (e.g. `Element` instead of `Elements`).
- Some method signatures changed to accept `WebElement` instead of `IWebElement`.
- Moved proxy classes to `Proxy` sub-namespace.
- The page/element property decorator now uses interface (e.g. `IWebElement` or `ITypifiedElement`) to determine if property can be decorated (more flexible).
- Renamed `HtmlElement` into `ElementContainer` class.
- The `RadioGroup` class transformed into collection to simplify associated radio button management.
- Renamed `Radio` into `RadioButton` class.
- Added `Abstract` before names of abstract classes.
- Library root namespace changed from `aik099\QATools` into `QATools\QATools`.
- The argument of `@timeout` annotation is now in seconds, not milliseconds.

### Removed
- Removed `TypifiedElement::find` and `TypifiedElement::findAll` methods to indicate, that by default element doesn't allow nested elements presence.
- Removed bundled coding standard in favor of it's version from Packagist.
- Removed ability to use different annotation handling library.
- Removed `setContainer` and `getContainer` methods from element classes to decouple them from rest of the library.

### Fixed
- The PageFactory wasn't passed to Form typified element from the proxy class resulting in Fatal Error.
- The XPath-reserved symbols (e.g. `[`) were not escaped in form element names, searched within Form typified element resulting in no elements found.
- The `Radio::select` method was using incorrect Mink method to radio button checking, which prevented radio button selection on Mink drivers other then Selenium2.
- The `[]` at the end of page/element property data type resulted in Fatal Error.
- The locator was created for page/element properties, that can't be decorated (e.g. had interface as their data type) by [@evangelion1204].

## [0.0.1] - 2013-12-06
### Added
- Initial release.
- Bundling used coding standard.
- Integration with "Travis CI", "Coveralls IO", "Scrutinizer CI", "VersionEye" services.

### Removed
- Removed dependency on Selenium2.

[Unreleased]: https://github.com/qa-tools/qa-tools/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/qa-tools/qa-tools/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/qa-tools/qa-tools/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/qa-tools/qa-tools/compare/v0.0.1...v1.0.0
[@evangelion1204]: https://github.com/evangelion1204
[@fonsecas72]: https://github.com/fonsecas72
[@slde-gorillaman]: https://github.com/slde-gorillaman
