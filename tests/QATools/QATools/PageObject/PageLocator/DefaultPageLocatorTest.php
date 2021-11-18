<?php
/**
 * This file is part of the QA-Tools library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/qa-tools/qa-tools
 */

namespace tests\QATools\QATools\PageObject\PageLocator;


use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use QATools\QATools\PageObject\PageLocator\DefaultPageLocator;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectException;
use Yoast\PHPUnitPolyfills\Polyfills\ExpectExceptionMessageMatches;

class DefaultPageLocatorTest extends TestCase
{

	use MockeryPHPUnitIntegration, ExpectException, ExpectExceptionMessageMatches;

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\QATools\\QATools\\PageObject\\PageLocator\\DefaultPageLocator';

	public function testEmptyPrefix()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\PageFactoryException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_PAGE_MISSING_PREFIXES);
		$this->expectExceptionMessage('No namespace prefixes passed');

		new $this->locatorClass(array());
	}

	public function testEmptyPageName()
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\PageFactoryException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_PAGE_NAME_MISSING);
		$this->expectExceptionMessage('No page name given');

		/** @var DefaultPageLocator $locator */
		$locator = new $this->locatorClass(array('\\'));

		$locator->resolvePage('');
	}

	/**
	 * @dataProvider invalidPageClassNamesDataProvider
	 */
	public function testResolvePageFailure($prefixes, $class_name)
	{
		$this->expectException('\QATools\QATools\PageObject\Exception\PageFactoryException');
		$this->expectExceptionCode(\QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_PAGE_CLASS_NOT_FOUND);
		$this->expectExceptionMessageMatches('/^None of the possible classes were found: (.+)$/');

		/** @var DefaultPageLocator $locator */
		$locator = new $this->locatorClass($prefixes);

		$locator->resolvePage($class_name);
	}

	public function invalidPageClassNamesDataProvider()
	{
		return array(
			array(
				array('\\'),
				'SomeNonExistingClassName',
			),
			array(
				array('\\Example'),
				'SomeNonExistingClassName',
			),
		);
	}

	/**
	 * @dataProvider resolvePageUsingPrefixDataProvider
	 */
	public function testResolvePageUsingPrefix($class_name, $prefixes, $expected_resolved_page_class)
	{
		/** @var DefaultPageLocator $locator */
		$locator = new $this->locatorClass($prefixes);

		m::mock($expected_resolved_page_class);

		$this->assertEquals($expected_resolved_page_class, $locator->resolvePage($class_name));
	}

	public function resolvePageUsingPrefixDataProvider()
	{
		return array(
			array(
				'ResolvePageMockedPrefixedClass',
				array('\\'),
				'\\ResolvePageMockedPrefixedClass',
			),
			array(
				'ResolvePageMockedPrefixedClass',
				array('NamespaceWithoutLeading'),
				'\\NamespaceWithoutLeading\\ResolvePageMockedPrefixedClass',
			),
			array(
				'ResolvePageMockedPrefixedClass',
				array('\\NamespaceWithLeading'),
				'\\NamespaceWithLeading\\ResolvePageMockedPrefixedClass',
			),
			array(
				'ResolvePageMockedPrefixedClass',
				array('NamespaceWithTrailing\\'),
				'\\NamespaceWithTrailing\\ResolvePageMockedPrefixedClass',
			),
			array(
				'ResolvePageMockedPrefixedClass',
				array('\\NamespaceWithLeadingAndTrailing\\'),
				'\\NamespaceWithLeadingAndTrailing\\ResolvePageMockedPrefixedClass',
			),
			array(
				'ResolvePageMockedPrefixedClass',
				array('\\Two\\Levels'),
				'\\Two\\Levels\\ResolvePageMockedPrefixedClass',
			),
			array(
				'ResolvePageMockedPrefixedClassInNamespace',
				array('\\Dummy', 'Example'),
				'\\Example\\ResolvePageMockedPrefixedClassInNamespace',
			),
			array(
				'resolve page mocked prefixed class',
				array('\\'),
				'\\ResolvePageMockedPrefixedClass',
			),
			array(
				'resolve page mocked prefixed class in namespace',
				array('Example', '\\Dummy'),
				'\\Example\\ResolvePageMockedPrefixedClassInNamespace',
			),
		);
	}

}
