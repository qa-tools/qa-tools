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

class DefaultPageLocatorTest extends TestCase
{

	use MockeryPHPUnitIntegration;

	/**
	 * Locator class.
	 *
	 * @var string
	 */
	protected $locatorClass = '\\QATools\\QATools\\PageObject\\PageLocator\\DefaultPageLocator';

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\PageFactoryException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_PAGE_MISSING_PREFIXES
	 * @expectedExceptionMessage No namespace prefixes passed
	 */
	public function testEmptyPrefix()
	{
		new $this->locatorClass(array());
	}

	/**
	 * @expectedException \QATools\QATools\PageObject\Exception\PageFactoryException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_PAGE_NAME_MISSING
	 * @expectedExceptionMessage No page name given
	 */
	public function testEmptyPageName()
	{
		/** @var DefaultPageLocator $locator */
		$locator = new $this->locatorClass(array('\\'));

		$locator->resolvePage('');
	}

	/**
	 * @dataProvider invalidPageClassNamesDataProvider
	 *
	 * @expectedException \QATools\QATools\PageObject\Exception\PageFactoryException
	 * @expectedExceptionCode \QATools\QATools\PageObject\Exception\PageFactoryException::TYPE_PAGE_CLASS_NOT_FOUND
	 * @expectedExceptionMessageRegExp /^None of the possible classes were found: (.+)$/
	 */
	public function testResolvePageFailure($prefixes, $class_name)
	{
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
