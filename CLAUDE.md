# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

QA-Tools is a PHP library implementing the PageObject pattern for functional/acceptance testing on top of
[Mink](https://github.com/minkphp/Mink) (browser automation abstraction, Selenium2 driver in CI). Minimum supported
PHP is 7.4 (raised from 5.6, PR #233). Consumers typically use this library via a PHPUnit or Behat integration;
this repo only contains the core library and its own test suite.

## Commands

- Install dependencies: `composer install`
- Run tests: `vendor/bin/phpunit` (copy `phpunit.xml.dist` to `phpunit.xml` first and adjust the `WEB_FIXTURE_*`
  server variables — see below)
- Run a single test: `vendor/bin/phpunit --filter testMethodOrClassName tests/QATools/QATools/PageObject/SomeTest.php`
- Run one test suite: `vendor/bin/phpunit --testsuite PageObject` (suites: `PageObject`, `HtmlElements`, `BEM`, `Live`)
- Check coding standard: `vendor/bin/phpcs --standard="vendor/aik099/coding-standard/CodingStandard" library tests`
- Build docs locally: `sphinx-build -nW -b html -d docs/build/doctrees docs docs/build/html` (requires Sphinx +
  sphinx_rtd_theme)

### Test environment requirement

Most tests (outside `Live`) don't require a running browser, but the suite still expects a working directory served
over HTTP and a Selenium server reachable via the `WEB_FIXTURE_*` PHP `server` variables declared in `phpunit.xml`
(`WEB_FIXTURE_BROWSER`, `WEB_FIXTURE_HOST`, `WEB_FIXTURE_PORT`, `WEB_FIXTURE_URL`). CI starts `php -S` and a Selenium
standalone server before invoking PHPUnit (see `.github/workflows/tests.yml` and `tests/run_selenium.sh`) — replicate
that locally if you need to run the full suite, including `Live`.

## Architecture

The library is split into three layers, each building on the previous one, all under
`library/QATools/QATools/` (PSR-0 autoloaded) with mirrored tests under `tests/QATools/QATools/`:

1. **PageObject** — the base layer. Central pieces:
   - `PageFactory` (`IPageFactory`) — entry point consumers instantiate with a Mink `Session`. Resolves page
     classes via `IPageLocator`, initializes `@page-url` annotations into a URL builder, and drives property
     decoration (turning typed class properties into lazily-resolved element proxies).
   - `Container` — a Pimple DI container wiring up the factory's collaborators (`config`, `annotation_manager`,
     `url_factory`, `url_normalizer`, `page_locator`, `page_url_matcher_registry`, `selenium_selector`). Can be
     replaced/extended by consumers or subclassed factories.
   - `Page` / `Container.php` element containers, `Property` (reflection over a class property + its annotations),
     `PropertyDecorator/*` (turns annotated properties into element proxies), `ElementLocator/*` (turns `@find-by`
     annotations into a locator), `PageLocator/*` (maps a short class name to a fully-qualified page class),
     `PageUrlMatcher/*` (matches current browser URL against `@page-url` annotations to answer `PageFactory::opened()`).
   - Annotations (`mindplay/annotations`) drive most of the wiring: `@find-by`, `@page-url`, `@timeout` are the base
     set; subclasses add their own (e.g. `@element-name`).
2. **HtmlElements** — adds typed element support on top of PageObject. `TypifiedPageFactory extends PageFactory`,
   registers the `@element-name` annotation, and swaps in `TypifiedPropertyDecorator` so annotated properties
   become instances of typed element classes instead of plain generic elements.
3. **BEM** — adds BEM (Block-Element-Modifier) CSS-methodology-aware page objects on top of the above
   (`BEMPage`, `BEMPageFactory`).

When extending factory behavior, subclass `PageFactory`/`TypifiedPageFactory` and override `createDecorator()` and/or
extend `annotationRegistry` in the constructor — that's the pattern both `TypifiedPageFactory` and `BEMPageFactory`
follow rather than modifying the base class.

## Mink 2.x forward-compatibility

Mink's unreleased `2-architecture-changes` branch changes `Element`/`DocumentElement`/`NodeElement`
constructors from `(xpath, Session)` to `(xpath, Driver, ElementFinder)`. `Page::__construct()` now uses
`Session::getPage()` instead of constructing `DocumentElement` directly (works on both architectures).
`tests/QATools/QATools/TestCase.php::usesNewMinkArchitecture()` detects which one is loaded via reflection on
`DocumentElement::__construct()`'s first parameter (falls back to parameter count on PHP <7.1, where
`ReflectionType::getName()`/`getType()` aren't fully available) — don't use `class_exists('...ElementFinder')`
for this, that class was backported into Mink 1.x (v1.11.0+) well before the constructor signature actually
changed, so it gives false positives. CI has a dedicated `PHP 8.x (Mink 2.x)` matrix job pinning
`behat/mink: dev-2-architecture-changes as 1.99`.

## Known environment quirk

On network-mounted checkouts of this repo, Composer's zip-extraction sometimes leaves a package unflattened
(`vendor/<pkg>/<vendor>-<repo>-<sha>/...` instead of `vendor/<pkg>/...`), breaking the classmap with
"Could not scan for classes" — copy the nested dir's contents up one level and delete it if `composer
dump-autoload` fails after an install/update.

## Contributing conventions (from CONTRIBUTING.md)

- Don't mix reformatting with functional changes in the same PR.
- Add/update docs under `docs/` and an entry in `CHANGELOG.md` (keepachangelog.com format) when applicable.
- Add tests under `tests/` mirroring the change.
- Squash commits by topic before opening a PR.
